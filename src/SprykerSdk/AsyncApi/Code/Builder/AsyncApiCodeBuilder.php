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
            $asyncApiResponseTransfer = $this->createTransferForMessage($asyncApiMessage, $asyncApiResponseTransfer, $projectNamespace);
            $asyncApiResponseTransfer = $this->createHandlerForMessage($asyncApiMessage, $asyncApiResponseTransfer, $projectNamespace);
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
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Transfer\AsyncApiResponseTransfer
     */
    protected function createTransferForMessage(
        AsyncApiMessageInterface $asyncApiMessage,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        $commandLines = [];

        $moduleName = $this->getModuleNameForMessage($asyncApiMessage);

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $payload */
        $payload = $asyncApiMessage->getAttribute('payload');

        /** @var string $asyncApiMessageName */
        $asyncApiMessageName = $asyncApiMessage->getName();

        $commandLines = $this->recursiveAddTransferPropertyAddCommandLines($commandLines, $asyncApiResponseTransfer, $asyncApiMessageName, $payload, $projectNamespace, $moduleName);

        // Add MessageAttribute to Transfer definition
        $commandLines = $this->addTransferPropertyCommandLine($commandLines, $projectNamespace, $moduleName, $asyncApiMessageName, 'messageAttributes', 'MessageAttribute');

        $asyncApiResponseTransfer->addMessage($this->messageBuilder->buildMessage(AsyncApiInfo::addedPropertyWithTypeTo('messageAttributes', 'MessageAttributesTransfer', $asyncApiMessageName, $moduleName)));

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
     *
     * @throws \SprykerSdk\AsyncApi\Exception\InvalidConfigurationException
     *
     * @return string
     */
    protected function getModuleNameForMessage(AsyncApiMessageInterface $asyncApiMessage): string
    {
        if (!$asyncApiMessage->getAttribute('x-spryker')) {
            throw new InvalidConfigurationException(sprintf('Could not find an `x-spryker` extension. Please add one to your schema file for the "%s" message.', $asyncApiMessage->getName()));
        }

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $xSprykerAttributeCollection */
        $xSprykerAttributeCollection = $asyncApiMessage->getAttribute('x-spryker');

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeInterface|null $moduleNameAttribute */
        $moduleNameAttribute = $xSprykerAttributeCollection->getAttribute('module');

        if (!$moduleNameAttribute) {
            throw new InvalidConfigurationException(sprintf('Could not find a `module` name property in the `x-spryker` extension. Please add one to your schema file for the "%s" message.', $asyncApiMessage->getName()));
        }

        /** @phpstan-var string */
        return $moduleNameAttribute->getValue();
    }

    /**
     * Recursivly add transfer properties. The most outer transfer is in the payload defined. The payload can have
     * properties which reference another transfer for this inner transfer we also need to add the transfer
     * definition with its properties.
     *
     * @param array<array<string>> $commandLines
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $messageName
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $attributeCollection
     * @param string $projectNamespace
     * @param string $moduleName
     *
     * @return array<array<string>>
     */
    protected function recursiveAddTransferPropertyAddCommandLines(
        array $commandLines,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $messageName,
        AsyncApiMessageAttributeCollectionInterface $attributeCollection,
        string $projectNamespace,
        string $moduleName
    ): array {
        $transferPropertiesToAdd = [];

        /** @var \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface $properties */
        $properties = $attributeCollection->getAttribute('properties');

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

                    $commandLines = $this->recursiveAddTransferPropertyAddCommandLines($commandLines, $asyncApiResponseTransfer, $innerMessageName, $itemCollection, $projectNamespace, $moduleName);
                }
            }

            $transferPropertiesToAdd[] = ($propertyNameSingular) ? sprintf('%s:%s:%s', $propertyName, $type, $propertyNameSingular) : sprintf('%s:%s', $propertyName, $type);
            $asyncApiResponseTransfer->addMessage($this->messageBuilder->buildMessage(AsyncApiInfo::addedPropertyWithTypeTo($propertyName, $type, $messageName, $moduleName)));
        }

        return $this->addTransferPropertyCommandLine($commandLines, $projectNamespace, $moduleName, $messageName, implode(',', $transferPropertiesToAdd));
    }

    /**
     * @param array<array<string>> $commandLines
     * @param string $projectNamespace
     * @param string $moduleName
     * @param string $messageName
     * @param string $propertyName
     * @param string|null $propertyType
     *
     * @return array<array<string>>
     */
    protected function addTransferPropertyCommandLine(
        array $commandLines,
        string $projectNamespace,
        string $moduleName,
        string $messageName,
        string $propertyName,
        ?string $propertyType = null
    ): array {
        $propertyCommandLine = [
            $this->config->getSprykRunExecutablePath() . '/vendor/bin/spryk-run',
            'AddSharedTransferProperty',
            '--mode', $this->sprykMode,
            '--organization', $projectNamespace,
            '--module', $moduleName,
            '--name', $messageName,
            '--propertyName', $propertyName,
        ];

        if ($propertyType) {
            $propertyCommandLine[] = '--propertyType';
            $propertyCommandLine[] = $propertyType;
        }

        $propertyCommandLine[] = '-n'; // No interaction
        $propertyCommandLine[] = '-v'; // verbose mode

        $commandLines[] = $propertyCommandLine;

        return $commandLines;
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
     * @param \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface $asyncApiMessage
     * @param \Transfer\AsyncApiResponseTransfer $asyncApiResponseTransfer
     * @param string $projectNamespace
     *
     * @return \Transfer\AsyncApiResponseTransfer
     */
    protected function createHandlerForMessage(
        AsyncApiMessageInterface $asyncApiMessage,
        AsyncApiResponseTransfer $asyncApiResponseTransfer,
        string $projectNamespace
    ): AsyncApiResponseTransfer {
        $commandLines = [];

        $moduleName = $this->getModuleNameForMessage($asyncApiMessage);

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
