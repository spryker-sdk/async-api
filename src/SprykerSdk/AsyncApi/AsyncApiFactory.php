<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi;

use SprykerSdk\AsyncApi\AsyncApi\Cli\AsyncApiCli;
use SprykerSdk\AsyncApi\AsyncApi\Cli\AsyncApiCliInterface;
use SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoader;
use SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoaderInterface;
use SprykerSdk\AsyncApi\Code\Builder\AsyncApiBuilder;
use SprykerSdk\AsyncApi\Code\Builder\AsyncApiBuilderInterface;
use SprykerSdk\AsyncApi\Code\Builder\AsyncApiCodeBuilder;
use SprykerSdk\AsyncApi\Code\Builder\AsyncApiCodeBuilderInterface;
use SprykerSdk\AsyncApi\Message\MessageBuilder;
use SprykerSdk\AsyncApi\Message\MessageBuilderInterface;
use SprykerSdk\AsyncApi\Validator\AsyncApiValidator;
use SprykerSdk\AsyncApi\Validator\Rule\AsyncApiChannelValidatorRule;
use SprykerSdk\AsyncApi\Validator\Rule\AsyncApiMessageValidatorRule;
use SprykerSdk\AsyncApi\Validator\Rule\AsyncApiModuleNameValidatorRule;
use SprykerSdk\AsyncApi\Validator\Rule\ValidatorRuleInterface;

class AsyncApiFactory
{
    /**
     * @var \SprykerSdk\AsyncApi\AsyncApiConfig|null
     */
    protected ?AsyncApiConfig $config = null;

    protected function getConfig(): AsyncApiConfig
    {
        if (!$this->config) {
            $this->config = new AsyncApiConfig();
        }

        return $this->config;
    }

    public function createAsyncApiBuilder(): AsyncApiBuilderInterface
    {
        return new AsyncApiBuilder($this->createMessageBuilder());
    }

    public function createAsyncApiCodeBuilder(): AsyncApiCodeBuilderInterface
    {
        return new AsyncApiCodeBuilder($this->getConfig(), $this->createMessageBuilder(), $this->createAsyncApiLoader());
    }

    public function createAsyncApiLoader(): AsyncApiLoaderInterface
    {
        return new AsyncApiLoader();
    }

    public function createAsyncApiValidator(): AsyncApiValidator
    {
        return new AsyncApiValidator(
            $this->getConfig(),
            $this->createMessageBuilder(),
            $this->createAsyncApiCli(),
            $this->getAsyncApiValidatorRules(),
        );
    }

    public function getAsyncApiValidatorRules(): array
    {
        return [
            $this->createAsyncApiMessageValidatorRule(),
            $this->createAsyncApiModuleNameValidatorRule(),
            $this->createAsyncApiChannelValidatorRule(),
        ];
    }

    protected function createAsyncApiMessageValidatorRule(): ValidatorRuleInterface
    {
        return new AsyncApiMessageValidatorRule($this->createMessageBuilder());
    }

    protected function createAsyncApiModuleNameValidatorRule(): ValidatorRuleInterface
    {
        return new AsyncApiModuleNameValidatorRule($this->createMessageBuilder());
    }

    protected function createAsyncApiChannelValidatorRule(): ValidatorRuleInterface
    {
        return new AsyncApiChannelValidatorRule($this->createMessageBuilder());
    }

    public function createMessageBuilder(): MessageBuilderInterface
    {
        return new MessageBuilder();
    }

    public function createAsyncApiCli(): AsyncApiCliInterface
    {
        return new AsyncApiCli($this->getConfig(), $this->createMessageBuilder());
    }
}
