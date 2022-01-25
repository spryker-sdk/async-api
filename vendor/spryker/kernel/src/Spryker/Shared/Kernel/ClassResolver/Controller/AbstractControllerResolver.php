<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\Controller;

use Spryker\Shared\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Shared\Kernel\Communication\BundleControllerActionInterface;

/**
 * @method \Spryker\Zed\Kernel\Communication\Controller\AbstractController getResolvedClassInstance()
 */
abstract class AbstractControllerResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    public const KEY_CONTROLLER = '%controller%';

    /**
     * @var \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface
     */
    protected $bundleControllerAction;

    /**
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     *
     * @throws \Spryker\Shared\Kernel\ClassResolver\Controller\ControllerNotFoundException
     *
     * @return object
     */
    public function resolve($bundleControllerAction)
    {
        $this->bundleControllerAction = $bundleControllerAction;

        if ($this->canResolve()) {
            return $this->getResolvedClassInstance();
        }

        throw new ControllerNotFoundException($bundleControllerAction);
    }

    /**
     * @param \Spryker\Shared\Kernel\Communication\BundleControllerActionInterface $bundleControllerAction
     *
     * @return bool
     */
    public function isResolveAble(BundleControllerActionInterface $bundleControllerAction)
    {
        $this->bundleControllerAction = $bundleControllerAction;
        if ($this->canResolve()) {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            $this->getClassNamePattern(),
            static::KEY_NAMESPACE,
            static::KEY_BUNDLE,
            static::KEY_CODE_BUCKET,
            static::KEY_CONTROLLER,
        );
    }

    /**
     * @return string
     */
    abstract protected function getClassNamePattern();

    /**
     * @param string $namespace
     * @param string|null $codeBucket
     *
     * @return string
     */
    protected function buildClassName($namespace, $codeBucket = null)
    {
        $searchAndReplace = [
            static::KEY_NAMESPACE => $namespace,
            static::KEY_BUNDLE => ucfirst($this->bundleControllerAction->getBundle()),
            static::KEY_CODE_BUCKET => $codeBucket,
            static::KEY_CONTROLLER => ucfirst($this->bundleControllerAction->getController()),
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern(),
        );

        return $className;
    }
}
