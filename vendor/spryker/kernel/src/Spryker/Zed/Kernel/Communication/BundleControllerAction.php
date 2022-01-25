<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Kernel\Communication;

use Laminas\Filter\Word\DashToCamelCase;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;

class BundleControllerAction implements BundleControllerActionInterface
{
    /**
     * @var string
     */
    protected $bundle;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string
     */
    protected $action;

    /**
     * @var \Laminas\Filter\Word\DashToCamelCase
     */
    protected $filter;

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     */
    public function __construct($bundle, $controller, $action)
    {
        $this->bundle = $bundle;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function filter($value)
    {
        /** @var string $value */
        $value = $this->getFilter()->filter($value);

        return lcfirst($value);
    }

    /**
     * @return \Laminas\Filter\Word\DashToCamelCase
     */
    private function getFilter()
    {
        if ($this->filter === null) {
            $this->filter = new DashToCamelCase();
        }

        return $this->filter;
    }

    /**
     * @return string
     */
    public function getBundle()
    {
        return $this->filter($this->bundle);
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->filter($this->controller);
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->filter($this->action);
    }
}
