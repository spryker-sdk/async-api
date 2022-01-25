<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model\PropelDatabase\Adapter\PostgreSql;

use RuntimeException;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Propel\Business\Model\PropelDatabase\Command\ExportDatabaseInterface;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Process\Process;

class ExportPostgreSqlDatabase implements ExportDatabaseInterface
{
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
     * @param string $backupPath
     *
     * @return void
     */
    public function exportDatabase($backupPath)
    {
        $this->runProcess(
            $this->getExportCommand($backupPath),
        );
    }

    /**
     * @param string $backupPath
     *
     * @return string
     */
    protected function getExportCommand($backupPath)
    {
        if ($this->useSudo()) {
            return $this->getSudoExportCommand($backupPath);
        }

        return $this->getExportCommandRemote($backupPath);
    }

    /**
     * @param string $backupPath
     *
     * @return string
     */
    protected function getExportCommandRemote($backupPath)
    {
        return sprintf(
            'pg_dump -h %s -p %s -U %s -F c -b -v -f %s %s',
            Config::get(PropelConstants::ZED_DB_HOST),
            Config::get(PropelConstants::ZED_DB_PORT),
            Config::get(PropelConstants::ZED_DB_USERNAME),
            $backupPath,
            Config::get(PropelConstants::ZED_DB_DATABASE),
        );
    }

    /**
     * @param string $backupPath
     *
     * @return string
     */
    protected function getSudoExportCommand($backupPath)
    {
        return $this->getExportCommandRemote($backupPath);
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
        $process->run();

        if (!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        $returnValue = (int)$process->getOutput();

        return (bool)$returnValue;
    }

    /**
     * @param string $command
     *
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess($command)
    {
        // Shim for Symfony 3.x, to be removed when Symfony dependency becomes 4.2+
        if (!method_exists(Process::class, 'fromShellCommandline')) {
            return new Process([$command], null, $this->getEnvironmentVariables());
        }

        return Process::fromShellCommandline($command, null, $this->getEnvironmentVariables());
    }

    /**
     * @return array<string, mixed>
     */
    protected function getEnvironmentVariables()
    {
        return [
            'PGPASSWORD' => Config::get(PropelConstants::ZED_DB_PASSWORD),
        ];
    }

    /**
     * @return bool
     */
    protected function useSudo()
    {
        return Config::get(PropelConstants::USE_SUDO_TO_MANAGE_DATABASE, true);
    }
}
