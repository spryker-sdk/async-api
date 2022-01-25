<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\AsyncApiInterface;
use SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface;
use SprykerSdk\AsyncApi\Loader\AsyncApiLoader;
use SprykerSdk\AsyncApi\Message\AsyncApiMessageInterface;

/**
 * @group SprykerSdkTest
 * @group AsyncApi
 * @group AsyncApiTest
 */
class AsyncApiTest extends Unit
{
    /**
     * @return void
     */
    public function testLoadReturnsAsyncApi(): void
    {
        // Arrange
        $asyncApiLoader = new AsyncApiLoader();

        // Act
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/asyncapi/asyncapi.yml'));

        // Assert
        $this->assertInstanceOf(AsyncApiInterface::class, $asyncApi);
    }

    /**
     * @return void
     */
    public function testGetChannelsReturnsIterable(): void
    {
        // Arrange
        $asyncApiLoader = new AsyncApiLoader();

        // Act
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/asyncapi/asyncapi.yml'));

        // Assert
        foreach ($asyncApi->getChannels() as $channelName => $channel) {
            $this->assertIsString($channelName);
            $this->assertInstanceOf(AsyncApiChannelInterface::class, $channel);
        }
    }

    /**
     * @return void
     */
    public function testGetChannelByNameReturnsChannel(): void
    {
        // Arrange
        $asyncApiLoader = new AsyncApiLoader();

        // Act
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/asyncapi/asyncapi.yml'));

        // Assert
        $channel = $asyncApi->getChannel('channelNameA');

        $this->assertSame('channelNameA', $channel->getName());
        $this->assertInstanceOf(AsyncApiChannelInterface::class, $channel);
    }

    /**
     * @return void
     */
    public function testGetPublishMessageByNameFromChannelReturnsMessage(): void
    {
        // Arrange
        $asyncApiLoader = new AsyncApiLoader();

        // Act
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/asyncapi/asyncapi.yml'));

        // Assert
        $channel = $asyncApi->getChannel('channelNameA');
        $publishMessage = $channel->getPublishMessage('IncomingMessage');

        $this->assertSame('IncomingMessage', $publishMessage->getName());
        $this->assertInstanceOf(AsyncApiMessageInterface::class, $publishMessage);
    }

    /**
     * @return void
     */
    public function testGetSubscribeMessageByNameFromChannelReturnsMessage(): void
    {
        // Arrange
        $asyncApiLoader = new AsyncApiLoader();

        // Act
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/asyncapi/asyncapi.yml'));

        // Assert
        $channel = $asyncApi->getChannel('channelNameA');
        $subscribeMessage = $channel->getSubscribeMessage('OutgoingMessage');

        $this->assertSame('OutgoingMessage', $subscribeMessage->getName());
        $this->assertInstanceOf(AsyncApiMessageInterface::class, $subscribeMessage);
    }

    /**
     * @return void
     */
    public function testGetPublishMessagesFromChannelReturnsIterable(): void
    {
        // Arrange
        $asyncApiLoader = new AsyncApiLoader();

        // Act
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/asyncapi/asyncapi.yml'));

        // Assert
        foreach ($asyncApi->getChannels() as $channel) {
            foreach ($channel->getPublishMessages() as $messageName => $message) {
                $this->assertIsString($messageName);
                $this->assertInstanceOf(AsyncApiMessageInterface::class, $message);
            }
        }
    }

    /**
     * @return void
     */
    public function testGetSubscribeMessagesFromChannelReturnsIterable(): void
    {
        // Arrange
        $asyncApiLoader = new AsyncApiLoader();

        // Act
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/asyncapi/asyncapi.yml'));

        // Assert
        foreach ($asyncApi->getChannels() as $channel) {
            foreach ($channel->getSubscribeMessages() as $messageName => $message) {
                $this->assertIsString($messageName);
                $this->assertInstanceOf(AsyncApiMessageInterface::class, $message);
            }
        }
    }

    /**
     * @return void
     */
    public function testGetAttributesFromSubscribeMessagesReturnsIterable(): void
    {
        // Arrange
        $asyncApiLoader = new AsyncApiLoader();

        // Act
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/asyncapi/asyncapi.yml'));

        // Assert
        $channel = $asyncApi->getChannel('channelNameA');
        $subscribeMessage = $channel->getSubscribeMessage('OutgoingMessage');

        foreach ($subscribeMessage->getAttributes() as $attributeName => $attribute) {
            $this->assertIsString($attributeName);
        }
    }

    /**
     * @return void
     */
    public function testGetAttributeFromSubscribeMessagesReturnsIterable(): void
    {
        // Arrange
        $asyncApiLoader = new AsyncApiLoader();

        // Act
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/asyncapi/asyncapi.yml'));

        // Assert
        $channel = $asyncApi->getChannel('channelNameA');
        $subscribeMessage = $channel->getSubscribeMessage('OutgoingMessage');
        $attribute = $subscribeMessage->getAttribute('name');

        $this->assertSame('name', $attribute->getName());
        $this->assertSame('OutgoingMessage', $attribute->getValue());
    }

    /**
     * @return void
     */
    public function testLoadDefinitionFromAnInvalidFileDoesNotThrowAnException(): void
    {
        // Arrange
        $asyncApiLoader = new AsyncApiLoader();

        // Act
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/asyncapi/asyncapi-empty.yml'));

        // Assert
        $this->assertNull($asyncApi->getChannel('channelNameA'), 'Expected that channel does not exists but it exists.');
    }
}
