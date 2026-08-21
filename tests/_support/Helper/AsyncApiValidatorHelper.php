<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;

class AsyncApiValidatorHelper extends Module
{
    use AsyncApiHelperTrait;

    /**
     * @var string
     */
    protected const ASYNC_API_FILE_NAME = 'asyncapi.yml';

    public function haveValidAsyncApiFile(): void
    {
        $files = [
            static::ASYNC_API_FILE_NAME => file_get_contents(codecept_data_dir('api/valid/base_asyncapi.schema.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    public function haveDefaultCreatedAsyncApiFile(): void
    {
        $files = [
            static::ASYNC_API_FILE_NAME => file_get_contents(codecept_data_dir('api/builder/asyncapi-empty.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    public function haveAsyncApiFileSyntaxError(): void
    {
        $files = [
            static::ASYNC_API_FILE_NAME => file_get_contents(codecept_data_dir('api/invalid/syntax_error_asyncapi.schema.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    public function haveAsyncApiFileWithMissingRequiredFields(): void
    {
        $files = [
            static::ASYNC_API_FILE_NAME => file_get_contents(codecept_data_dir('api/invalid/asyncapi-without-spryker-extension.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    public function haveAsyncApiFileWithDuplicatedMessageNames(): void
    {
        $files = [
            static::ASYNC_API_FILE_NAME => file_get_contents(codecept_data_dir('api/invalid/asyncapi-duplicated-message-names.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    /**
     * @param array $files
     */
    protected function prepareAsyncApiSchema(array $files): void
    {
        $this->getAsyncApiHelper()->mockDirectoryStructure(
            $this->buildStructureByPath($this->getOpenApiSchemaPath(), $files),
        );
    }

    protected function getOpenApiSchemaPath(): string
    {
        return 'resources/api';
    }

    /**
     * @param string $path
     * @param array $files
     */
    protected function buildStructureByPath(string $path, array $files): array
    {
        $pathFragments = explode('/', trim($path, '/'));

        $structure = [];
        $current = &$structure;
        foreach ($pathFragments as $fragment) {
            $current[$fragment] = [];
            $current = &$current[$fragment];
        }
        $current = $files;

        return $structure;
    }

    public function getDefaultAsyncApiFilePath(): string
    {
        return sprintf(
            '%s/%s/%s',
            $this->getAsyncApiHelper()->getRootPath(),
            $this->getOpenApiSchemaPath(),
            static::ASYNC_API_FILE_NAME,
        );
    }
}
