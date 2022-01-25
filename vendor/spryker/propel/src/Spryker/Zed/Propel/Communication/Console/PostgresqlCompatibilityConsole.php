<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Console;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Spryker\Zed\Propel\PropelConfig;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 */
class PostgresqlCompatibilityConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'propel:pg-sql-compat';

    /**
     * @var string
     */
    public const OPTION_CORE = 'core';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->addOption(static::OPTION_CORE, 'c', InputOption::VALUE_NONE, 'Adjust core schema files too');
        $this->setDescription('Adjust Propel-XML schema files to work with PostgreSQL');

        parent::configure();
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (Config::get(PropelConstants::ZED_DB_ENGINE) !== PropelConfig::DB_ENGINE_PGSQL) {
            $this->info('This command doesn\'t support chosen DB engine');

            return static::CODE_SUCCESS;
        }

        $this->info('Adjust propel config for PostgreSQL and missing functions (group_concat)');
        $this->getFacade()->adjustPropelSchemaFilesForPostgresql();
        $this->getFacade()->adjustPostgresqlFunctions();

        $adjustCore = $this->input->getOption(static::OPTION_CORE);
        if ($adjustCore) {
            $this->info('Adjust propel config for PostgreSQL and missing functions (group_concat) - CORE');
            $this->getFacade()->adjustCorePropelSchemaFilesForPostgresql();
            $this->getFacade()->adjustCorePostgresqlFunctions();
        }

        return static::CODE_SUCCESS;
    }
}
