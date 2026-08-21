<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi;

class AsyncApiConfig
{
    /**
     * @api
     *
     * @throws \SprykerSdk\AsyncApi\Exception\AsyncApiException
     */
    public function getDefaultAsyncApiFile(): string
    {
        $pathFragments = [
            $this->getProjectRootPath(),
            'resources',
            'api',
            'asyncapi.yml',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathFragments);
    }

    /**
     * @api
     */
    public function getProjectRootPath(): string
    {
        return (string)getcwd();
    }

    /**
     * Returns the current working directory or `INSTALLED_ROOT_DIRECTORY` (when INSTALLED_ROOT_DIRECTORY is defined).
     * This is needed to be able to execute this tool within the SprykerSdk and not inside of a project directly.
     */
    public function getSprykRunExecutablePath(): string
    {
        $installedRootDirectory = getenv('INSTALLED_ROOT_DIRECTORY');

        if ($installedRootDirectory) {
            return $installedRootDirectory;
        }

        return (string)getcwd();
    }
}
