<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Kernel\ClassResolver\DependencyInjector;

use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Yves\Kernel\ClassResolver\AbstractClassResolver;
use Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorCollection;

class DependencyInjectorResolver extends AbstractClassResolver
{
    /**
     * @var string
     */
    public const CLASS_NAME_PATTERN = '\\%1$s\\Yves\\%2$s%3$s\\Dependency\\Injector\\%4$sDependencyInjector';

    /**
     * @var string
     */
    public const KEY_FROM_BUNDLE = '%fromBundle%';

    /**
     * @var string
     */
    protected const RESOLVABLE_TYPE = 'YvesDependencyInjector';

    /**
     * @var string
     */
    protected $fromBundle;

    /**
     * @param object|string $callerClass
     *
     * @return \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorCollectionInterface
     */
    public function resolve($callerClass)
    {
        $dependencyInjectorCollection = $this->getDependencyInjectorCollection();

        $this->setCallerClass($callerClass);
        $injectToBundle = $this->getClassInfo()->getModule();
        $injectFromBundles = $this->getInjectorBundles($injectToBundle);

        foreach ($injectFromBundles as $injectFromBundle) {
            $this->fromBundle = $injectFromBundle;

            $this->unsetCurrentCacheEntry();

            if ($this->canResolve()) {
                $resolvedInjector = $this->getResolvedClassInstance();
                $dependencyInjectorCollection->addDependencyInjector($resolvedInjector);
            }
        }

        return $dependencyInjectorCollection;
    }

    /**
     * @return \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorInterface
     */
    protected function getResolvedClassInstance()
    {
        /** @var \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorInterface $dependencyInjector */
        $dependencyInjector = parent::getResolvedClassInstance();

        return $dependencyInjector;
    }

    /**
     * @return string
     */
    public function getClassPattern()
    {
        return sprintf(
            static::CLASS_NAME_PATTERN,
            static::KEY_NAMESPACE,
            static::KEY_FROM_BUNDLE,
            static::KEY_CODE_BUCKET,
            static::KEY_BUNDLE,
        );
    }

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
            static::KEY_BUNDLE => $this->getClassInfo()->getModule(),
            static::KEY_FROM_BUNDLE => $this->fromBundle,
            static::KEY_CODE_BUCKET => $codeBucket,
        ];

        $className = str_replace(
            array_keys($searchAndReplace),
            array_values($searchAndReplace),
            $this->getClassPattern(),
        );

        return $className;
    }

    /**
     * @param string $injectToBundle
     *
     * @return array
     */
    protected function getInjectorBundles($injectToBundle)
    {
        $injectorConfiguration = $this->getDependencyInjectorConfiguration();
        if (!isset($injectorConfiguration[$injectToBundle])) {
            return [];
        }

        return $injectorConfiguration[$injectToBundle];
    }

    /**
     * @return array
     */
    protected function getDependencyInjectorConfiguration()
    {
        return Config::get(KernelConstants::DEPENDENCY_INJECTOR_YVES, []);
    }

    /**
     * @return \Spryker\Yves\Kernel\Dependency\Injector\DependencyInjectorCollection
     */
    protected function getDependencyInjectorCollection()
    {
        return new DependencyInjectorCollection();
    }
}
