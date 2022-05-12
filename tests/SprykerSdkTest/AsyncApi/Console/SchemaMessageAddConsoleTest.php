<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi\Console;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AsyncApiBuilderTestTransfer;
use SprykerSdk\AsyncApi\Console\AbstractConsole;
use SprykerSdk\AsyncApi\Console\SchemaMessageAddConsole;
use SprykerSdk\AsyncApi\Exception\InvalidConfigurationException;
use SprykerSdkTest\AsyncApi\AsyncApiTester;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerSdkTest
 * @group AsyncApi
 * @group Console
 * @group SchemaMessageAddConsoleTest
 */
class SchemaMessageAddConsoleTest extends Unit
{
    /**
     * @var \SprykerSdkTest\AsyncApi\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testAddMessageReturnsSuccessCodeWhenMessageWasAdded(): void
    {
        // Arrange
        $this->tester->haveAsyncApiFile();
        $commandTester = $this->tester->getConsoleTester(SchemaMessageAddConsole::class);

        // Act
        $commandTester->execute(
            [
                SchemaMessageAddConsole::ARGUMENT_CHANNEL_NAME => 'test/channel',
                SchemaMessageAddConsole::ARGUMENT_OPERATION_ID => 'operationId',
                '--' . SchemaMessageAddConsole::OPTION_MESSAGE_TYPE => 'subscribe',
                '--' . SchemaMessageAddConsole::OPTION_FROM_TRANSFER_CLASS => AsyncApiBuilderTestTransfer::class,
            ],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_SUCCESS, $commandTester->getStatusCode());
    }

    /**
     * @return void
     */
    public function testAddMessageReturnsErrorCodeAndPrintsErrorMessagesWhenMessageCouldNotBeAddedWhenAsyncApiDoesNotExists(): void
    {
        $commandTester = $this->tester->getConsoleTester(SchemaMessageAddConsole::class, false);

        // Act
        $commandTester->execute(
            [
                SchemaMessageAddConsole::ARGUMENT_CHANNEL_NAME => 'test/channel',
                SchemaMessageAddConsole::ARGUMENT_OPERATION_ID => 'operationId',
                '--' . SchemaMessageAddConsole::OPTION_MESSAGE_TYPE => 'publish',
                '--' . SchemaMessageAddConsole::OPTION_FROM_TRANSFER_CLASS => AsyncApiBuilderTestTransfer::class,
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );

        // Assert
        $this->assertSame(AbstractConsole::CODE_ERROR, $commandTester->getStatusCode());
        $this->assertNotEmpty($commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testAddMessageReturnsErrorCodeAndPrintsErrorMessagesWhenMessageTypeIsNotPublishAndNotSubscribe(): void
    {
        $commandTester = $this->tester->getConsoleTester(SchemaMessageAddConsole::class, false);

        // Assert
        $this->expectException(InvalidConfigurationException::class);

        // Act
        $commandTester->execute(
            [
                SchemaMessageAddConsole::ARGUMENT_CHANNEL_NAME => 'test/channel',
                SchemaMessageAddConsole::ARGUMENT_OPERATION_ID => 'operationId',
                '--' . SchemaMessageAddConsole::OPTION_MESSAGE_TYPE => 'publishTest',
            ],
            ['verbosity' => OutputInterface::VERBOSITY_VERBOSE],
        );
    }
}
