<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Transfer\ValidateRequestTransfer;
use Transfer\ValidateResponseTransfer;

class ValidatorHelper extends Module
{
    use AsyncApiHelperTrait;

    /**
     * @return void
     */
    public function haveValidConfigurations(): void
    {
        $structure = $this->getValidBaseStructure();

        $this->getAsyncApiHelper()->mockDirectoryStructure($structure);
    }

    /**
     * @return array<array<array<\array>>>
     */
    protected function getValidBaseStructure(): array
    {
        return [
            'resources' => [
                'api' => [
                    'asyncapi.yml' => file_get_contents(codecept_data_dir('api/valid/base_asyncapi.schema.yml')),
                ],
            ],
        ];
    }

    /**
     * @return \Transfer\ValidateRequestTransfer
     */
    public function haveValidateRequest(): ValidateRequestTransfer
    {
        $config = $this->getAsyncApiHelper()->getConfig();

        $validateRequest = new ValidateRequestTransfer();
        $validateRequest->setAsyncApiFile($config->getDefaultAsyncApiFile());

        return $validateRequest;
    }

    /**
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     *
     * @return array
     */
    public function getMessagesFromValidateResponseTransfer(ValidateResponseTransfer $validateResponseTransfer): array
    {
        $messages = [];

        foreach ($validateResponseTransfer->getErrors() as $messageTransfer) {
            $messages[] = $messageTransfer->getMessage();
        }

        return $messages;
    }
}
