<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Channel;

class AsyncApiChannelCollection implements AsyncApiChannelCollectionInterface
{
    /**
     * @var array<\SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface>
     */
    protected array $channels;

    /**
     * @param array<\SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface> $channels
     */
    public function __construct(array $channels)
    {
        $this->channels = $channels;
    }

    /**
     * @return iterable<\SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface>
     */
    public function getChannels(): iterable
    {
        foreach ($this->channels as $channelName => $channel) {
            yield $channelName => $channel;
        }
    }

    /**
     * @param string $channelName
     *
     * @return \SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface|null
     */
    public function getChannel(string $channelName): ?AsyncApiChannelInterface
    {
        return $this->channels[$channelName] ?? null;
    }
}
