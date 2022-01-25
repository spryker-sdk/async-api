<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Gui\Communication\Form\Type\Extension\NoValidateTypeExtension;
use Spryker\Zed\Gui\GuiDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;

/**
 * @deprecated Use {@link \Spryker\Zed\Gui\Communication\Plugin\Twig\GuiFilterTwigPlugin} instead.
 *
 * @deprecated Use {@link \Spryker\Zed\Gui\Communication\Plugin\Twig\GuiTwigPlugin} instead.
 *
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 */
class GuiTwigExtensionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $this->provideFormTypeExtension($app);

        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) {
                $this->registerTwigFunctions($twig);
                $this->registerTwigFilters($twig);

                return $twig;
            }),
        );
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return void
     */
    protected function registerTwigFunctions(Environment $twig)
    {
        foreach ($this->getTwigFunctions() as $function) {
            $twig->addFunction($function);
        }
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return void
     */
    protected function registerTwigFilters(Environment $twig)
    {
        foreach ($this->getTwigFilters() as $filter) {
            $twig->addFilter($filter);
        }
    }

    /**
     * @return array<\Twig\TwigFunction>
     */
    protected function getTwigFunctions()
    {
        return $this->getFactory()
            ->getProvidedDependency(GuiDependencyProvider::GUI_TWIG_FUNCTIONS);
    }

    /**
     * @return array<\Twig\TwigFilter>
     */
    protected function getTwigFilters()
    {
        return $this->getFactory()
            ->getProvidedDependency(GuiDependencyProvider::GUI_TWIG_FILTERS);
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function provideFormTypeExtension(Application $app)
    {
        $app['form.type.extensions'] = $app->share(function () {
            return [
                new NoValidateTypeExtension(),
            ];
        });
    }
}
