<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi;

use SprykerSdk\AsyncApi\Exception\AsyncApiException;

class AsyncApiConfig
{
    /**
     * @api
     *
     * @throws \SprykerSdk\AsyncApi\Exception\AsyncApiException
     *
     * @return string
     */
    public function getDefaultAsyncApiFile(): string
    {
        $pathFragments = [
            $this->getProjectRootPath(),
            'config',
            'api',
            'asyncapi',
            'asyncapi.yml',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathFragments);
    }

    /**
     * @api
     *
     * @throws \SprykerSdk\AsyncApi\Exception\AsyncApiException
     *
     * @return string
     */
    public function getProjectRootPath(): string
    {
        $cwd = getcwd();

        // @codeCoverageIgnoreStart
        if (!$cwd) {
            throw new AsyncApiException('Could not get the current working directory.');
        }
        // @codeCoverageIgnoreEnd

        return $cwd;
    }

    /**
     * Returns `vendor/bin/` (default) or `INSTALLED_ROOT_DIRECTORY/vendor/bin/` (when INSTALLED_ROOT_DIRECTORY is defined).
     *
     * @return string
     */
    public function getSprykRunExecutablePath(): string
    {
        $pathFragments = [
            $this->getInstalledRootDirectory(),
            'vendor',
            'bin',
        ];

        return implode(DIRECTORY_SEPARATOR, $pathFragments) . DIRECTORY_SEPARATOR;
    }

    /**
     * Returns the current working directory or `INSTALLED_ROOT_DIRECTORY` (when INSTALLED_ROOT_DIRECTORY is defined).
     * This is needed to be able to execute this tool within the SprykerSdk and not inside of a project directly.
     *
     * @return string
     */
    protected function getInstalledRootDirectory(): string
    {
        if (getenv('INSTALLED_ROOT_DIRECTORY')) {
            return getenv('INSTALLED_ROOT_DIRECTORY');
        }

        return (string)getcwd();
    }
}
