<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\AsyncApiConfig;

/**
 * @group SprykerSdkTest
 * @group AsyncApi
 * @group AsyncApiConfigTest
 */
class AsyncApiConfigTest extends Unit
{
    /**
     * @var \SprykerSdkTest\AsyncApi\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * Tests that ensures we get the default executable path when installed the "normal" way.
     *
     * @return void
     */
    public function testGetSprykRunExecutableReturnsDefaultExecutable(): void
    {
        // Arrange
        $expectedExecutable = getcwd() . '/vendor/bin/';
        $config = new AsyncApiConfig();

        // Act
        $sprykRunExecutable = $config->getSprykRunExecutablePath();

        // Assert
        $this->assertSame($expectedExecutable, $sprykRunExecutable);
    }

    /**
     * Tests that ensures we get a path to where this SDK is installed. Usually only when used within the SprykerSDK.
     *
     * @return void
     */
    public function testGetSprykRunExecutableReturnsExternalDefinedExecutable(): void
    {
        putenv('INSTALLED_ROOT_DIRECTORY=foo-bar');
        // Arrange
        $expectedExecutable = 'foo-bar/vendor/bin/';
        $config = new AsyncApiConfig();

        // Act
        $sprykRunExecutable = $config->getSprykRunExecutablePath();

        // Assert
        $this->assertSame($expectedExecutable, $sprykRunExecutable);
        putenv('INSTALLED_ROOT_DIRECTORY');
    }
}
