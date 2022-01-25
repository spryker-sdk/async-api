<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log\Communication\Console;

use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Log\Business\LogFacadeInterface getFacade()
 * @method \Spryker\Zed\Log\Communication\LogCommunicationFactory getFactory()
 */
class DeleteLogFilesConsole extends Console
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'log:clear';

    /**
     * @var string
     */
    public const DESCRIPTION = 'This command will clear all logs.';

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME);
        $this->setDescription(static::DESCRIPTION);

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
        $this->info('Clear logs');
        $this->getFacade()->stopListener();
        $this->getFacade()->clearLogs();
        $this->getFacade()->startListener();

        return static::CODE_SUCCESS;
    }
}
