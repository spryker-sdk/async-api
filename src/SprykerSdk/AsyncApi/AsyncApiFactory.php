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

    /**
     * @return \SprykerSdk\AsyncApi\AsyncApiConfig
     */
    protected function getConfig(): AsyncApiConfig
    {
        if (!$this->config) {
            $this->config = new AsyncApiConfig();
        }

        return $this->config;
    }

    /**
     * @return \SprykerSdk\AsyncApi\Code\Builder\AsyncApiBuilderInterface
     */
    public function createAsyncApiBuilder(): AsyncApiBuilderInterface
    {
        return new AsyncApiBuilder($this->createMessageBuilder());
    }

    /**
     * @return \SprykerSdk\AsyncApi\Code\Builder\AsyncApiCodeBuilderInterface
     */
    public function createAsyncApiCodeBuilder(): AsyncApiCodeBuilderInterface
    {
        return new AsyncApiCodeBuilder($this->getConfig(), $this->createMessageBuilder(), $this->createAsyncApiLoader());
    }

    /**
     * @return \SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoaderInterface
     */
    public function createAsyncApiLoader(): AsyncApiLoaderInterface
    {
        return new AsyncApiLoader();
    }

    /**
     * @return \SprykerSdk\AsyncApi\Validator\AsyncApiValidator
     */
    public function createAsyncApiValidator(): AsyncApiValidator
    {
        return new AsyncApiValidator(
            $this->getConfig(),
            $this->createMessageBuilder(),
            $this->createAsyncApiCli(),
            $this->getAsyncApiValidatorRules(),
        );
    }

    /**
     * @return array
     */
    public function getAsyncApiValidatorRules(): array
    {
        return [
            $this->createAsyncApiMessageValidatorRule(),
            $this->createAsyncApiModuleNameValidatorRule(),
            $this->createAsyncApiChannelValidatorRule(),
        ];
    }

    /**
     * @return \SprykerSdk\AsyncApi\Validator\Rule\ValidatorRuleInterface
     */
    protected function createAsyncApiMessageValidatorRule(): ValidatorRuleInterface
    {
        return new AsyncApiMessageValidatorRule($this->createMessageBuilder());
    }

    /**
     * @return \SprykerSdk\AsyncApi\Validator\Rule\ValidatorRuleInterface
     */
    protected function createAsyncApiModuleNameValidatorRule(): ValidatorRuleInterface
    {
        return new AsyncApiModuleNameValidatorRule($this->createMessageBuilder());
    }

    /**
     * @return \SprykerSdk\AsyncApi\Validator\Rule\ValidatorRuleInterface
     */
    protected function createAsyncApiChannelValidatorRule(): ValidatorRuleInterface
    {
        return new AsyncApiChannelValidatorRule($this->createMessageBuilder());
    }

    /**
     * @return \SprykerSdk\AsyncApi\Message\MessageBuilderInterface
     */
    public function createMessageBuilder(): MessageBuilderInterface
    {
        return new MessageBuilder();
    }

    /**
     * @return \SprykerSdk\AsyncApi\AsyncApi\Cli\AsyncApiCliInterface
     */
    public function createAsyncApiCli(): AsyncApiCliInterface
    {
        return new AsyncApiCli($this->getConfig(), $this->createMessageBuilder());
    }
}
