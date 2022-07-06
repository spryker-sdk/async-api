<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Helper;

use Codeception\Module;
use Codeception\Stub;
use Codeception\Stub\Expected;
use Codeception\TestInterface;
use org\bovigo\vfs\vfsStream;
use SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoader;
use SprykerSdk\AsyncApi\AsyncApiConfig;
use SprykerSdk\AsyncApi\AsyncApiFacade;
use SprykerSdk\AsyncApi\AsyncApiFacadeInterface;
use SprykerSdk\AsyncApi\AsyncApiFactory;
use SprykerSdk\AsyncApi\Code\Builder\AsyncApiCodeBuilder;
use SprykerSdk\AsyncApi\Console\CodeGenerateConsole;
use SprykerSdk\AsyncApi\Message\MessageBuilder;
use Symfony\Component\Yaml\Yaml;
use Transfer\AsyncApiChannelTransfer;
use Transfer\AsyncApiMessageTransfer;
use Transfer\AsyncApiRequestTransfer;
use Transfer\AsyncApiResponseTransfer;
use Transfer\AsyncApiTransfer;

class AsyncApiHelper extends Module
{
    /**
     * @var string|null
     */
    protected ?string $rootPath = null;

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _before(TestInterface $test): void
    {
        parent::_before($test);

        // Ensure we are always using the virtual filesystem even if none of the have* methods was called.
        $this->rootPath = vfsStream::setup('root')->url();
    }

    /**
     * @param \Codeception\TestInterface $test
     *
     * @return void
     */
    public function _after(TestInterface $test): void
    {
        $this->rootPath = null;
    }

    /**
     * @return \SprykerSdk\AsyncApi\AsyncApiFacadeInterface
     */
    public function getFacade(): AsyncApiFacadeInterface
    {
        return new AsyncApiFacade();
    }

    /**
     * @return \SprykerSdk\AsyncApi\AsyncApiConfig
     */
    public function getConfig(): AsyncApiConfig
    {
        if ($this->rootPath === null) { // This will always be set, check if a test fails as it expects the normal filesystem
            return new AsyncApiConfig();
        }

        return Stub::make(AsyncApiConfig::class, [
            'getProjectRootPath' => function () {
                return $this->rootPath;
            },
        ]);
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }

    /**
     * This will ensure that the AopSdkConfig::getProjectRootPath() will return the passed path.
     *
     * @param string $rootPath
     *
     * @return void
     */
    public function mockRoot(string $rootPath): void
    {
        $this->rootPath = $rootPath;
    }

    /**
     * Sets up an expected directory structure in the virtual filesystem
     *
     * @param array $structure
     *
     * @return void
     */
    public function mockDirectoryStructure(array $structure): void
    {
        // Set up the virtual filesystem structure
        $root = vfsStream::setup('root', null, $structure);
        $this->mockRoot($root->url());
    }

    /**
     * @var string
     */
    public const CHANNEL_NAME = 'foo/bar';

    /**
     * @return \Transfer\AsyncApiRequestTransfer
     */
    public function haveAsyncApiAddRequest(): AsyncApiRequestTransfer
    {
        $asyncApiTransfer = new AsyncApiTransfer();
        $asyncApiTransfer
            ->setTitle('Test title')
            ->setVersion('0.1.0');

        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer
            ->setTargetFile($this->getConfig()->getDefaultAsyncApiFile())
            ->setAsyncApi($asyncApiTransfer);

        return $asyncApiRequestTransfer;
    }

    /**
     * We assume that an AsyncApi file with version 0.1.0 exists when `\SprykerSdkTest\Helper\AsyncApiHelper::haveAsyncApiFile()`
     * was called before `\SprykerSdk\Zed\AopSdk\Business\AopSdkFacadeInterface::addAsyncApi()` is executed.
     *
     * @return \Transfer\AsyncApiRequestTransfer
     */
    public function haveAsyncApiUpdateVersionRequest(): AsyncApiRequestTransfer
    {
        $asyncApiTransfer = new AsyncApiTransfer();
        $asyncApiTransfer
            ->setTitle('Test title')
            ->setVersion('1.0.0');

        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer
            ->setTargetFile($this->getConfig()->getDefaultAsyncApiFile())
            ->setAsyncApi($asyncApiTransfer);

        return $asyncApiRequestTransfer;
    }

