<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\Exception\InvalidConfigurationException;

/**
 * @group SprykerSdkTest
 * @group AsyncApi
 * @group AsyncApiFacadeTest
 */
class AsyncApiFacadeTest extends Unit
{
    /**
     * @var string
     */
    public const MESSAGE_NAME = 'MyMessage';

    /**
     * @var \SprykerSdkTest\AsyncApi\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testAddAsyncApiAddsANewAsyncApiFile(): void
    {
        // Arrange
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequest();

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApi(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->assertFileExists($asyncApiRequestTransfer->getTargetFile());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiUpdatesTheVersionOfAnExistingAsyncApiFile(): void
    {
        // Arrange
        $this->tester->haveAsyncApiFile(); // Ensure a file with version 0.1.0 exists
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiUpdateVersionRequest(); // Get a request that will change the version to 1.0.0

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApi(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->tester->assertAsyncApiVersionIsUpdated($asyncApiRequestTransfer->getTargetFile(), '1.0.0');
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageReturnsFailedResponseWhenAsyncApiFileDoesNotExists(): void
    {
        // Arrange
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequest();
        $asyncApiRequestTransfer->setTargetFile('not existing file');

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->assertCount(1, $asyncApiResponseTransfer->getErrors());
        $this->assertSame('File "not existing file" does not exists. Please create one to continue.', $asyncApiResponseTransfer->getErrors()[0]->getMessage());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageFromTransferObjectAddsAPublishMessageToTheAsyncApiFile(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->havePublishMessageWithMetadata();
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApiAndPayloadTransferObject();
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiMessage', $asyncApiMessageTransfer->getChannel()->getName());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageFromTransferObjectAddsASubscribeMessageToTheAsyncApiFile(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->haveSubscribeMessageWithMetadata();
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApiAndPayloadTransferObject();
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->tester->assertAsyncApiHasSubscribeMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiMessage', $asyncApiMessageTransfer->getChannel()->getName());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageFromTransferObjectAddsAnAdditionalPublishMessageWhenChannelHasAlreadyAMessage(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->havePublishMessageWithMetadata();
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApiAndPayloadTransferObject();
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        $this->tester->getFacade()->addAsyncApiMessage($asyncApiRequestTransfer);
        $asyncApiRequestTransfer->getAsyncApiMesssage()->setName('AdditionalMessage');

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiMessage', $asyncApiMessageTransfer->getChannel()->getName());
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AdditionalMessage', $asyncApiMessageTransfer->getChannel()->getName());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageFromTransferObjectAddsAnAdditionalPublishMessageWhenChannelHasAlreadyMessages(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->havePublishMessageWithMetadata();
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApiAndPayloadTransferObject();
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        // Add additional method that creates `oneOf`
        $this->tester->getFacade()->addAsyncApiMessage($asyncApiRequestTransfer);

        // Add another additional method that adds to `oneOf`
        $asyncApiRequestTransfer->getAsyncApiMesssage()->setName('AsyncApiBuilderTest2');
        $this->tester->getFacade()->addAsyncApiMessage($asyncApiRequestTransfer);

        $asyncApiRequestTransfer->getAsyncApiMesssage()->setName('AsyncApiBuilderTest3');

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiMessage', $asyncApiMessageTransfer->getChannel()->getName());
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiBuilderTest2', $asyncApiMessageTransfer->getChannel()->getName());
        $this->tester->assertAsyncApiHasPublishMessageInChannel($asyncApiRequestTransfer->getTargetFile(), 'AsyncApiBuilderTest3', $asyncApiMessageTransfer->getChannel()->getName());
    }

    /**
     * @return void
     */
    public function testAddAsyncApiMessageAddsMessageWithDefinedProperties(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->havePublishMessageWithMetadata(static::MESSAGE_NAME);
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApiAndProperties();
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        // Act
        $asyncApiResponseTransfer = $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );

        // Assert
        $this->tester->assertAsyncApiResponseHasNoErrors($asyncApiResponseTransfer);
        $this->tester->assertMessageInChannelHasProperty($asyncApiRequestTransfer->getTargetFile(), $asyncApiMessageTransfer->getChannel()->getName(), 'publish', $asyncApiMessageTransfer->getName(), ['firstName', 'string', true]);
        $this->tester->assertMessageInChannelHasProperty($asyncApiRequestTransfer->getTargetFile(), $asyncApiMessageTransfer->getChannel()->getName(), 'publish', $asyncApiMessageTransfer->getName(), ['lastName', 'string', false]);
        $this->tester->assertMessageInChannelHasProperty($asyncApiRequestTransfer->getTargetFile(), $asyncApiMessageTransfer->getChannel()->getName(), 'publish', $asyncApiMessageTransfer->getName(), ['phoneNumber', 'int', true]);
    }

    /**
     * @return void
     */
    public function testAddAsyncApiThrowsExceptionWhenTransferObjectAndPropertiesGivenInAsyncApiRequest(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->havePublishMessageWithMetadata(static::MESSAGE_NAME);
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApiAndPayloadTransferObject();
        $asyncApiRequestTransfer->setProperties(['foo' => 'bar']);
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        // Expect
        $this->expectException(InvalidConfigurationException::class);

        // Act
        $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );
    }

    /**
     * @return void
     */
    public function testAddAsyncApiThrowsExceptionWhenNeitherTransferObjectOrPropertiesGivenInAsyncApiRequest(): void
    {
        // Arrange
        $asyncApiMessageTransfer = $this->tester->havePublishMessageWithMetadata(static::MESSAGE_NAME);
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequestWithExistingAsyncApi();
        $asyncApiRequestTransfer
            ->setPayloadTransferObjectName(null)
            ->setAsyncApiMesssage($asyncApiMessageTransfer);

        // Expect
        $this->expectException(InvalidConfigurationException::class);

        // Act
        $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );
    }

    /**
     * @return void
     */
    public function testAddAsyncApiThrowsExceptionWhenOperationIdIsMissionInAsyncApiRequest(): void
    {
        // Arrange
        $this->tester->haveAsyncApiFileWithMissingRequiredFields();
        $asyncApiMessageTransfer = $this->tester->havePublishMessageWithMetadata(static::MESSAGE_NAME);
        $asyncApiRequestTransfer = $this->tester->haveAsyncApiAddRequest();
        $asyncApiRequestTransfer->setProperties(['foo' => 'bar']);
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        // Expect
        $this->expectException(InvalidConfigurationException::class);

        // Act
        $this->tester->getFacade()->addAsyncApiMessage(
            $asyncApiRequestTransfer,
        );
    }
}
