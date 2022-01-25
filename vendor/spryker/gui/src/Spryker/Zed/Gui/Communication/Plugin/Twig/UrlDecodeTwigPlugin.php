<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class UrlDecodeTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @var string
     */
    public const FUNCTION_NAME_URL_DECODE = 'urldecode';

    /**
     * {@inheritDoc}
     * - Extends twig with "urldecode" function to encode the url.
     * - The function returns a string which consist all non-alphanumeric characters except -_. and replace by the percent (%) sign followed by two hex digits and spaces encoded as plus (+) signs.
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction($this->getUrlDecodeFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getUrlDecodeFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_URL_DECODE, function (string $url) {
            return urldecode($url);
        }, ['is_safe' => ['html']]);
    }
}
