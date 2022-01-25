<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Twig\TwigExtensionInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Twig\Environment;

/**
 * @method \Spryker\Zed\Twig\Communication\TwigCommunicationFactory getFactory()
 * @method \Spryker\Zed\Twig\Business\TwigFacadeInterface getFacade()
 */
abstract class AbstractTwigExtensionPlugin extends AbstractPlugin implements TwigPluginInterface, TwigExtensionInterface
{
    /**
     * {@inheritDoc}
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
        $twig->addExtension($this);

        return $twig;
    }

    /**
     * {@inheritDoc}
     * Initializes the runtime environment.
     *
     * This is where you can load some file that contains filter functions for instance.
     *
     * @api
     *
     * @param \Twig\Environment $environment The current Environment instance
     *
     * @return void
     */
    public function initRuntime(Environment $environment)
    {
    }

    /**
     * {@inheritDoc}
     * Returns the token parser instances to add to the existing list.
     *
     * @api
     *
     * @return \Twig\TokenParser\TokenParserInterface[] An array of TokenParserInterface or TokenParserBrokerInterface instances
     */
    public function getTokenParsers()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     * Returns the node visitor instances to add to the existing list.
     *
     * @api
     *
     * @return \Twig\NodeVisitor\NodeVisitorInterface[] An array of NodeVisitorInterface instances
     */
    public function getNodeVisitors()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     * Returns a list of filters to add to the existing list.
     *
     * @api
     *
     * @return \Twig\TwigFilter[] An array of filters
     */
    public function getFilters()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     * Returns a list of tests to add to the existing list.
     *
     * @api
     *
     * @return \Twig\TwigTest[] An array of tests
     */
    public function getTests()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     * Returns a list of functions to add to the existing list.
     *
     * @api
     *
     * @return \Twig\TwigFunction[] An array of functions
     */
    public function getFunctions()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     * Returns a list of operators to add to the existing list.
     *
     * @api
     *
     * @return array An array of operators
     */
    public function getOperators()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     * Returns a list of global variables to add to the existing list.
     *
     * @api
     *
     * @return array An array of global variables
     */
    public function getGlobals()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated since 1.26 (to be removed in 2.0), not used anymore internally
     *
     * @return string
     */
    public function getName()
    {
        return static::class;
    }
}
