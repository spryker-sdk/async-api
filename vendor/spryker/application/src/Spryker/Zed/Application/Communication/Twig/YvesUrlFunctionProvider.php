<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Twig;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Zed\Application\ApplicationConfig;

class YvesUrlFunctionProvider extends TwigFunctionProvider
{
    /**
     * @var \Spryker\Zed\Application\ApplicationConfig
     */
    protected $applicationConfig;

    /**
     * @param \Spryker\Zed\Application\ApplicationConfig $applicationConfig
     */
    public function __construct(ApplicationConfig $applicationConfig)
    {
        $this->applicationConfig = $applicationConfig;
    }

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return 'yves_url';
    }

    /**
     * @return callable
     */
    public function getFunction()
    {
        return function ($url, array $query = [], array $options = []) {
            $url = Url::generate($url, $query, $this->formatOptions($options));

            return $url->buildEscaped();
        };
    }

    /**
     * @param array $options
     *
     * @return array
     */
    protected function formatOptions(array $options): array
    {
        $options[Url::HOST] = isset($options[Url::HOST]) ? $options[Url::HOST] : $this->getYvesHttpHost();

        return $options;
    }

    /**
     * @return string
     */
    protected function getYvesHttpHost(): string
    {
        return rtrim($this->applicationConfig->getYvesHostName(), '/');
    }
}
