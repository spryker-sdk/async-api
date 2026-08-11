<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\AsyncApi;

use SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface;

interface AsyncApiInterface
{
    /**
     * @return iterable<\SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface>
     */
    public function getChannels(): iterable;

    /**
     * @param string $channelName
     */
    public function getChannel(string $channelName): ?AsyncApiChannelInterface;
}
