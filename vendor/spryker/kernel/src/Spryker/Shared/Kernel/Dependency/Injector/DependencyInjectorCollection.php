<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\Dependency\Injector;

class DependencyInjectorCollection implements DependencyInjectorCollectionInterface
{
    /**
     * @var array<\Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorInterface>
     */
    protected $dependencyInjector = [];

    /**
     * @param \Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorInterface $dependencyInjector
     *
     * @return $this
     */
    public function addDependencyInjector(DependencyInjectorInterface $dependencyInjector)
    {
        $this->dependencyInjector[] = $dependencyInjector;

        return $this;
    }

    /**
     * @return array<\Spryker\Shared\Kernel\Dependency\Injector\DependencyInjectorInterface>
     */
    public function getDependencyInjector()
    {
        return $this->dependencyInjector;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->dependencyInjector);
    }
}