    /**
     * @return \Transfer\AsyncApiRequestTransfer
     */
    public function haveAsyncApiAddRequestWithExistingAsyncApi(): AsyncApiRequestTransfer
    {
        $this->haveAsyncApiFile();

        $asyncApiTransfer = new AsyncApiTransfer();
        $asyncApiTransfer
            ->setTitle('Test title')
            ->setVersion('1.1.0');

        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer
            ->setTargetFile($this->getConfig()->getDefaultAsyncApiFile())
            ->setAsyncApi($asyncApiTransfer);

        return $asyncApiRequestTransfer;
    }

    /**
     * @return \Transfer\AsyncApiRequestTransfer
     */
    public function haveAsyncApiAddRequestWithExistingAsyncApiAndPayloadTransferObject(): AsyncApiRequestTransfer
    {
        $asyncApiRequestTransfer = $this->haveAsyncApiAddRequestWithExistingAsyncApi();
        $asyncApiRequestTransfer
            ->setPayloadTransferObjectName(AsyncApiMessageTransfer::class)
            ->setOperationId('operationId');

        return $asyncApiRequestTransfer;
    }

    /**
     * This simulates a CLI command execution where properties are set in the AsyncApiRequestTransfer.
     *
     * @param array|null $properties
     *
     * @return \Transfer\AsyncApiRequestTransfer
     */
    public function haveAsyncApiAddRequestWithExistingAsyncApiAndProperties(?array $properties = null): AsyncApiRequestTransfer
    {
        $asyncApiRequestTransfer = $this->haveAsyncApiAddRequestWithExistingAsyncApi();
        $asyncApiRequestTransfer
            ->setProperties($properties ?? ['firstName:string:required', 'lastName:string', 'phoneNumber:int:required', 'email:string'])
            ->setOperationId('operationId');

        return $asyncApiRequestTransfer;
    }

    /**
     * return void
     *
     * @return void
     */
    public function haveAsyncApiFile(): void
    {
        $this->prepareAsyncApiFile(codecept_data_dir('api/valid/base_asyncapi.schema.yml'));
    }

