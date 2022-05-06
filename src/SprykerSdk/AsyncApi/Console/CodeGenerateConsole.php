<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Console;

use Generated\Shared\Transfer\AsyncApiRequestTransfer;
use Generated\Shared\Transfer\AsyncApiResponseTransfer;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CodeGenerateConsole extends AbstractConsole
{
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
    public const OPTION_ORGANIZATION = 'organization';

    /**
     * @var string
     */
    public const OPTION_ORGANIZATION_SHORT = 'o';

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->setName('code:asyncapi:generate')
            ->setDescription('Generates code from an AsyncAPI file definition.')
            ->addOption(static::OPTION_ASYNC_API_FILE, static::OPTION_ASYNC_API_FILE_SHORT, InputOption::VALUE_REQUIRED, '', $this->getConfig()->getDefaultAsyncApiFile())
            ->addOption(static::OPTION_ORGANIZATION, static::OPTION_ORGANIZATION_SHORT, InputOption::VALUE_REQUIRED, 'Namespace that should be used for the code builder. When set to Spryker code will be generated in the core modules.', 'App');
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer
            ->setTargetFile($input->getOption(static::OPTION_ASYNC_API_FILE))
            ->setOrganization($input->getOption(static::OPTION_ORGANIZATION));

        $asyncApiResponseTransfer = $this->getFacade()->buildFromAsyncApi($asyncApiRequestTransfer);

        if ($asyncApiResponseTransfer->getErrors()->count() === 0) {
            $this->printMessages($asyncApiResponseTransfer, $output);

            return static::CODE_SUCCESS;
        }

        $this->printErrors($asyncApiResponseTransfer, $output);

        return static::CODE_ERROR;
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printMessages(AsyncApiResponseTransfer $asyncApiResponseTransfer, OutputInterface $output): void
    {
        if (!$output->isVerbose()) {
            return;
        }

        foreach ($asyncApiResponseTransfer->getMessages() as $message) {
            $output->writeln($message->getMessageOrFail());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printErrors(AsyncApiResponseTransfer $asyncApiResponseTransfer, OutputInterface $output): void
    {
        if (!$output->isVerbose()) {
            return;
        }

        foreach ($asyncApiResponseTransfer->getErrors() as $error) {
            $output->writeln($error->getMessageOrFail());
        }
    }
}
