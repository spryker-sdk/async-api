<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi\AsyncApi\Message;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\Message\AsyncApiError;

class AsyncApiErrorTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PATH = 'test/path';

    /**
     * @return void
     */
    public function testErrorMessageIsFormattedWhenOSIsNotWindows()
    {
        $class = new AsyncApiError(true);
        $message = $class::couldNotFindAModulePropertyInTheSprykerExtension(static::TEST_PATH);

        $this->assertNotNull($message);
        $this->assertStringContainsString('[', $message);
    }

    /**
     * @return void
     */
    public function testErrorMessageIsNotFormattedWhenOSIsWindows()
    {
        $class = new AsyncApiError(false);
        $message = $class::couldNotFindAModulePropertyInTheSprykerExtension(static::TEST_PATH);

        $this->assertNotNull($message);
        $this->assertStringNotContainsString('[', $message);
    }
}
