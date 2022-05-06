<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

trait AsyncApiHelperTrait
{
    /**
     * @return \SprykerSdkTest\Helper\AsyncApiHelper
     */
    protected function getAsyncApiHelper(): AsyncApiHelper
    {
        /** @var \SprykerSdkTest\Helper\AsyncApiHelper $asyncApiHelper */
        $asyncApiHelper = $this->getModule('\\' . AsyncApiHelper::class);

        return $asyncApiHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule($name);
}
