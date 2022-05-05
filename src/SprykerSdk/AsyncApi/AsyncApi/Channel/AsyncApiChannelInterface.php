<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\AsyncApi\Channel;

use SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface;

interface AsyncApiChannelInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return array<string, \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface>
     */
    public function getPublishMessages(): iterable;

    /**
     * @param string $messageName
     *
     * @return \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface|null
     */
    public function getPublishMessage(string $messageName): ?AsyncApiMessageInterface;

    /**
     * @return array<string, \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface>
     */
    public function getSubscribeMessages(): iterable;

    /**
     * @param string $messageName
     *
     * @return \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface|null
     */
    public function getSubscribeMessage(string $messageName): ?AsyncApiMessageInterface;
}
