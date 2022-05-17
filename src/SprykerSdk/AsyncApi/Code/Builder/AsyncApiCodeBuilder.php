<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Code\Builder;

use Generated\Shared\Transfer\AsyncApiRequestTransfer;
use Generated\Shared\Transfer\AsyncApiResponseTransfer;
use SprykerSdk\AsyncApi\AsyncApi\AsyncApiInterface;
use SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface;
use SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoaderInterface;
use SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface;
use SprykerSdk\AsyncApi\AsyncApiConfig;
use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdk\AsyncApi\Message\AsyncApiInfo;
use SprykerSdk\AsyncApi\Message\MessageBuilderInterface;
use Symfony\Component\Process\Process;

class AsyncApiCodeBuilder implements AsyncApiCodeBuilderInterface
{
    /**
     * @var \SprykerSdk\AsyncApi\AsyncApiConfig
     */
    protected AsyncApiConfig $config;

    /**
     * @var \SprykerSdk\AsyncApi\Message\MessageBuilderInterface
     */
    protected MessageBuilderInterface $messageBuilder;

    /**
     * @var \SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoaderInterface
     */
    protected AsyncApiLoaderInterface $asyncApiLoader;

    /**
     * @var string
     */
    protected string $sprykMode = 'project';

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApiConfig $config
     * @param \SprykerSdk\AsyncApi\Message\MessageBuilderInterface $messageBuilder
     * @param \SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoaderInterface $asyncApiLoader
     */
    public function __construct(AsyncApiConfig $config, MessageBuilderInterface $messageBuilder, AsyncApiLoaderInterface $asyncApiLoader)
    {
        $this->config = $config;
        $this->messageBuilder = $messageBuilder;
        $this->asyncApiLoader = $asyncApiLoader;
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function build(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer
    {
        $asyncApiResponseTransfer = new AsyncApiResponseTransfer();
        $asyncApi = $this->asyncApiLoader->load($asyncApiRequestTransfer->getTargetFileOrFail());

        $organization = $asyncApiRequestTransfer->getOrganizationOrFail();

        if ($organization === 'Spryker') {
            $this->sprykMode = 'core';
        }

        $asyncApiResponseTransfer = $this->buildCodeForPublishMessagesChannels($asyncApi, $asyncApiResponseTransfer, $organization);
        $asyncApiResponseTransfer = $this->buildCodeForSubscribeMessagesChannels($asyncApi, $asyncApiResponseTransfer, $organization);

        if ($asyncApiResponseTransfer->getErrors()->count() || !$asyncApiResponseTransfer->getMessages()->count()) {
            $asyncApiResponseTransfer->addError($this->messageBuilder->buildMessage(AsyncApiError::couldNotGenerateCodeFromAsyncApi()));
        }

        if ($asyncApiResponseTransfer->getErrors()->count() === 0) {
            $asyncApiResponseTransfer->addMessage($this->messageBuilder->buildMessage(AsyncApiInfo::generatedCodeFromAsyncApiSchema()));
        }

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\AsyncApiInterface $asyncApi
     * @param \Generated\Shared\Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    protected function buildCodeForPublishMessagesChannels(
        AsyncApiInterface $asyncApi,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        foreach ($asyncApi->getChannels() as $channel) {
            $asyncApiResponseTransfer = $this->buildCodeForPublishMessages($channel, $asyncApiResponseTransfer, $projectNamespace);
        }

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\AsyncApiInterface $asyncApi
     * @param \Generated\Shared\Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    protected function buildCodeForSubscribeMessagesChannels(
        AsyncApiInterface $asyncApi,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        foreach ($asyncApi->getChannels() as $channel) {
            $asyncApiResponseTransfer = $this->buildCodeForSubscribeMessages($channel, $asyncApiResponseTransfer, $projectNamespace);
        }

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface $asyncApiChannel
     * @param \Generated\Shared\Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    protected function buildCodeForPublishMessages(
        AsyncApiChannelInterface $asyncApiChannel,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        foreach ($asyncApiChannel->getPublishMessages() as $asyncApiMessage) {
            $asyncApiResponseTransfer = $this->createTransferForMessage($asyncApiMessage, $asyncApiResponseTransfer, $projectNamespace);
            $asyncApiResponseTransfer = $this->createHandlerForMessage($asyncApiMessage, $asyncApiResponseTransfer, $projectNamespace);
        }

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface $asyncApiChannel
     * @param \Generated\Shared\Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    protected function buildCodeForSubscribeMessages(
        AsyncApiChannelInterface $asyncApiChannel,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        foreach ($asyncApiChannel->getSubscribeMessages() as $asyncApiMessage) {
            $asyncApiResponseTransfer = $this->createTransferForMessage($asyncApiMessage, $asyncApiResponseTransfer, $projectNamespace);
        }

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface $asyncApiMessage
     * @param \Generated\Shared\Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    protected function createTransferForMessage(
        AsyncApiMessageInterface $asyncApiMessage,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        $commandLines = [];

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface $operationIdAttribute */
        $operationIdAttribute = $asyncApiMessage->getAttribute('operationId');
        /** @var string $moduleName */
        $moduleName = $operationIdAttribute->getValue();

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $payload */
        $payload = $asyncApiMessage->getAttribute('payload');

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $properties */
        $properties = $payload->getAttribute('properties');

        /** @var string $asyncApiMessageName */
        $asyncApiMessageName = $asyncApiMessage->getName();

        $transferPropertiesToAdd = [];

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $property */
        foreach ($properties->getAttributes() as $propertyName => $property) {
            /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface $typeAttribute */
            $typeAttribute = $property->getAttribute('type');
            /** @var string $type */
            $type = $typeAttribute->getValue();

            $transferPropertiesToAdd[] = sprintf('%s:%s', $propertyName, $type);
            $asyncApiResponseTransfer->addMessage($this->messageBuilder->buildMessage(AsyncApiInfo::addedPropertyWithTypeTo($propertyName, $type, $asyncApiMessageName, $moduleName)));
        }

        $transferBuildCommandLine = [
            $this->config->getSprykRunExecutablePath() . '/vendor/bin/spryk-run',
            'AddSharedTransferProperty',
            '--mode', $this->sprykMode,
            '--organization', $projectNamespace,
            '--module', $moduleName,
            '--name', $asyncApiMessageName,
            '--propertyName', implode(',', $transferPropertiesToAdd),
            '-n',
            '-v',
        ];

        $commandLines[] = $transferBuildCommandLine;

        // Add messageAttributes to the Transfer
        $commandLines[] = [
            $this->config->getSprykRunExecutablePath() . '/vendor/bin/spryk-run',
            'AddSharedTransferProperty',
            '--mode', $this->sprykMode,
            '--organization', $projectNamespace,
            '--module', $moduleName,
            '--name', $asyncApiMessage->getName(),
            '--propertyName', 'messageAttributes',
            '--propertyType', 'MessageAttributes',
            '-n',
            '-v',
        ];

        $asyncApiResponseTransfer->addMessage($this->messageBuilder->buildMessage(AsyncApiInfo::addedPropertyWithTypeTo('messageAttributes', 'MessageAttributesTransfer', $asyncApiMessage->getName(), $moduleName)));

        $commandLines[] = [
            $this->config->getSprykRunExecutablePath() . '/vendor/bin/spryk-run',
            'AddSharedTransferDefinition',
            '--mode', $this->sprykMode,
            '--organization', $projectNamespace,
            '--module', $moduleName,
            '--name', 'MessageAttributes',
            '-n',
            '-v',
        ];

        $asyncApiResponseTransfer->addMessage($this->messageBuilder->buildMessage(AsyncApiInfo::addedTransferDefinitionTo('MessageAttributeTransfer', $moduleName)));

        $this->runCommandLines($commandLines);

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface $asyncApiMessage
     * @param \Generated\Shared\Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    protected function createHandlerForMessage(
        AsyncApiMessageInterface $asyncApiMessage,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        $commandLines = [];
        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface $moduleNameAttribute */
        $moduleNameAttribute = $asyncApiMessage->getAttribute('operationId');
        /** @var string $moduleName */
        $moduleName = $moduleNameAttribute->getValue();

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface $messageNameAttribute */
        $messageNameAttribute = $asyncApiMessage->getAttribute('name');
        /** @var string $messageName */
        $messageName = $messageNameAttribute->getValue();

        $commandLines[] = [
            $this->config->getSprykRunExecutablePath() . '/vendor/bin/spryk-run',
            'AddMessageBrokerHandlerPlugin',
            '--mode', $this->sprykMode,
            '--organization', $projectNamespace,
            '--module', $moduleName,
            '--messageName', $messageName,
            '-n',
            '-v',
        ];

        $asyncApiResponseTransfer->addMessage($this->messageBuilder->buildMessage(AsyncApiInfo::addedMessageHandlerPluginForMessageTo($messageName, $moduleName)));

        $this->runCommandLines($commandLines);

        return $asyncApiResponseTransfer;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param array<array> $commandLines
     *
     * @return void
     */
    protected function runCommandLines(array $commandLines): void
    {
        foreach ($commandLines as $commandLine) {
            $process = new Process($commandLine, $this->config->getProjectRootPath());

            $process->run(function ($a, $buffer) {
                echo $buffer;
                // For debugging purposes, set a breakpoint here to see issues.
            });
        }
    }
}
