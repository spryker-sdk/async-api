<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Message;

use SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface;

class AsyncApiMessage implements AsyncApiMessageInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var \SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface
     */
    protected AsyncApiMessageAttributeCollectionInterface $attributes;

    /**
     * @param string $name
     * @param \SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $attributes
     */
    public function __construct(string $name, AsyncApiMessageAttributeCollectionInterface $attributes)
    {
        $this->name = $name;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return iterable<string, \SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface>
     */
    public function getAttributes(): iterable
    {
        return $this->attributes->getAttributes();
    }

    /**
     * @param string $attributeName
     *
     * @return \SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|\SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|null
     */
    public function getAttribute(string $attributeName)
    {
        return $this->attributes->getAttribute($attributeName);
    }
}
