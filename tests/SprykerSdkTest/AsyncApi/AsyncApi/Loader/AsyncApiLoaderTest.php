<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\AsyncApi\AsyncApi\Loader;

use Codeception\Test\Unit;
use SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoader;
use SprykerSdkTest\AsyncApi\AsyncApiTester;

/**
 * @group SprykerSdkTest
 * @group AsyncApi
 * @group AsyncApi
 * @group Loader
 * @group AsyncApiLoaderTest
 */
class AsyncApiLoaderTest extends Unit
{
    /**
     * @var \SprykerSdkTest\AsyncApi\AsyncApiTester
     */
    protected AsyncApiTester $tester;

    /**
     * @return void
     */
    public function testLoadResolvesReferencesToTransfersInPayload(): void
    {
        // Arrange
        $asyncApiLoader = new AsyncApiLoader();

        // Act
        $asyncApi = $asyncApiLoader->load(codecept_data_dir('api/valid/transfer_references_transfer.yml'));

        $channel = $asyncApi->getChannel('channel');
        $publishMessages = iterator_to_array($channel->getPublishMessages());
        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface $publishMessage */
        $publishMessage = $publishMessages['Message'];

        $payload = $publishMessage->getAttribute('payload');
        $payloadProperties = $payload->getAttribute('properties');
        $items = $payloadProperties->getAttribute('items')->getAttribute('items')->getAttribute('properties');

        // Assert
        $this->assertSame('string', $items->getAttribute('foo')->getAttribute('type')->getValue());
        $this->assertSame('string', $items->getAttribute('bar')->getAttribute('type')->getValue());
    }
}
