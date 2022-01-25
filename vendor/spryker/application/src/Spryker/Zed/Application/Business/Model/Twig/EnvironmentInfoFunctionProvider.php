<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business\Model\Twig;

use Spryker\Service\UtilNetwork\Model\Host;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Twig\TwigFunctionProvider;

/**
 * @deprecated Will be removed without replacement in the next major.
 * If you use `environmentInfo` function in your twig files, please add it on your own.
 */
class EnvironmentInfoFunctionProvider extends TwigFunctionProvider
{
    /**
     * @return string
     */
    public function getFunctionName()
    {
        return 'environmentInfo';
    }

    /**
     * @return callable
     */
    public function getFunction()
    {
        return function ($currentController) {
            $utilNetworkHost = new Host();
            $html = '<div class="zed:header__environment"><i class="icon-cogs"></i>'
                . '<span>' . APPLICATION_ENV . '</span>'
                . '<dl>'
                . '<dt>Locale:'
                . '<dd>' . Store::getInstance()->getCurrentLocale()
                . '<dt>Store:'
                . '<dd>' . Store::getInstance()->getStoreName()
                . '<dt>Code Bucket:'
                . '<dd>' . APPLICATION_CODE_BUCKET
                . '<dt>Server:'
                . '<dd>' . $utilNetworkHost->getHostname()
                . '<dt>Controller:'
                . '<dd>' . $currentController
                . '</dl></div>';

            return $html;
        };
    }
}
