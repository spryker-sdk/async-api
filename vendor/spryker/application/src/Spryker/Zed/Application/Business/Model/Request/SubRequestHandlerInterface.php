<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Business\Model\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * @deprecated Use {@link \Spryker\Zed\Http\Communication\SubRequest\SubRequestHandlerInterface} instead.
 */
interface SubRequestHandlerInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $url
     * @param array $additionalSubRequestParameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleSubRequest(Request $request, $url, array $additionalSubRequestParameters = []);
}
