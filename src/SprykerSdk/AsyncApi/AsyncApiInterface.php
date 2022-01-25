<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi;

use SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface;

interface AsyncApiInterface
{
    /**
     * @return iterable<\SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface>
     */
    public function getChannels(): iterable;

    /**
     * @param string $channelName
     *
     * @return \SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface|null
     */
    public function getChannel(string $channelName): ?AsyncApiChannelInterface;
}
