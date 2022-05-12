<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi\Console;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\Console\AbstractConsole;
use SprykerSdk\AsyncApi\Console\SchemaValidateConsole;
use SprykerSdk\AsyncApi\Messages\AsyncApiMessages;
use SprykerSdkTest\AsyncApi\AsyncApiTester;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group AsyncApi
 * @group Validator
 * @group AsyncApiValidatorTest
 */
class AsyncApiValidatorTest extends Unit
{
    /**
     * @var \SprykerSdkTest\AsyncApi\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testValidateAsyncApiReturnsErrorMessageWhenNoChannelsDefined(): void
    {
        // Arrange
        $this->tester->haveDefaultCreatedAsyncApiFile();

        $commandTester = $this->tester->getConsoleTester(SchemaValidateConsole::class);

        // Act
        $commandTester->execute([], ['verbosity' => OutputInterface::VERBOSITY_VERBOSE]);

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertStringContainsString(AsyncApiMessages::VALIDATOR_ERROR_NO_CHANNELS_DEFINED, $commandTester->getDisplay());
    }
}
