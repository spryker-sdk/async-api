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
     * @var string
     */
    protected const TEST_FILE_NAME = 'fileName';

    /**
     * @return void
     */
    public function testInfoMessageIsFormattedWhenOSIsNotWindows()
    {
        $class = new AsyncApiInfo(true);
        $message = $class::asyncApiFileCreated(static::TEST_FILE_NAME);

        $this->assertNotNull($message);
        $this->assertStringContainsString('[', $message);
    }

    /**
     * @return void
     */
    public function testInfoMessageIsNotFormattedWhenOSIsWindows()
    {
        $class = new AsyncApiInfo(false);
        $message = $class::asyncApiFileCreated(static::TEST_FILE_NAME);

        $this->assertNotNull($message);
        $this->assertStringNotContainsString('[', $message);
    }
}
