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
     * @codeCoverageIgnore
     *
     * @return string
     */
    protected function getRootPath(): string
    {
        return ASYNC_API_ROOT_DIR;
    }
}
