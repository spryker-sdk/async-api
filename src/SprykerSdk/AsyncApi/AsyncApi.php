<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi;

use SprykerSdk\AsyncApi\Channel\AsyncApiChannelCollectionInterface;
use SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface;

class AsyncApi implements AsyncApiInterface
{
    /**
     * @var \SprykerSdk\AsyncApi\Channel\AsyncApiChannelCollectionInterface
     */
    protected AsyncApiChannelCollectionInterface $channels;

    /**
     * @param \SprykerSdk\AsyncApi\Channel\AsyncApiChannelCollectionInterface $channels
     */
    public function __construct(AsyncApiChannelCollectionInterface $channels)
    {
        $this->channels = $channels;
    }

    /**
     * @return iterable<\SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface>
     */
    public function getChannels(): iterable
    {
        return $this->channels->getChannels();
    }

    /**
     * @param string $channelName
     *
     * @return \SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface|null
     */
    public function getChannel(string $channelName): ?AsyncApiChannelInterface
    {
        return $this->channels->getChannel($channelName);
    }
}
