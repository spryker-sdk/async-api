<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\AsyncApi\Loader;

use SprykerSdk\AsyncApi\AsyncApi\AsyncApiInterface;

interface AsyncApiLoaderInterface
{
    /**
     * @param string $asyncApiPath
     *
     * @return \SprykerSdk\AsyncApi\AsyncApi\AsyncApiInterface
     */
    public function load(string $asyncApiPath): AsyncApiInterface;
}
