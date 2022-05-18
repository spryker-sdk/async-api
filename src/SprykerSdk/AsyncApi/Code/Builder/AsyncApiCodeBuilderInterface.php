<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Code\Builder;

use Transfer\AsyncApiRequestTransfer;
use Transfer\AsyncApiResponseTransfer;

interface AsyncApiCodeBuilderInterface
{
    /**
     * @param \Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Transfer\AsyncApiResponseTransfer
     */
    public function build(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer;
}
