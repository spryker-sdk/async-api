<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Console;

use Generated\Shared\Transfer\ValidateRequestTransfer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AsyncApiValidateConsole extends AbstractConsole
{
    /**
     * @var string
     */
    public const ASYNC_API_FILE = 'asyncapi-file';

    /**
     * @var string
     */
    public const ASYNC_API_FILE_SHORT = 'a';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('asyncapi:validate')
            ->setDescription('Validates the asyncapi files.')
            ->addOption(static::ASYNC_API_FILE, static::ASYNC_API_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultAsyncApiFile());
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $validateRequestTransfer = new ValidateRequestTransfer();
        $validateRequestTransfer->setAsyncApiFile($input->getOption(static::ASYNC_API_FILE));

        $validateResponseTransfer = $this->getFacade()->validateAsyncApi($validateRequestTransfer);

        if ($validateResponseTransfer->getErrors()->count() === 0) {
            return static::CODE_SUCCESS;
        }

        if ($output->isVerbose()) {
            foreach ($validateResponseTransfer->getErrors() as $error) {
                $output->writeln($error->getMessageOrFail());
            }
        }

        return static::CODE_ERROR;
    }
}
