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

    /**
     * @return void
     */
    public function haveValidAsyncApiFile(): void
    {
        $files = [
            static::ASYNC_API_FILE_NAME => file_get_contents(codecept_data_dir('api/valid/base_asyncapi.schema.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    /**
     * @return void
     */
    public function haveDefaultCreatedAsyncApiFile(): void
    {
        $files = [
            static::ASYNC_API_FILE_NAME => file_get_contents(codecept_data_dir('api/builder/asyncapi-empty.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    /**
     * @return void
     */
    public function haveAsyncApiFileSyntaxError(): void
    {
        $files = [
            static::ASYNC_API_FILE_NAME => file_get_contents(codecept_data_dir('api/invalid/syntax_error_asyncapi.schema.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    /**
     * @return void
     */
    public function haveAsyncApiFileWithMissingRequiredFields(): void
    {
        $files = [
            static::ASYNC_API_FILE_NAME => file_get_contents(codecept_data_dir('api/invalid/asyncapi-without-spryker-extension.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    /**
     * @return void
     */
    public function haveAsyncApiFileWithDuplicatedMessageNames(): void
    {
        $files = [
            static::ASYNC_API_FILE_NAME => file_get_contents(codecept_data_dir('api/invalid/asyncapi-duplicated-message-names.yml')),
        ];

        $this->prepareAsyncApiSchema($files);
    }

    /**
     * @param array $files
     *
     * @return void
     */
    protected function prepareAsyncApiSchema(array $files): void
    {
        $this->getAsyncApiHelper()->mockDirectoryStructure(
            $this->buildStructureByPath($this->getOpenApiSchemaPath(), $files),
        );
    }

    /**
     * @return string
     */
    protected function getOpenApiSchemaPath(): string
    {
        return 'resources/api';
    }

    /**
     * @param string $path
     * @param array $files
     *
     * @return array
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

    /**
     * @return string
     */
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
