<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\AsyncApi\Cli;

use SprykerSdk\AsyncApi\AsyncApiConfig;
use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdk\AsyncApi\Message\AsyncApiInfo;
use SprykerSdk\AsyncApi\Message\MessageBuilderInterface;
use Symfony\Component\Process\Process;
use Transfer\ValidateResponseTransfer;

class AsyncApiCli implements AsyncApiCliInterface
{
    /**
     * @var string
     */
    protected const ASYNCAPI_CLI = 'asyncapi';

    /**
     * @var string
     */
    protected const ASYNCAPI_CLI_VALIDATE_COMMAND = 'validate';

    /**
     * @var string
     */
    protected const ASYNCAPI_CLI_VERSION = '--version';

    /**
     * @var \SprykerSdk\AsyncApi\AsyncApiConfig
     */
    protected AsyncApiConfig $config;

    /**
     * @var \SprykerSdk\AsyncApi\Message\MessageBuilderInterface
     */
    protected MessageBuilderInterface $messageBuilder;

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApiConfig $config
     * @param \SprykerSdk\AsyncApi\Message\MessageBuilderInterface $messageBuilder
     */
    public function __construct(AsyncApiConfig $config, MessageBuilderInterface $messageBuilder)
    {
        $this->config = $config;
        $this->messageBuilder = $messageBuilder;
    }

    /**
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     * @param string $asyncApiFilePath
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validate(ValidateResponseTransfer $validateResponseTransfer, string $asyncApiFilePath): ValidateResponseTransfer
    {
        if (!$this->isCliInstalled()) {
            $validateResponseTransfer->addMessage($this->messageBuilder->buildMessage(
                AsyncApiInfo::asyncApiCliNotFound(),
            ));

            return $validateResponseTransfer;
        }

        if (!$this->runProcess([static::ASYNCAPI_CLI, static::ASYNCAPI_CLI_VALIDATE_COMMAND, $asyncApiFilePath])) {
            $validateResponseTransfer->addError($this->messageBuilder->buildMessage(
                AsyncApiError::asyncApiCliValidationFailed($asyncApiFilePath),
            ));

            return $validateResponseTransfer;
        }

        return $validateResponseTransfer;
    }

    /**
     * @return bool
     */
    protected function isCliInstalled(): bool
    {
        if (!$this->runProcess([static::ASYNCAPI_CLI, static::ASYNCAPI_CLI_VERSION])) {
            $this->triggerError('The AsyncAPI validator must be installed starting from the next major version 1.0.0. Not having the validator installed will mark the validation failed.');

            return false;
        }

        return true;
    }

    /**
     * @param array $command
     *
     * @return bool
     */
    protected function runProcess(array $command): bool
    {
        $process = new Process($command, $this->config->getProjectRootPath());
        $process->run(function ($type, $buffer): void {
            echo $buffer;
        });

        return $process->isSuccessful();
    }

    /**
     * @param string $errorMessage
     *
     * @return void
     */
    protected function triggerError(string $errorMessage): void
    {
        trigger_error($errorMessage, E_USER_DEPRECATED);
    }
}
