<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\AsyncApi;

use SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelCollectionInterface;
use SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface;

class AsyncApi implements AsyncApiInterface
{
    /**
     * @var \SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelCollectionInterface
     */
    protected AsyncApiChannelCollectionInterface $channels;

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelCollectionInterface $channels
     */
    public function __construct(AsyncApiChannelCollectionInterface $channels)
    {
        $this->channels = $channels;
    }

    /**
     * @return iterable<\SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface>
     */
    public function getChannels(): iterable
    {
        return $this->channels->getChannels();
    }

    /**
     * @param string $channelName
     *
     * @return \SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface|null
     */
    public function getChannel(string $channelName): ?AsyncApiChannelInterface
    {
        return $this->channels->getChannel($channelName);
    }
}
