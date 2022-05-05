<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\AsyncApi\Message;

interface AsyncApiMessageInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return iterable<string, \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface>
     */
    public function getAttributes(): iterable;

    /**
     * @param string $attributeName
     *
     * @return \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|\SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|null
     */
    public function getAttribute(string $attributeName);
}
