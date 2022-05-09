<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Code\Builder;

use Generated\Shared\Transfer\AsyncApiMessageTransfer;
use Generated\Shared\Transfer\AsyncApiRequestTransfer;
use Generated\Shared\Transfer\AsyncApiResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use ReflectionClass;
use SprykerSdk\AsyncApi\Exception\InvalidConfigurationException;
use Symfony\Component\Yaml\Yaml;

class AsyncApiBuilder implements AsyncApiBuilderInterface
{
    /**
     * @var array<string>
     */
    protected $transferToAsyncApiTypeMap = [
        'int' => 'integer',
    ];

    /**
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function addAsyncApi(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer
    {
        $asyncApiResponseTransfer = new AsyncApiResponseTransfer();

        $asyncApi = [
            'asyncapi' => '2.2.0',
            'info' => [
                'title' => $asyncApiRequestTransfer->getAsyncApiOrFail()->getTitleOrFail(),
                'version' => $asyncApiRequestTransfer->getAsyncApiOrFail()->getVersionOrFail(),
            ],
        ];

        $targetFilePath = $asyncApiRequestTransfer->getTargetFileOrFail();

        if (is_file($targetFilePath)) {
            $this->updateAsyncApi($targetFilePath, $asyncApi);

            return $asyncApiResponseTransfer;
        }

        $asyncApi = $this->addDefaults($asyncApi);

        $this->writeToFile($targetFilePath, $asyncApi);

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function addAsyncApiMessage(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer
    {
        $asyncApiResponseTransfer = new AsyncApiResponseTransfer();
        $targetFile = $asyncApiRequestTransfer->getTargetFileOrFail();

        if (!file_exists($targetFile)) {
            $asyncApiResponseTransfer->addError((new MessageTransfer())->setMessage(sprintf('File "%s" does not exists. Please create one to continue.', $targetFile)));

            return $asyncApiResponseTransfer;
        }

        $this->validateRequest($asyncApiRequestTransfer);

        $asyncApi = Yaml::parseFile($targetFile);

        $asyncApiMessageTransfer = $asyncApiRequestTransfer->getAsyncApiMesssageOrFail();

        $messageName = $this->getMessageName($asyncApiMessageTransfer, $asyncApiRequestTransfer);

        $asyncApi = $this->addComponentMessage($asyncApi, $messageName, $asyncApiMessageTransfer, $asyncApiRequestTransfer);
        $asyncApi = $this->addComponentSchemaMessage($asyncApi, $messageName, $asyncApiMessageTransfer, $asyncApiRequestTransfer);
        $asyncApi = $this->addMessageToChannel($asyncApi, $messageName, $asyncApiMessageTransfer);
        $asyncApi = $this->addComponentMessageHeader($asyncApi, $messageName, $asyncApiMessageTransfer);

        $asyncApi = $this->addDefaults($asyncApi);

        $this->writeToFile($targetFile, $asyncApi);

        return $asyncApiResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @throws \SprykerSdk\AsyncApi\Exception\InvalidConfigurationException
     *
     * @return void
     */
    protected function validateRequest(AsyncApiRequestTransfer $asyncApiRequestTransfer): void
    {
        if ($this->isPropertyOptionEmpty($asyncApiRequestTransfer) && $this->isTransferOptionEmpty($asyncApiRequestTransfer)) {
            throw new InvalidConfigurationException(
                sprintf('You either need to pass properties with the `-P` option or you need to pass a transfer class name for reverse engineering with the `-t` option.'),
            );
        }

        if (!$this->isPropertyOptionEmpty($asyncApiRequestTransfer) && !$this->isTransferOptionEmpty($asyncApiRequestTransfer)) {
            throw new InvalidConfigurationException(
                sprintf('You can only pass one of the options `-P` or `-t`, not both.'),
            );
        }

        if ($this->isOperationIdEmpty($asyncApiRequestTransfer)) {
            throw new InvalidConfigurationException(
                sprintf('You must pass an operationId to the message with the option `-o`.'),
            );
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return bool
     */
    protected function isPropertyOptionEmpty(AsyncApiRequestTransfer $asyncApiRequestTransfer): bool
    {
        return count($asyncApiRequestTransfer->getProperties()) === 0;
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return bool
     */
    protected function isTransferOptionEmpty(AsyncApiRequestTransfer $asyncApiRequestTransfer): bool
    {
        return $asyncApiRequestTransfer->getPayloadTransferObjectName() === null;
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return bool
     */
    protected function isOperationIdEmpty(AsyncApiRequestTransfer $asyncApiRequestTransfer): bool
    {
        if ($asyncApiRequestTransfer->getOperationId() === null) {
            return true;
        }

        return $asyncApiRequestTransfer->getOperationId() === '';
    }

    /**
     * @param array $asyncApi
     * @param string $messageName
     * @param \Generated\Shared\Transfer\AsyncApiMessageTransfer $asyncApiMessageTransfer
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return array
     */
    protected function addComponentMessage(
        array $asyncApi,
        string $messageName,
        AsyncApiMessageTransfer $asyncApiMessageTransfer,
        AsyncApiRequestTransfer $asyncApiRequestTransfer
    ): array {
        $asyncApi['components']['messages'][$messageName] = [
            'name' => $messageName,
            'operationId' => $asyncApiRequestTransfer->getOperationId() ?? '',
            'summary' => $asyncApiMessageTransfer->getSummary() ?? '',
            'payload' => [
                '$ref' => sprintf('#/components/schemas/%s', $messageName),
            ],
        ];

        return $asyncApi;
    }

    /**
     * @param array $asyncApi
     * @param string $messageName
     * @param \Generated\Shared\Transfer\AsyncApiMessageTransfer $asyncApiMessageTransfer
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return array
     */
    protected function addComponentSchemaMessage(
        array $asyncApi,
        string $messageName,
        AsyncApiMessageTransfer $asyncApiMessageTransfer,
        AsyncApiRequestTransfer $asyncApiRequestTransfer
    ): array {
        $messageAttributes = $this->getMessageAttributes($asyncApiMessageTransfer, $asyncApiRequestTransfer);

        return $this->buildComponentSchemaMessage($asyncApi, $messageAttributes->getProperties(), $messageAttributes->getRequiredProperties(), $messageName);
    }

    /**
     * @param array $asyncApi
     * @param array $messageProperties
     * @param array $requiredFields
     * @param string $messageName
     *
     * @return array
     */
    protected function buildComponentSchemaMessage(array $asyncApi, array $messageProperties, array $requiredFields, string $messageName): array
    {
        $schema = [
            'type' => 'object',
            'properties' => $messageProperties,
        ];

        if ($requiredFields) {
            $schema['required'] = $requiredFields;
        }

        $asyncApi['components']['schemas'][$messageName] = $schema;

        return $asyncApi;
    }

    /**
     * @param array $asyncApi
     * @param string $messageName
     * @param \Generated\Shared\Transfer\AsyncApiMessageTransfer $asyncApiMessageTransfer
     *
     * @return array
     */
    protected function addMessageToChannel(array $asyncApi, string $messageName, AsyncApiMessageTransfer $asyncApiMessageTransfer): array
    {
        $channelName = $asyncApiMessageTransfer->getChannelOrFail()->getNameOrFail();

        if ($asyncApiMessageTransfer->getIsPublish()) {
            $asyncApi = $this->addMessageToChannelType($asyncApi, $messageName, $channelName, 'publish');
        }

        if ($asyncApiMessageTransfer->getIsSubscribe()) {
            $asyncApi = $this->addMessageToChannelType($asyncApi, $messageName, $channelName, 'subscribe');
        }

        return $asyncApi;
    }

    /**
     * @param array $asyncApi
     * @param string $messageName
     * @param string $channelName
     * @param string $channelType
     *
     * @return array
     */
    protected function addMessageToChannelType(array $asyncApi, string $messageName, string $channelName, string $channelType): array
    {
        if (isset($asyncApi['channels'][$channelName][$channelType]['message'])) {
            if (isset($asyncApi['channels'][$channelName][$channelType]['message']['oneOf'])) {
                $asyncApi['channels'][$channelName][$channelType]['message']['oneOf'][] = [
                    '$ref' => sprintf('#/components/messages/%s', $messageName),
                ];

                return $asyncApi;
            }

            $messages = [
                $asyncApi['channels'][$channelName][$channelType]['message'],
            ];

            $messages[] = [
                '$ref' => sprintf('#/components/messages/%s', $messageName),
            ];
            $asyncApi['channels'][$channelName][$channelType]['message'] = [];
            $asyncApi['channels'][$channelName][$channelType]['message']['oneOf'] = $messages;

            return $asyncApi;
        }
        $asyncApi['channels'][$channelName][$channelType]['message'] = [
            '$ref' => sprintf('#/components/messages/%s', $messageName),
        ];

        return $asyncApi;
    }

    /**
     * @param array $asyncApi
     * @param string $messageName
     * @param \Generated\Shared\Transfer\AsyncApiMessageTransfer $asyncApiMessageTransfer
     *
     * @return array
     */
    protected function addComponentMessageHeader(array $asyncApi, string $messageName, AsyncApiMessageTransfer $asyncApiMessageTransfer): array
    {
        if ($asyncApiMessageTransfer->getAddMetadata()) {
            $asyncApi['components']['messages'][$messageName]['headers'] = [
                '$ref' => '#/components/schemas/headers',
            ];
        }

        return $asyncApi;
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function mapTransferTypeToAsyncyApiType(string $type): string
    {
        if (isset($this->transferToAsyncApiTypeMap[$type])) {
            return $this->transferToAsyncApiTypeMap[$type];
        }

        return $type;
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiMessageTransfer $asyncApiMessageTransfer
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return string
     */
    protected function getMessageName(AsyncApiMessageTransfer $asyncApiMessageTransfer, AsyncApiRequestTransfer $asyncApiRequestTransfer): string
    {
        if (is_string($asyncApiMessageTransfer->getName())) {
            return $asyncApiMessageTransfer->getName();
        }

        $transferObjectClassName = $asyncApiRequestTransfer->getPayloadTransferObjectNameOrFail();
        $messageNameFragments = explode('\\', $transferObjectClassName);

        return str_replace('Transfer', '', array_pop($messageNameFragments));
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiMessageTransfer $asyncApiMessageTransfer
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiMessageTransfer
     */
    protected function getMessageAttributes(
        AsyncApiMessageTransfer $asyncApiMessageTransfer,
        AsyncApiRequestTransfer $asyncApiRequestTransfer
    ): AsyncApiMessageTransfer {
        if ($asyncApiRequestTransfer->getProperties()) {
            return $this->formatProperty($asyncApiMessageTransfer, $asyncApiRequestTransfer);
        }

        if ($asyncApiRequestTransfer->getPayloadTransferObjectName()) {
            /** @var class-string<\Generated\Shared\Transfer\AbstractTransfer> $transferObjectClassName */
            $transferObjectClassName = '\\' . ltrim($asyncApiRequestTransfer->getPayloadTransferObjectNameOrFail(), '\\');

            $transferObject = new $transferObjectClassName();
            $transferObjectReflection = new ReflectionClass($transferObjectClassName);
            $transferMetadataProperty = $transferObjectReflection->getProperty('transferMetadata');
            $transferMetadataProperty->setAccessible(true);

            $transferProperties = $transferMetadataProperty->getValue($transferObject);

            $requiredFields = $asyncApiMessageTransfer->getRequiredProperties();
            $messageProperties = $asyncApiMessageTransfer->getProperties();

            foreach ($transferProperties as $propertyName => $propertyDefinition) {
                if ($propertyDefinition['is_transfer']) {
                    continue;
                }

                if ($propertyDefinition['is_value_object']) {
                    continue;
                }

                $messageProperties[$propertyName] = [
                    'type' => $this->mapTransferTypeToAsyncyApiType($propertyDefinition['type']),
                ];

                if (!$propertyDefinition['is_nullable']) {
                    $requiredFields[] = $propertyName;
                }

                $asyncApiMessageTransfer->setRequiredProperties($requiredFields);
                $asyncApiMessageTransfer->setProperties($messageProperties);
            }
        }

        return $asyncApiMessageTransfer;
    }

    /**
     * @param array $asyncApi
     *
     * @return array
     */
    protected function addDefaults(array $asyncApi): array
    {
        if (!isset($asyncApi['components'])) {
            $asyncApi['components'] = [];
        }

        if (!isset($asyncApi['components']['schemas'])) {
            $asyncApi['components']['schemas'] = [];
        }

        $asyncApi['components']['schemas']['headers'] = [
            'type' => 'object',
            'required' => [
                'timestamp',
                'store',
                'correlationId',
            ],
            'properties' => [
                'timestamp' => [
                    'type' => 'integer',
                    'description' => 'Timestamp when this message was created (microtime).',
                ],
                'store' => [
                    'type' => 'string',
                    'description' => 'Store name that triggered the event.',
                ],
                'appIdentifier' => [
                    'type' => 'string',
                    'description' => 'Identifier of the app for the triggered event.',
                ],
                'correlationId' => [
                    'type' => 'string',
                    'description' => 'Identifier of the current process.',
                ],
            ],
        ];

        return $asyncApi;
    }

    /**
     * @param string $targetFile
     * @param array $asyncApi
     *
     * @return void
     */
    protected function writeToFile(string $targetFile, array $asyncApi): void
    {
        $asyncApi = $this->orderAsyncApiElements($asyncApi);

        $asyncApiSchemaYaml = Yaml::dump($asyncApi, 100);

        $dirname = dirname($targetFile);

        if (!is_dir($dirname)) {
            mkdir($dirname, 0770, true);
        }

        file_put_contents($targetFile, $asyncApiSchemaYaml);
    }

    /**
     * @param array $asyncApi
     *
     * @return array
     */
    protected function orderAsyncApiElements(array $asyncApi): array
    {
        $orderedElements = [];

        if (isset($asyncApi['channels'])) {
            $orderedElements['channels'] = $asyncApi['channels'];
            unset($asyncApi['channels']);
        }

        if (isset($asyncApi['components']['schemas'])) {
            $orderedElements['components']['schemas'] = $asyncApi['components']['schemas'];
            unset($asyncApi['components']['schemas']);
        }

        if (isset($asyncApi['components']['messages'])) {
            $orderedElements['components']['messages'] = $asyncApi['components']['messages'];
            unset($asyncApi['components']['messages']);
        }

        if (isset($asyncApi['components'])) {
            unset($asyncApi['components']);
        }

        return array_merge($asyncApi, $orderedElements);
    }

    /**
     * @param \Generated\Shared\Transfer\AsyncApiMessageTransfer $asyncApiMessageTransfer
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiMessageTransfer
     */
    protected function formatProperty(
        AsyncApiMessageTransfer $asyncApiMessageTransfer,
        AsyncApiRequestTransfer $asyncApiRequestTransfer
    ): AsyncApiMessageTransfer {
        $messageProperties = $asyncApiMessageTransfer->getProperties();
        $requiredFields = $asyncApiMessageTransfer->getRequiredProperties();

        foreach ($asyncApiRequestTransfer->getProperties() as $property) {
            $propertyDefinition = explode(':', $property);

            if (count($propertyDefinition) > 1) {
                $messageProperties[$propertyDefinition[0]] = ['type' => $propertyDefinition[1]];
            }

            if (in_array('required', $propertyDefinition)) {
                $requiredFields[] = $propertyDefinition[0];
            }
            $asyncApiMessageTransfer->setRequiredProperties($requiredFields);
            $asyncApiMessageTransfer->setProperties($messageProperties);
        }

        return $asyncApiMessageTransfer;
    }

    /**
     * @param string $targetFile
     * @param array $asyncApi
     *
     * @return void
     */
    protected function updateAsyncApi(string $targetFile, array $asyncApi): void
    {
        $originAsyncApi = Yaml::parse((string)file_get_contents($targetFile));
        $originAsyncApi['info']['version'] = $asyncApi['info']['version'];

        $this->writeToFile($targetFile, $originAsyncApi);
    }
}
