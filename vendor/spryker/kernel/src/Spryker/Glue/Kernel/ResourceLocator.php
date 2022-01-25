<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel;

use Spryker\Glue\Kernel\ClassResolver\RestResource\ResourceResolver;
use Spryker\Shared\Kernel\AbstractLocator;

class ResourceLocator extends AbstractLocator
{
    /**
     * @var string
     */
    public const SERVICE_SUFFIX = 'Resource';

    /**
     * @var string
     */
    protected $application = 'Glue';

    /**
     * @api
     *
     * @param string $bundle
     *
     * @return \Spryker\Glue\Kernel\AbstractRestResource
     */
    public function locate($bundle)
    {
        $restResourceResolver = new ResourceResolver();

        return $restResourceResolver->resolve($bundle);
    }
}
