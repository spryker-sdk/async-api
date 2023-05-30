<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\AsyncApi\Cli;

use Transfer\ValidateResponseTransfer;

interface AsyncApiCliInterface
{
    /**
     * @return bool
     */
    public function isCliInstalled(): bool;

    /**
     * @param \Transfer\ValidateResponseTransfer $validateResponseTransfer
     * @param string $asyncApiFilePath
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validate(ValidateResponseTransfer $validateResponseTransfer, string $asyncApiFilePath): ValidateResponseTransfer;
}
