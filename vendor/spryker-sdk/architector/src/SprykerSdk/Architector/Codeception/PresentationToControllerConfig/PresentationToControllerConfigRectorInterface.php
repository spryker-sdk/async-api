<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Architector\Codeception\PresentationToControllerConfig;

use Rector\Core\Contract\Rector\RectorInterface;

interface PresentationToControllerConfigRectorInterface extends RectorInterface
{
    /**
     * @param string $content
     *
     * @return string
     */
    public function transform(string $content): string;
}
