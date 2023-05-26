<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi\AsyncApi\Message;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\Message\AsyncApiInfo;

class AsyncApiInfoTest extends Unit
{
    /**
     * @return void
     */
    public function testInfoMessageIsFormattedWhenOSIsNotWindows()
    {
        $class = new AsyncApiInfo(true);
        $message = $class::asyncApiSchemaFileIsValid();

        $this->assertNotNull($message);
        $this->assertStringContainsString('[', $message);
    }

    /**
     * @return void
     */
    public function testInfoMessageIsNotFormattedWhenOSIsWindows()
    {
        $class = new AsyncApiInfo();
        $message = $class::asyncApiSchemaFileIsValid();

        $this->assertNotNull($message);
        $this->assertStringNotContainsString('[', $message);
    }
}
