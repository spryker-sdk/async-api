<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Message\Attributes;

class AsyncApiMessageAttributeCollection implements AsyncApiMessageAttributeCollectionInterface
{
    /**
     * @var array<(\SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|\SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface)>
     */
    protected array $attributes;

    /**
     * @param array<(\SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|\SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface)> $attributes
     */
    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return iterable<(\SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|\SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface)>
     */
    public function getAttributes(): iterable
    {
        foreach ($this->attributes as $attributeName => $attribute) {
            yield $attributeName => $attribute;
        }
    }

    /**
     * @param string $attributeName
     *
     * @return \SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|\SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|null
     */
    public function getAttribute(string $attributeName)
    {
        return $this->attributes[$attributeName] ?? null;
    }
}
