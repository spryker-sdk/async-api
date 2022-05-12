<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\AsyncApiFactory;
use SprykerSdk\AsyncApi\Code\Builder\AsyncApiCodeBuilderInterface;

/**
 * @group SprykerSdkTest
 * @group AsyncApi
 * @group AsyncApiFactoryTest
 */
class AsyncApiFactoryTest extends Unit
{
    /**
     * @var \SprykerSdkTest\AsyncApi\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testCreateAsyncApiCodeBuilder(): void
    {
        // Act
        $factory = new AsyncApiFactory();
        $asyncApiCodeBuilder = $factory->createAsyncApiCodeBuilder();

        // Assert
        $this->assertInstanceOf(AsyncApiCodeBuilderInterface::class, $asyncApiCodeBuilder);
    }
}
