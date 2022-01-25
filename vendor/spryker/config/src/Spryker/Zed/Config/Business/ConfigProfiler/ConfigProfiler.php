<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Config\Business\ConfigProfiler;

use Spryker\Shared\Config\Config;

class ConfigProfiler implements ConfigProfilerInterface
{
    /**
     * @return array
     */
    public function getProfileData(): array
    {
        return Config::getProfileData();
    }
}
