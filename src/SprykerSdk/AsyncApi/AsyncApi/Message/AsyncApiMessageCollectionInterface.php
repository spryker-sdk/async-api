<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\AsyncApi\Message;

interface AsyncApiMessageCollectionInterface
{
    /**
     * @return iterable<\SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface>
     */
    public function getMessages(): iterable;

    /**
     * @param string $messageName
     *
     * @return \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface|null
     */
    public function getMessage(string $messageName): ?AsyncApiMessageInterface;
}
