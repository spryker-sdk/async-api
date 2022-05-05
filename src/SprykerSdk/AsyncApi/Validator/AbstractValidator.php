<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Validator;

use Generated\Shared\Transfer\ValidateResponseTransfer;
use SprykerSdk\AsyncApi\AsyncApiConfig;
use SprykerSdk\AsyncApi\Validator\Finder\FinderInterface;

abstract class AbstractValidator implements ValidatorInterface
{
    /**
     * @var \SprykerSdk\AsyncApi\AsyncApiConfig
     */
    protected AsyncApiConfig $config;

    /**
     * @var \SprykerSdk\AsyncApi\Validator\Finder\FinderInterface
     */
    protected FinderInterface $finder;

    /**
     * @var array<\SprykerSdk\AsyncApi\Validator\FileValidatorInterface>
     */
    protected array $fileValidators;

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApiConfig $config
     * @param \SprykerSdk\AsyncApi\Validator\Finder\FinderInterface $finder
     * @param array $fileValidators
     */
    public function __construct(AsyncApiConfig $config, FinderInterface $finder, array $fileValidators = [])
    {
        $this->config = $config;
        $this->finder = $finder;
        $this->fileValidators = $fileValidators;
    }

    /**
     * @param array $fileData
     * @param string $fileName
     * @param \Generated\Shared\Transfer\ValidateResponseTransfer $validateResponseTransfer
     * @param array|null $context
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    protected function validateFileData(
        array $fileData,
        string $fileName,
        ValidateResponseTransfer $validateResponseTransfer,
        ?array $context = null
    ): ValidateResponseTransfer {
        foreach ($this->fileValidators as $fileValidator) {
            $validateResponseTransfer = $fileValidator->validate($fileData, $fileName, $validateResponseTransfer, $context);
        }

        return $validateResponseTransfer;
    }
}
