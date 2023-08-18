<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Code\Builder;

use Doctrine\Inflector\InflectorFactory;
use SprykerSdk\AsyncApi\AsyncApi\AsyncApiInterface;
use SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface;
use SprykerSdk\AsyncApi\AsyncApi\Loader\AsyncApiLoaderInterface;
use SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface;
use SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface;
use SprykerSdk\AsyncApi\AsyncApiConfig;
use SprykerSdk\AsyncApi\Exception\InvalidConfigurationException;
use SprykerSdk\AsyncApi\Message\AsyncApiError;
use SprykerSdk\AsyncApi\Message\AsyncApiInfo;
use SprykerSdk\AsyncApi\Message\MessageBuilderInterface;
use Symfony\Component\Process\Process;
use Transfer\AsyncApiRequestTransfer;
use Transfer\AsyncApiResponseTransfer;

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
     * @param \Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Transfer\AsyncApiResponseTransfer
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
            $asyncApiResponseTransfer->addError(
                $this->messageBuilder->buildMessage(
                    AsyncApiError::couldNotGenerateCodeFromAsyncApi($asyncApiRequestTransfer->getTargetFileOrFail()),
                ),
            );
        }

        if ($asyncApiResponseTransfer->getErrors()->count() === 0) {
            $asyncApiResponseTransfer->addMessage($this->messageBuilder->buildMessage(AsyncApiInfo::generatedCodeFromAsyncApiSchema()));
        }

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\AsyncApiInterface $asyncApi
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Transfer\AsyncApiResponseTransfer
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
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Transfer\AsyncApiResponseTransfer
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
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Transfer\AsyncApiResponseTransfer
     */
    protected function buildCodeForPublishMessages(
        AsyncApiChannelInterface $asyncApiChannel,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        foreach ($asyncApiChannel->getPublishMessages() as $asyncApiMessage) {
            $asyncApiResponseTransfer = $this->runAddAsyncApiPublishMessage($asyncApiChannel, $asyncApiMessage, $asyncApiResponseTransfer, $projectNamespace);
        }

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface $asyncApiChannel
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface $asyncApiMessage
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Transfer\AsyncApiResponseTransfer
     */
    protected function runAddAsyncApiPublishMessage(
        AsyncApiChannelInterface $asyncApiChannel,
        AsyncApiMessageInterface $asyncApiMessage,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        $messagesProperties = $this->getMessagesProperties($asyncApiMessage, $asyncApiResponseTransfer);
        $moduleName = $this->getModuleNameForMessage($asyncApiMessage);

        $commandLine = [
            $this->config->getSprykRunExecutablePath() . '/vendor/bin/spryk-run',
            'AddAsyncApiPublishMessage',
            '--mode', $this->sprykMode,
            '--organization', $projectNamespace,
            '--module', $moduleName,
            '--messageName', $asyncApiMessage->getName(),
            '--channelName', $asyncApiChannel->getName(),
            '--messages', implode(';', $messagesProperties),
            '-n',
        ];

        $asyncApiResponseTransfer->addMessage($this->messageBuilder->buildMessage(AsyncApiInfo::generatedCodeFromAsyncApiSchema()));
        $this->runCommandLine($commandLine);

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface $asyncApiChannel
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Transfer\AsyncApiResponseTransfer
     */
    protected function buildCodeForSubscribeMessages(
        AsyncApiChannelInterface $asyncApiChannel,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        foreach ($asyncApiChannel->getSubscribeMessages() as $asyncApiMessage) {
            $asyncApiResponseTransfer = $this->runAddAsyncApiSubscribeMessage($asyncApiChannel, $asyncApiMessage, $asyncApiResponseTransfer, $projectNamespace);
        }

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelInterface $asyncApiChannel
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface $asyncApiMessage
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Transfer\AsyncApiResponseTransfer
     */
    protected function runAddAsyncApiSubscribeMessage(
        AsyncApiChannelInterface $asyncApiChannel,
        AsyncApiMessageInterface $asyncApiMessage,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        $messagesProperties = $this->getMessagesProperties($asyncApiMessage, $asyncApiResponseTransfer);
        $moduleName = $this->getModuleNameForMessage($asyncApiMessage);

        $commandLine = [
            $this->config->getSprykRunExecutablePath() . '/vendor/bin/spryk-run',
            'AddAsyncApiSubscribeMessage',
            '--mode', $this->sprykMode,
            '--organization', $projectNamespace,
            '--module', $moduleName,
            '--messageName', $asyncApiMessage->getName(),
            '--channelName', $asyncApiChannel->getName(),
            '--messages', implode(';', $messagesProperties),
            '-n',
        ];

        $asyncApiResponseTransfer->addMessage($this->messageBuilder->buildMessage(AsyncApiInfo::generatedCodeFromAsyncApiSchema()));
        $this->runCommandLine($commandLine);

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface $asyncApiMessage
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     *
     * @return array
     */
    protected function getMessagesProperties(
        AsyncApiMessageInterface $asyncApiMessage,
        AsyncApiResponseTransfer $asyncApiResponseTransfer
    ): array {
        $messagesProperties = [];

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $payload */
        $payload = $asyncApiMessage->getAttribute('payload');

        /** @var string $asyncApiMessageName */
        $asyncApiMessageName = $asyncApiMessage->getName();

        $messagesProperties = $this->recursiveAddTransferProperty($messagesProperties, $asyncApiResponseTransfer, $asyncApiMessageName, $payload);

        // The last message is the first message and this needs to get the messageAttributes property
        $rootMessage = array_pop($messagesProperties);
        $rootMessage .= ',messageAttributes:MessageAttributes';
        array_push($messagesProperties, $rootMessage);

        return $messagesProperties;
    }

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface $asyncApiMessage
     *
     * @throws \SprykerSdk\AsyncApi\Exception\InvalidConfigurationException
     *
     * @return string
     */
    protected function getModuleNameForMessage(AsyncApiMessageInterface $asyncApiMessage): string
    {
        if (!$asyncApiMessage->getAttribute('x-spryker')) {
            throw new InvalidConfigurationException(AsyncApiError::couldNotFindAnSprykerExtension($asyncApiMessage->getName()));
        }

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $xSprykerAttributeCollection */
        $xSprykerAttributeCollection = $asyncApiMessage->getAttribute('x-spryker');

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|null $moduleNameAttribute */
        $moduleNameAttribute = $xSprykerAttributeCollection->getAttribute('module');

        if (!$moduleNameAttribute) {
            throw new InvalidConfigurationException(AsyncApiError::couldNotFindAModulePropertyInTheSprykerExtension($asyncApiMessage->getName()));
        }

        /** @phpstan-var string */
        return $moduleNameAttribute->getValue();
    }

    /**
     * Recursivly add transfer properties. The most outer transfer is in the payload defined. The payload can have
     * properties which reference another transfer for this inner transfer we also need to add the transfer
     * definition with its properties.
     *
     * @param array<array<string>|string> $messagesProperties
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $messageName
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $attributeCollection
     *
     * @return array<array<string>|string>
     */
    protected function recursiveAddTransferProperty(
        array $messagesProperties,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $messageName,
        AsyncApiMessageAttributeCollectionInterface $attributeCollection
    ): array {
        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $properties */
        $properties = $attributeCollection->getAttribute('properties');
        $propertiesForCurrentMessage = [];

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $property */
        foreach ($properties->getAttributes() as $propertyName => $property) {
            $propertyNameSingular = $this->getSingularized($propertyName);

            /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface $typeAttribute */
            $typeAttribute = $property->getAttribute('type');
            /** @var string $type */
            $type = $typeAttribute->getValue();

            if ($type === 'array') {
                /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|null $typeOfAttribute */
                $typeOfAttribute = $property->getAttribute('typeOf');

                if ($typeOfAttribute) {
                    /** @var string $innerMessageName */
                    $innerMessageName = $typeOfAttribute->getValue();
                    $type = sprintf('%s[]', $innerMessageName);

                    /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $itemCollection */
                    $itemCollection = $property->getAttribute('items');

                    $messagesProperties = $this->recursiveAddTransferProperty($messagesProperties, $asyncApiResponseTransfer, $innerMessageName, $itemCollection);
                }
            }

            $propertiesForCurrentMessage[] = ($propertyNameSingular) ? sprintf('%s:%s:%s', $propertyName, $type, $propertyNameSingular) : sprintf('%s:%s', $propertyName, $type);
        }

        $messagesProperties[] = sprintf('%s#%s', $messageName, implode(',', $propertiesForCurrentMessage));

        return $messagesProperties;
    }

    /**
     * @param string $propertyName
     *
     * @return string|null
     */
    protected function getSingularized(string $propertyName): ?string
    {
        $inflector = InflectorFactory::create()->build();
        $singularized = $inflector->singularize($propertyName);

        return ($singularized !== $propertyName) ? $singularized : null;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param array<int, string> $commandLine
     *
     * @return void
     */
    protected function runCommandLine(array $commandLine): void
    {
        $process = new Process($commandLine, $this->config->getProjectRootPath());

        $process->run(function ($a, $buffer) {
            echo $buffer;
            // For debugging purposes, set a breakpoint here to see issues.
        });
    }
}
