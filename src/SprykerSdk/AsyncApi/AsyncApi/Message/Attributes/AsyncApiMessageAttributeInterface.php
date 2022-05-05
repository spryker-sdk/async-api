<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\AsyncApi\Message\Attributes;

interface AsyncApiMessageAttributeInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|string|int
     */
    public function getValue();
}
