<?php

namespace SprykerSdkTest\AsyncApi\AsyncApi\Message;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\Message\AsyncApiError;

class AsyncApiErrorTest extends Unit
{
    protected const TEST_PATH = 'test/path';

    public function testErrorMessageIsFormattedWhenOSIsNotWindows()
    {
        $class = new AsyncApiError(true);
        $message = $class::couldNotFindAModulePropertyInTheSprykerExtension(static::TEST_PATH);

        $this->assertNotNull($message);
        $this->assertStringContainsString('[', $message);
    }

    public function testErrorMessageIsNotFormattedWhenOSIsWindows()
    {
        $class = new AsyncApiError();
        $message = $class::couldNotFindAModulePropertyInTheSprykerExtension(static::TEST_PATH);

        $this->assertNotNull($message);
        $this->assertStringNotContainsString('[', $message);
    }
}
