<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Channel;

use SprykerSdk\AsyncApi\Message\AsyncApiMessageCollectionInterface;
use SprykerSdk\AsyncApi\Message\AsyncApiMessageInterface;

class AsyncApiChannel implements AsyncApiChannelInterface
{
    /**
     * @var string
     */
    protected string $name;

    /**
     * @var \SprykerSdk\AsyncApi\Message\AsyncApiMessageCollectionInterface
     */
    protected AsyncApiMessageCollectionInterface $publishMessages;

    /**
     * @var \SprykerSdk\AsyncApi\Message\AsyncApiMessageCollectionInterface
     */
    protected AsyncApiMessageCollectionInterface $subscribeMessages;

    /**
     * @param string $name
     * @param \SprykerSdk\AsyncApi\Message\AsyncApiMessageCollectionInterface $publishMessages
     * @param \SprykerSdk\AsyncApi\Message\AsyncApiMessageCollectionInterface $subscribeMessages
     */
    public function __construct(string $name, AsyncApiMessageCollectionInterface $publishMessages, AsyncApiMessageCollectionInterface $subscribeMessages)
    {
        $this->name = $name;
        $this->publishMessages = $publishMessages;
        $this->subscribeMessages = $subscribeMessages;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return iterable<string, \SprykerSdk\AsyncApi\Message\AsyncApiMessageInterface>
     */
    public function getPublishMessages(): iterable
    {
        yield from $this->publishMessages->getMessages();
    }

    /**
     * @param string $messageName
     *
     * @return \SprykerSdk\AsyncApi\Message\AsyncApiMessageInterface|null
     */
    public function getPublishMessage(string $messageName): ?AsyncApiMessageInterface
    {
        return $this->publishMessages->getMessage($messageName);
    }

    /**
     * @return iterable<string, \SprykerSdk\AsyncApi\Message\AsyncApiMessageInterface>
     */
    public function getSubscribeMessages(): iterable
    {
        yield from $this->subscribeMessages->getMessages();
    }

    /**
     * @param string $messageName
     *
     * @return \SprykerSdk\AsyncApi\Message\AsyncApiMessageInterface|null
     */
    public function getSubscribeMessage(string $messageName): ?AsyncApiMessageInterface
    {
        return $this->subscribeMessages->getMessage($messageName);
    }
}