    /**
     * @param string $pathToAsyncApi
     *
     * @return void
     */
    protected function prepareAsyncApiFile(string $pathToAsyncApi): void
    {
        $filePath = sprintf('%s/resources/api/asyncapi.yml', $this->getRootPath());

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0770, true);
        }

        file_put_contents($filePath, file_get_contents($pathToAsyncApi));
    }

    /**
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     *
     * @return array
     */
    public function getMessagesFromAsyncApiResponseTransfer(AsyncApiResponseTransfer $asyncApiResponseTransfer): array
    {
        $messages = [];

        foreach ($asyncApiResponseTransfer->getErrors() as $messageTransfer) {
            $messages[] = $messageTransfer->getMessage();
        }

        return $messages;
    }

    /**
     * @param string $targetFile
     * @param string $messageName
     * @param string $channelName
     *
     * @return void
     */
    public function assertAsyncApiHasPublishMessageInChannel(string $targetFile, string $messageName, string $channelName): void
    {
        $asyncApi = Yaml::parseFile($targetFile);
        $channelType = 'publish';

        $this->assertMessageInChannelType($asyncApi, $messageName, $channelName, $channelType);
    }

    /**
     * @param string $targetFile
     * @param string $messageName
     * @param string $channelName
     *
     * @return void
     */
    public function assertAsyncApiHasSubscribeMessageInChannel(string $targetFile, string $messageName, string $channelName): void
    {
        $asyncApi = Yaml::parseFile($targetFile);
        $channelType = 'subscribe';

        $this->assertMessageInChannelType($asyncApi, $messageName, $channelName, $channelType);
    }

    /**
     * @param string $targetFile
     * @param string $expectedVersion
     *
     * @return void
     */
    public function assertAsyncApiVersionIsUpdated(string $targetFile, string $expectedVersion): void
    {
        $asyncApi = Yaml::parseFile($targetFile);
        $message = sprintf('Expected to have version "%s" but got "%s".', $expectedVersion, $asyncApi['info']['version']);

        $this->assertSame($asyncApi['info']['version'], $expectedVersion, $message);
    }

    /**
     * @param array $asyncApi
     * @param string $messageName
     * @param string $channelName
     * @param string $channelType
     *
     * @return void
     */
    protected function assertMessageInChannelType(array $asyncApi, string $messageName, string $channelName, string $channelType): void
    {
        $this->assertChannelType($asyncApi, $channelName, $channelType);

        $expectedMessageReference = $this->getExpectedMessageReference($asyncApi, $messageName, $channelName, $channelType);

        $this->assertNotNull($expectedMessageReference, sprintf(
            'Expected to have a "%s" message "%s" in the channel "%s" but it does not exist.',
            $channelType,
            $messageName,
            $channelName,
        ));
    }

    /**
     * @param array $asyncApi
     * @param string $messageName
     * @param string $channelName
     * @param string $channelType
     *
     * @return string|null
     */
    protected function getExpectedMessageReference(array $asyncApi, string $messageName, string $channelName, string $channelType): ?string
    {
        $messages = $asyncApi['channels'][$channelName][$channelType]['message'];

        $expectedMessageReference = sprintf('#/components/messages/%s', $messageName);

        foreach ($messages as $key => $messageReference) {
            if ($key === 'oneOf') {
                return $this->getExpectedMessageReferenceFromOneOf($messageReference, $expectedMessageReference);
            }

            if ($messageReference === $expectedMessageReference) {
                return $messageReference;
            }
        }

        return null;
    }

    /**
     * @param array $messages
     * @param string $expectedMessageReference
     *
     * @return string|null
     */
    protected function getExpectedMessageReferenceFromOneOf(array $messages, string $expectedMessageReference): ?string
    {
        foreach ($messages as $message) {
            if ($message['$ref'] === $expectedMessageReference) {
                return $message['$ref'];
            }
        }

        return null;
    }

    /**
     * @param array $asyncApi
     * @param string $channelName
     * @param string $channelType
     *
     * @return void
     */
    protected function assertChannelType(array $asyncApi, string $channelName, string $channelType): void
    {
        $this->assertIsArray($asyncApi['channels'][$channelName], sprintf(
            'Expected to have a channel "%s" but it does not exist.',
            $channelName,
        ));

        $this->assertIsArray($asyncApi['channels'][$channelName][$channelType], sprintf(
            'Expected to have a "%s" element in the channel "%s" but it does not exist.',
            $channelType,
            $channelName,
        ));

        $this->assertIsArray($asyncApi['channels'][$channelName][$channelType]['message'], sprintf(
            'Expected to have a "message" element in the channel "%s" but it does not exist.',
            $channelName,
        ));
    }

    /**
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     *
     * @return void
     */
    public function assertAsyncApiResponseHasNoErrors(AsyncApiResponseTransfer $asyncApiResponseTransfer): void
    {
        $this->assertCount(0, $asyncApiResponseTransfer->getErrors(), sprintf(
            'Expected that no errors given but there are errors. Errors: "%s"',
            implode(', ', $this->getMessagesFromAsyncApiResponseTransfer($asyncApiResponseTransfer)),
        ));
    }

    /**
     * @param string|null $messageName When $messageName is passed the message will not have a PayloadTransferObjectName.
     *
     * @return \Transfer\AsyncApiMessageTransfer
     */
    public function havePublishMessageWithMetadata(?string $messageName = null): AsyncApiMessageTransfer
    {
        return $this->createMessage(true, 'publish', $messageName);
    }

    /**
     * @param string|null $messageName When $messageName is passed the message will not have a PayloadTransferObjectName.
     *
     * @return \Transfer\AsyncApiMessageTransfer
     */
    public function haveSubscribeMessageWithMetadata(?string $messageName = null): AsyncApiMessageTransfer
    {
        return $this->createMessage(true, 'subscribe', $messageName);
    }

    /**
     * @param bool $withMetadata
     * @param string $channelType
     * @param string|null $messageName
     *
     * @return \Transfer\AsyncApiMessageTransfer
     */
    protected function createMessage(bool $withMetadata, string $channelType, ?string $messageName = null): AsyncApiMessageTransfer
    {
        $asyncApiChannelTransfer = new AsyncApiChannelTransfer();
        $asyncApiChannelTransfer->setName(static::CHANNEL_NAME);

        $asyncApiMessageTransfer = new AsyncApiMessageTransfer();
        $asyncApiMessageTransfer
            ->setName($messageName)
            ->setChannel($asyncApiChannelTransfer)
            ->setAddMetadata($withMetadata);

        $asyncApiRequestTransfer = new AsyncApiRequestTransfer();
        $asyncApiRequestTransfer->setAsyncApiMesssage($asyncApiMessageTransfer);

        if (!$messageName) {
            // When no explicit messageName is given use a transfer object to create an AsyncAPI message from
            // This is used to test reverse engineer from a given TransferObject.
            $asyncApiRequestTransfer->setPayloadTransferObjectName(AsyncApiMessageTransfer::class);
        }

        if ($channelType === 'publish') {
            $asyncApiMessageTransfer->setIsPublish(true);
        }
        if ($channelType === 'subscribe') {
            $asyncApiMessageTransfer->setIsSubscribe(true);
        }

        return $asyncApiMessageTransfer;
    }

    /**
     * @return \SprykerSdk\AsyncApi\Console\CodeGenerateConsole
     */
    public function getAsyncApiBuilderConsoleMock(): CodeGenerateConsole
    {
        $asyncApiCodeBuilderStub = Stub::construct(
            AsyncApiCodeBuilder::class,
            [
                new AsyncApiConfig(),
                new MessageBuilder(),
                new AsyncApiLoader(),
            ],
            [
                'runCommandLines' => Expected::atLeastOnce(),
            ],
        );

        $factoryMock = Stub::make(AsyncApiFactory::class, [
            'createAsyncApiCodeBuilder' => $asyncApiCodeBuilderStub,
        ]);
        $facade = new AsyncApiFacade();
        $facade->setFactory($factoryMock);

        $buildFromAsyncApiConsole = new CodeGenerateConsole();
        $buildFromAsyncApiConsole->setFacade($facade);

        return $buildFromAsyncApiConsole;
    }

    /**
     * @param string $targetFile
     * @param string $channelName
     * @param string $channelType
     * @param string $messageName
     * @param array $property
     *
     * @return void
     */
    public function assertMessageInChannelHasProperty(string $targetFile, string $channelName, string $channelType, string $messageName, array $property): void
    {
        $asyncApi = Yaml::parseFile($targetFile);

        $this->assertMessageInChannelType($asyncApi, $messageName, $channelName, $channelType);

        $this->assertArrayHasKey($property[0], $asyncApi['components']['schemas'][$messageName]['properties'], sprintf(
            'Expected to have a property "%s" in the message "%s" but it does not exist.',
            $property[0],
            $messageName,
        ));

        $this->assertSame($asyncApi['components']['schemas'][$messageName]['properties'][$property[0]]['type'], $property[1], sprintf(
            'Expected to have a property "%s" with type "%s" in the message "%s" but it does not exist.',
            $property[0],
            $property[1],
            $messageName,
        ));

        if ($property[2]) {
            $this->assertTrue(in_array($property[0], $asyncApi['components']['schemas'][$messageName]['required']), sprintf(
                'Expected that property "%s" is required but it was not found in "required".',
                $property[0],
                $messageName,
            ));
        }
    }

    /**
     * @param string $targetFile
     * @param string $channelName
     * @param string $channelType
     * @param string $messageName
     *
     * @return void
     */
    public function assertMessageInChannelHasOperationId(string $targetFile, string $channelName, string $channelType, string $messageName): void
    {
        $asyncApi = Yaml::parseFile($targetFile);

        $this->assertMessageInChannelType($asyncApi, $messageName, $channelName, $channelType);

        $this->assertArrayHasKey('operationId', $asyncApi['components']['messages'][$messageName], sprintf(
            'Expected to have a operationId in the message "%s" but it does not exist.',
            $messageName,
        ));
    }

    /**
     * @param array $asyncApi
     * @param string $messageName
     * @param string $channelName
     * @param string $channelType
     *
     * @return void
     */
    public function assertMessageExistsOnlyOnceInChannel(array $asyncApi, string $messageName, string $channelName, string $channelType): void
    {
        $this->assertMessageInChannelType($asyncApi, $messageName, $channelName, $channelType);

        $messages = $asyncApi['channels'][$channelName][$channelType]['message'];

        if (!isset($messages['oneOf'])) {
            return;
        }

        $expectedMessageReference = sprintf('#/components/messages/%s', $messageName);

        $count = 0;

        foreach ($messages['oneOf'] as $message) {
            if ($message['$ref'] === $expectedMessageReference) {
                $count++;
            }
        }

        $this->assertEquals(1, $count, sprintf(
            'Expected to have only one message "%s" in channel "%s"',
            $messageName,
            $channelName,
        ));
    }
}
