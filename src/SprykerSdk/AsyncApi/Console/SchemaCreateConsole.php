<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Console;

use Generated\Shared\Transfer\AsyncApiRequestTransfer;
use Generated\Shared\Transfer\AsyncApiTransfer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SchemaCreateConsole extends AbstractConsole
{
    /**
     * @var string
     */
    public const ARGUMENT_TITLE = 'title';

    /**
     * @var string
     */
    public const OPTION_ASYNC_API_FILE = 'asyncapi-file';

    /**
     * @var string
     */
    public const OPTION_ASYNC_API_FILE_SHORT = 'a';

    /**
     * @var string
     */
    public const OPTION_API_VERSION = 'api-version';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('schema:create')
            ->setDescription('Creates an AsyncAPI file in the specified Async API schema file path.')
            ->addArgument(static::ARGUMENT_TITLE, InputArgument::REQUIRED, 'The name of the App.')
            ->addOption(static::OPTION_ASYNC_API_FILE, static::OPTION_ASYNC_API_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultAsyncApiFile())
            ->addOption(static::OPTION_API_VERSION, null, InputOption::VALUE_REQUIRED, 'Version number of the AsyncAPI schema. Defaults to 0.1.0', '0.1.0');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $asyncApiTransfer = new AsyncApiTransfer();
        $asyncApiTransfer
            ->setTitle($input->getArgument(static::ARGUMENT_TITLE))
            ->setVersion($input->getOption(static::OPTION_API_VERSION));

        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer
            ->setTargetFile($input->getOption(static::OPTION_ASYNC_API_FILE))
            ->setAsyncApi($asyncApiTransfer);

        $asyncApiResponseTransfer = $this->getFacade()->addAsyncApi($asyncApiRequestTransfer);

        if ($asyncApiResponseTransfer->getErrors()->count() === 0) {
            return static::CODE_SUCCESS;
        }

        // @codeCoverageIgnoreStart
        if ($output->isVerbose()) {
            foreach ($asyncApiResponseTransfer->getErrors() as $error) {
                $output->writeln($error->getMessageOrFail());
            }
        }

        return static::CODE_ERROR;
        // @codeCoverageIgnoreEnd
    }
}
