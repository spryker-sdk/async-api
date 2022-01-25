<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Message;

interface AsyncApiMessageInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return iterable<string, \SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface>
     */
    public function getAttributes(): iterable;

    /**
     * @param string $attributeName
     *
     * @return \SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|\SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface|null
     */
    public function getAttribute(string $attributeName);
}
