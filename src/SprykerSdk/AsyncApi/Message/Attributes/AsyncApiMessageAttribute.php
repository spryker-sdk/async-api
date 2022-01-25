<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Message\Attributes;

class AsyncApiMessageAttribute implements AsyncApiMessageAttributeInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var \SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|string|int
     */
    protected $value;

    /**
     * @param string $name
     * @param \SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|string|int $value
     */
    public function __construct(string $name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|string|int
     */
    public function getValue()
    {
        return $this->value;
    }
}
