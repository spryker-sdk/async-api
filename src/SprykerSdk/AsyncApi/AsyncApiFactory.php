<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi;

use SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoader;
use SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoaderInterface;
use SprykerSdk\AsyncApi\Code\Builder\AsyncApiBuilder;
use SprykerSdk\AsyncApi\Code\Builder\AsyncApiBuilderInterface;
use SprykerSdk\AsyncApi\Code\Builder\AsyncApiCodeBuilder;
use SprykerSdk\AsyncApi\Code\Builder\AsyncApiCodeBuilderInterface;
use SprykerSdk\AsyncApi\Validator\AsyncApiValidator;
use SprykerSdk\AsyncApi\Validator\FileValidatorInterface;
use SprykerSdk\AsyncApi\Validator\Finder\Finder;
use SprykerSdk\AsyncApi\Validator\Finder\FinderInterface;
use SprykerSdk\AsyncApi\Validator\Validator\AsyncApiChannelValidator;
use SprykerSdk\AsyncApi\Validator\Validator\AsyncApiMessageValidator;
use SprykerSdk\AsyncApi\Validator\Validator\AsyncApiOperationIdValidator;

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
     * @return \SprykerSdk\AsyncApi\Validator\Finder\FinderInterface
     */
    protected function createFinder(): FinderInterface
    {
        return new Finder();
    }

    /**
     * @return \SprykerSdk\AsyncApi\Code\Builder\AsyncApiBuilderInterface
     */
    public function createAsyncApiBuilder(): AsyncApiBuilderInterface
    {
        return new AsyncApiBuilder();
    }

    /**
     * @return \SprykerSdk\AsyncApi\Code\Builder\AsyncApiCodeBuilderInterface
     */
    public function createAsyncApiCodeBuilder(): AsyncApiCodeBuilderInterface
    {
        return new AsyncApiCodeBuilder($this->getConfig(), $this->createAsyncApiLoader());
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
            $this->createFinder(),
            $this->getAsyncApiValidators(),
        );
    }

    /**
     * @return array
     */
    public function getAsyncApiValidators(): array
    {
        return [
            $this->createAsyncApiMessageValidator(),
            $this->createAsyncApiOperationIdValidator(),
            $this->createAsyncApiChannelValidator(),
        ];
    }

    /**
     * @return \SprykerSdk\AsyncApi\Validator\FileValidatorInterface
     */
    protected function createAsyncApiMessageValidator(): FileValidatorInterface
    {
        return new AsyncApiMessageValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\AsyncApi\Validator\FileValidatorInterface
     */
    protected function createAsyncApiOperationIdValidator(): FileValidatorInterface
    {
        return new AsyncApiOperationIdValidator($this->getConfig());
    }

    /**
     * @return \SprykerSdk\AsyncApi\Validator\FileValidatorInterface
     */
    protected function createAsyncApiChannelValidator(): FileValidatorInterface
    {
        return new AsyncApiChannelValidator($this->getConfig());
    }
}
