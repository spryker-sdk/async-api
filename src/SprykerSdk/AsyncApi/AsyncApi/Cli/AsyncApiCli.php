<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\AsyncApi\Cli;

use Exception;
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
    public const ASYNCAPI_CLI_COMMAND = 'asyncapi';

    /**
     * @var string
     */
    public const ASYNCAPI_CLI_VALIDATE = 'validate';

    /**
     * @var string
     */
    public const ASYNCAPI_CLI_VERSION = '--version';

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
     * @return bool
     */
    public function isCliInstalled(): bool
    {
        try {
            $process = new Process([static::ASYNCAPI_CLI_COMMAND, static::ASYNCAPI_CLI_VERSION], $this->config->getProjectRootPath());
            $process->run(function ($type, $buffer): void {
                echo $buffer;
            });
        } catch (Exception $e) {
            return false;
        }

        return $process->isSuccessful();
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

        try {
            $process = new Process([static::ASYNCAPI_CLI_COMMAND, static::ASYNCAPI_CLI_VALIDATE, $asyncApiFilePath], $this->config->getProjectRootPath());
            $process->run(function ($type, $buffer): void {
                echo $buffer;
            });
        } catch (Exception $e) {
            $validateResponseTransfer->addError($this->messageBuilder->buildMessage(
                AsyncApiError::asyncApiCliValidationFailed($asyncApiFilePath, $e->getMessage()),
            ));

            return $validateResponseTransfer;
        }

        if (!$process->isSuccessful()) {
            $validateResponseTransfer->addError($this->messageBuilder->buildMessage(
                AsyncApiError::asyncApiCliValidationFailed($asyncApiFilePath, $process->getErrorOutput()),
            ));
        }

        return $validateResponseTransfer;
    }
}
