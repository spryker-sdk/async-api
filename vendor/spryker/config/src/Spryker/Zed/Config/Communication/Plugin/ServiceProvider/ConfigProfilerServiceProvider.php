<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Config\Communication\Plugin\ServiceProvider;

use Spryker\Shared\Config\Plugin\ServiceProvider\AbstractConfigProfilerServiceProvider as SharedConfigProfilerServiceProvider;

/**
 * @deprecated Use {@link \Spryker\Zed\Config\Communication\Plugin\WebProfiler\WebProfilerConfigDataCollector} instead.
 */
class ConfigProfilerServiceProvider extends SharedConfigProfilerServiceProvider
{
    /**
     * @return string
     */
    protected function getTemplateName()
    {
        return '@Config/Collector/spryker_config_profiler.html.twig';
    }

    /**
     * @return bool|string
     */
    protected function getPathToTemplates()
    {
        return realpath(dirname(__DIR__) . '/../../Presentation/Collector');
    }
}
