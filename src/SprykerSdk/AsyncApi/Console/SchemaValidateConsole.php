<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Transfer\ValidateRequestTransfer;

class SchemaValidateConsole extends AbstractConsole
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
        $this->setName('schema:asyncapi:validate')
            ->setDescription('Validates an AsyncAPI file.')
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
            $this->printMessages($output, $validateResponseTransfer->getMessages());

            return static::CODE_SUCCESS;
        }

        $this->printMessages($output, $validateResponseTransfer->getErrors());

        return static::CODE_ERROR;
    }
}
