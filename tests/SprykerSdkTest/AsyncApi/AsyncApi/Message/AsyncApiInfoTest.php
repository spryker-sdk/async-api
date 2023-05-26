<?php

namespace SprykerSdkTest\AsyncApi\AsyncApi\Message;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\Message\AsyncApiInfo;

class AsyncApiInfoTest extends Unit
{
    public function testInfoMessageIsFormattedWhenOSIsNotWindows()
    {
        $class = new AsyncApiInfo(true);
        $message = $class::asyncApiSchemaFileIsValid();

        $this->assertNotNull($message);
        $this->assertStringContainsString('[', $message);
    }

    public function testInfoMessageIsNotFormattedWhenOSIsWindows()
    {
        $class = new AsyncApiInfo();
        $message = $class::asyncApiSchemaFileIsValid();

        $this->assertNotNull($message);
        $this->assertStringNotContainsString('[', $message);
    }
}
