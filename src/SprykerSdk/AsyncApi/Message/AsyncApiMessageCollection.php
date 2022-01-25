<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Message;

class AsyncApiMessageCollection implements AsyncApiMessageCollectionInterface
{
    /**
     * @var array<string, \SprykerSdk\AsyncApi\Message\AsyncApiMessageInterface>
     */
    protected array $messages;

    /**
     * @param array<string, \SprykerSdk\AsyncApi\Message\AsyncApiMessageInterface> $messages
     */
    public function __construct(array $messages)
    {
        $this->messages = $messages;
    }

    /**
     * @return iterable<string, \SprykerSdk\AsyncApi\Message\AsyncApiMessageInterface>
     */
    public function getMessages(): iterable
    {
        foreach ($this->messages as $messageName => $message) {
            yield $messageName => $message;
        }
    }

    /**
     * @param string $messageName
     *
     * @return \SprykerSdk\AsyncApi\Message\AsyncApiMessageInterface|null
     */
    public function getMessage(string $messageName): ?AsyncApiMessageInterface
    {
        return $this->messages[$messageName] ?? null;
    }
}
