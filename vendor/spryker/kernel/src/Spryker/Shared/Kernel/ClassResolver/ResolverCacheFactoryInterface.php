<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver;

/**
 * @deprecated Use {@link \Spryker\Shared\Kernel\KernelConstants::RESOLVABLE_CLASS_NAMES_CACHE_ENABLED} instead.
 */
interface ResolverCacheFactoryInterface
{
    /**
     * @return bool
     */
    public function useCache();

    /**
     * @throws \Exception
     *
     * @return \Spryker\Shared\Kernel\ClassResolver\Cache\ProviderInterface
     */
    public function createClassResolverCacheProvider();
}
