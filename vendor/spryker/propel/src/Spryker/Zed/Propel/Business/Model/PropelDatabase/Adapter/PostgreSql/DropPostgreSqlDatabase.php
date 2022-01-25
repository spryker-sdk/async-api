<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql;

use PDO;
use RuntimeException;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Exception\UnSupportedCharactersInConfigurationValueException;
use Spryker\Zed\Propel\Business\Exception\UnsupportedVersionException;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\DropDatabaseInterface;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Process\Process;

class DropPostgreSqlDatabase implements DropDatabaseInterface
{
    /**
     * @var string
     */
    protected const SHELL_CHARACTERS_PATTERN = '/\$|`/i';

    /**
     * @var \Spryker\Zed\Propel\PropelConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Propel\PropelConfig $config
     */
    public function __construct(PropelConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return void
     */
    public function dropDatabase()
    {
        $this->closeOpenConnections();

        $this->runDropCommand();
    }

    /**
     * @return bool
     */
    protected function runDropCommand()
    {
        return $this->runProcess($this->getDropCommand());
    }

    /**
     * @return void
     */
    protected function closeOpenConnections(): void
    {
        $pdoConnection = $this->createPdoConnection();
        $pdoConnection->exec($this->getCloseOpenedConnectionsQuery());
        unset($pdoConnection);
    }

    /**
     * @return string
     */
    protected function getDropCommand()
    {
        if ($this->useSudo()) {
            return $this->getSudoDropCommand();
        }

        return $this->getDropCommandRemote();
    }

    /**
     * @return string
     */
    protected function getDropCommandRemote()
    {
        return sprintf(
            'psql -h %s -p %s -U %s -w -c "DROP DATABASE IF EXISTS \"%s\"; " %s',
            Config::get(PropelConstants::ZED_DB_HOST),
            Config::get(PropelConstants::ZED_DB_PORT),
            $this->getConfigValue(PropelConstants::ZED_DB_USERNAME),
            $this->getConfigValue(PropelConstants::ZED_DB_DATABASE),
            'postgres',
        );
    }

    /**
     * @return string
     */
    protected function getSudoDropCommand()
    {
        return sprintf(
            'sudo dropdb %s --if-exists',
            $this->getConfigValue(PropelConstants::ZED_DB_DATABASE),
        );
    }

    /**
     * @param string $command
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    protected function runProcess($command)
    {
        $process = $this->getProcess($command);
        $process->setTimeout($this->config->getProcessTimeout());
        $process->run(null, $this->getEnvironmentVariables());

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        $returnValue = (int)$process->getOutput();

        return (bool)$returnValue;
    }

    /**
     * @return bool
     */
    protected function useSudo()
    {
        return Config::get(PropelConstants::USE_SUDO_TO_MANAGE_DATABASE, true);
    }

    /**
     * @param string $command
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\UnsupportedVersionException
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess($command)
    {
        // Shim for Symfony 3.x, to be removed when Symfony dependency becomes 4.2+
        if (!method_exists(Process::class, 'fromShellCommandline')) {
            if (version_compare(PHP_VERSION, '8.0.0', '>=') === true) {
                throw new UnsupportedVersionException('The minimum required version for symfony/process is 4.2.0 to work with PHP 8');
            }

            /**
             * @phpstan-ignore-next-line
             * @psalm-suppress InvalidArgument
             */
            return new Process($command);
        }

        return Process::fromShellCommandline($command);
    }

    /**
     * @param string $key
     *
     * @throws \Spryker\Zed\Propel\Business\Exception\UnSupportedCharactersInConfigurationValueException
     *
     * @return mixed
     */
    protected function getConfigValue(string $key)
    {
        $value = Config::get($key);
        if (preg_match(static::SHELL_CHARACTERS_PATTERN, $value)) {
            throw new UnSupportedCharactersInConfigurationValueException(sprintf(
                'Configuration value for key "%s" contains unsupported characters (\'$\',\'`\') that is forbidden by security reason.',
                $key,
            ));
        }

        return $value;
    }

    /**
     * @return string
     */
    protected function getCloseOpenedConnectionsQuery(): string
    {
        return sprintf('
            SELECT pg_terminate_backend(pg_stat_activity.pid)
                FROM pg_stat_activity
                WHERE pg_stat_activity.datname = \'%s\';
        ', $this->getConfigValue(PropelConstants::ZED_DB_DATABASE));
    }

    /**
     * @return \PDO
     */
    protected function createPdoConnection(): PDO
    {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=postgres',
            $this->getConfigValue(PropelConstants::ZED_DB_HOST),
            $this->getConfigValue(PropelConstants::ZED_DB_PORT),
        );

        return new PDO(
            $dsn,
            $this->getConfigValue(PropelConstants::ZED_DB_USERNAME),
            $this->getConfigValue(PropelConstants::ZED_DB_PASSWORD),
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function getEnvironmentVariables(): array
    {
        return [
            'PGPASSWORD' => $this->getConfigValue(PropelConstants::ZED_DB_PASSWORD),
        ];
    }
}
