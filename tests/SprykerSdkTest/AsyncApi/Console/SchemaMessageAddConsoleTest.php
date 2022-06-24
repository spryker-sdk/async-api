<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi\Console;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoader;
use SprykerSdk\AsyncApi\Console\AbstractConsole;
use SprykerSdk\AsyncApi\Console\SchemaMessageAddConsole;
use SprykerSdk\AsyncApi\Exception\InvalidConfigurationException;
use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdkTest\AsyncApi\AsyncApiTester;
use Symfony\Component\Console\Output\OutputInterface;
use Transfer\AsyncApiBuilderTestTransfer;

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
        $this->expectExceptionMessage(
            AsyncApiError::messageTypeHasWrongValue(
                SchemaMessageAddConsole::OPTION_MESSAGE_TYPE,
                [SchemaMessageAddConsole::VALUE_PUBLISH, SchemaMessageAddConsole::VALUE_SUBSCRIBE],
            ),
        );
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

    /**
     * @return void
     */
    public function testAddExistingMessageAndCheckOnlyOneExists(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(SchemaMessageAddConsole::class, false);

        // Act
        $commandTester->execute(
            [
                '--' . SchemaMessageAddConsole::OPTION_MESSAGE_TYPE => 'publish',
                '--' . SchemaMessageAddConsole::OPTION_PROPERTY => ['property:string'],
                '--' . SchemaMessageAddConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/asyncapi/console/asyncapi-empty.yml'),
                SchemaMessageAddConsole::ARGUMENT_CHANNEL_NAME => 'test/channel',
                SchemaMessageAddConsole::ARGUMENT_OPERATION_ID => 'operationId',
                SchemaMessageAddConsole::ARGUMENT_MESSAGE_NAME => 'testing',
            ],
        );

        $asyncApiLoader = new AsyncApiLoader();
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/asyncapi/console/asyncapi-empty.yml'));

        // Assert
        $this->assertCount(1, $asyncApi->getChannel('test/channel')->getPublishMessages());
    }
}
