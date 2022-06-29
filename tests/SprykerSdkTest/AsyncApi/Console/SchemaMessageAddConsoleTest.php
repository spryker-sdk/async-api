<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi\Console;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\Console\AbstractConsole;
use SprykerSdk\AsyncApi\Console\SchemaMessageAddConsole;
use SprykerSdk\AsyncApi\Exception\InvalidConfigurationException;
use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdkTest\AsyncApi\AsyncApiTester;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
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
     * This test ensures that a message can only be added once per channel.
     * The given API file already has the message that should be added and thus adding it again will be skipped.
     *
     * @return void
     */
    public function testAddMessageOnlyWhenMessageNameDoesNotExistInChannel(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(SchemaMessageAddConsole::class, false);

        // Act
        // The message 'OutgoingMessage' is added for the second time in the channel 'channelNameA'
        $commandTester->execute(
            [
                '--' . SchemaMessageAddConsole::OPTION_MESSAGE_TYPE => 'subscribe',
                '--' . SchemaMessageAddConsole::OPTION_PROPERTY => ['property:string'],
                '--' . SchemaMessageAddConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/asyncapi/asyncapi.yml'),
                SchemaMessageAddConsole::ARGUMENT_CHANNEL_NAME => 'channelNameA',
                SchemaMessageAddConsole::ARGUMENT_OPERATION_ID => 'operationId',
                SchemaMessageAddConsole::ARGUMENT_MESSAGE_NAME => 'OutgoingMessage',
            ],
        );

        $asyncApi = Yaml::parseFile(codecept_data_dir('api/asyncapi/asyncapi.yml'));

        // Assert
        $this->tester->assertMessageExistsOnlyOnceInChannel($asyncApi, 'OutgoingMessage', 'channelNameA', 'subscribe');
    }

    /**
     * This test ensures that the array 'oneOf' is not created when trying to add an existing message to a channel that has only one message.
     *
     * @return void
     */
    public function testAddExistingMessageToChannelWithOneMessageDoesNotCreateOneOfArray(): void
    {
        // Arrange
        $commandTester = $this->tester->getConsoleTester(SchemaMessageAddConsole::class, false);

        // Act
        // The message 'PaymentMethodAdded' is added for the second time in the channel 'payment'
        $commandTester->execute(
            [
                '--' . SchemaMessageAddConsole::OPTION_MESSAGE_TYPE => 'publish',
                '--' . SchemaMessageAddConsole::OPTION_PROPERTY => ['property:string'],
                '--' . SchemaMessageAddConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('api/asyncapi/asyncapi-one-reference.yml'),
                SchemaMessageAddConsole::ARGUMENT_CHANNEL_NAME => 'payment',
                SchemaMessageAddConsole::ARGUMENT_OPERATION_ID => 'operationId',
                SchemaMessageAddConsole::ARGUMENT_MESSAGE_NAME => 'PaymentMethodAdded',
            ],
        );

        $asyncApi = Yaml::parseFile(codecept_data_dir('api/asyncapi/asyncapi-one-reference.yml'));

        // Assert
        // The 'oneOf' array wasn't created
        $this->assertTrue(!isset($asyncApi['channels']['payment']['publish']['message']['oneOf']));
    }
}
