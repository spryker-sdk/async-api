<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Message;

use Transfer\MessageTransfer;

interface MessageBuilderInterface
{
    /**
     * @param string $message
     *
     * @return \Transfer\MessageTransfer
     */
    public function buildMessage(string $message): MessageTransfer;
}
