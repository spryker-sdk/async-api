<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Transfer\Business\Model;

interface TransferValidatorInterface
{
    /**
     * @param array<string, mixed> $options
     *
     * @return bool
     */
    public function validate(array $options): bool;
}
