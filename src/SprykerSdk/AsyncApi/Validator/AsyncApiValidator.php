<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Validator;

use Exception;
use SprykerSdk\AsyncApi\AsyncApi\Cli\AsyncApiCliInterface;
use SprykerSdk\AsyncApi\AsyncApiConfig;
use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdk\AsyncApi\Message\AsyncApiInfo;
use SprykerSdk\AsyncApi\Message\MessageBuilderInterface;
use Symfony\Component\Yaml\Yaml;
use Transfer\ValidateRequestTransfer;
use Transfer\ValidateResponseTransfer;

class AsyncApiValidator implements ValidatorInterface
{
    /**
     * @var \SprykerSdk\AsyncApi\AsyncApiConfig
     */
    protected AsyncApiConfig $config;

    /**
     * @var \SprykerSdk\AsyncApi\Message\MessageBuilderInterface
     */
    protected MessageBuilderInterface $messageBuilder;

    /**
     * @var array<\SprykerSdk\AsyncApi\Validator\Rule\ValidatorRuleInterface>
     */
    protected array $validatorRules;

    /**
     * @var \SprykerSdk\AsyncApi\AsyncApi\Cli\AsyncApiCliInterface
     */
    protected AsyncApiCliInterface $asyncApiCli;

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApiConfig $config
     * @param \SprykerSdk\AsyncApi\Message\MessageBuilderInterface $messageBuilder
     * @param \SprykerSdk\AsyncApi\AsyncApi\Cli\AsyncApiCliInterface $asyncApiCli
     * @param array<\SprykerSdk\AsyncApi\Validator\Rule\ValidatorRuleInterface> $validatorRules
     */
    public function __construct(AsyncApiConfig $config, MessageBuilderInterface $messageBuilder, AsyncApiCliInterface $asyncApiCli, array $validatorRules = [])
    {
        $this->config = $config;
        $this->messageBuilder = $messageBuilder;
        $this->validatorRules = $validatorRules;
        $this->asyncApiCli = $asyncApiCli;
    }

    /**
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     * @param \Transfer\ValidateResponseTransfer|null $validateResponseTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validate(
        ValidateRequestTransfer $validateRequestTransfer,
        ?ValidateResponseTransfer $validateResponseTransfer = null
    ): ValidateResponseTransfer {
        $validateResponseTransfer ??= new ValidateResponseTransfer();
        $asyncApiFile = $validateRequestTransfer->getAsyncApiFileOrFail();

        if (!is_file($asyncApiFile)) {
            $validateResponseTransfer->addError($this->messageBuilder->buildMessage(
                AsyncApiError::asyncApiFileDoesNotExist($asyncApiFile),
            ));

            return $validateResponseTransfer;
        }

        try {
            $asyncApi = Yaml::parseFile($asyncApiFile);
        } catch (Exception $e) {
            $validateResponseTransfer->addError($this->messageBuilder->buildMessage(
                AsyncApiError::couldNotParseAsyncApiFile($asyncApiFile, $e->getMessage()),
            ));

            return $validateResponseTransfer;
        }

        $validateResponseTransfer = $this->executeValidatorRules($asyncApi, $asyncApiFile, $validateResponseTransfer);

        $validateResponseTransfer = $this->asyncApiCli->validate($validateResponseTransfer, $asyncApiFile);

        if ($validateResponseTransfer->getErrors()->count() === 0) {
            $validateResponseTransfer->addMessage($this->messageBuilder->buildMessage(AsyncApiInfo::asyncApiSchemaFileIsValid()));
        }

        return $validateResponseTransfer;
    }

    /**
     * @param array $fileData
     * @param string $fileName
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     * @param array|null $context
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    protected function executeValidatorRules(
        array $fileData,
        string $fileName,
        ValidateResponseTransfer $validateResponseTransfer,
        ?array $context = null
    ): ValidateResponseTransfer {
        foreach ($this->validatorRules as $fileValidator) {
            $validateResponseTransfer = $fileValidator->validate($fileData, $fileName, $validateResponseTransfer, $context);
        }

        return $validateResponseTransfer;
    }
}
