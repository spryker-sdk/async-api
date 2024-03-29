<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\AsyncApi\Loader;

use SprykerSdk\AsyncApi\AsyncApi\AsyncApi;
use SprykerSdk\AsyncApi\AsyncApi\AsyncApiInterface;
use SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannel;
use SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelCollection;
use SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelCollectionInterface;
use SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessage;
use SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageCollection;
use SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttribute;
use SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollection;
use SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;

class AsyncApiLoader implements AsyncApiLoaderInterface
{
    /**
     * @param string $asyncApiPath
     *
     * @return \SprykerSdk\AsyncApi\AsyncApi\AsyncApiInterface
     */
    public function load(string $asyncApiPath): AsyncApiInterface
    {
        $asyncApi = $this->getYmlArray($asyncApiPath);

        return new AsyncApi($this->getChannels($asyncApi));
    }

    /**
     * @param string $asyncApiPath
     *
     * @return array
     */
    protected function getYmlArray(string $asyncApiPath): array
    {
        if (filter_var($asyncApiPath, FILTER_VALIDATE_URL)) {
            return Yaml::parse((string)file_get_contents($asyncApiPath));
        }

        return Yaml::parseFile($asyncApiPath);
    }

    /**
     * @param array<string, mixed> $asyncApi
     *
     * @return \SprykerSdk\AsyncApi\AsyncApi\Channel\AsyncApiChannelCollectionInterface
     */
    protected function getChannels(array $asyncApi): AsyncApiChannelCollectionInterface
    {
        $channels = [];

        foreach ($this->getChannelNames($asyncApi) as $channelName) {
            $channels[$channelName] = new AsyncApiChannel(
                $channelName,
                new AsyncApiMessageCollection($this->getMessagesByTypeFromChannel($asyncApi, 'publish', $channelName)),
                new AsyncApiMessageCollection($this->getMessagesByTypeFromChannel($asyncApi, 'subscribe', $channelName)),
            );
        }

        return new AsyncApiChannelCollection($channels);
    }

    /**
     * @param array<string, mixed> $asyncApi
     * @param string $type
     * @param string $channelName
     *
     * @return array
     */
    protected function getMessagesByTypeFromChannel(array $asyncApi, string $type, string $channelName): array
    {
        $channelMessages = [];

        $propertyPath = sprintf('[channels][%s][%s][message][oneOf]', $channelName, $type);
        $oneOfMessages = $this->getFromPropertyPath($asyncApi, $propertyPath);

        if ($oneOfMessages) {
            $channelMessages += $this->formatMessageFromOneOf($oneOfMessages, $asyncApi);

            return $this->createAsyncApiMessages($channelMessages);
        }

        $propertyPath = sprintf('[channels][%s][%s][message]', $channelName, $type);
        $singleMessages = $this->getFromPropertyPath($asyncApi, $propertyPath);

        if ($singleMessages) {
            $channelMessages += $this->formatMessages($singleMessages, $asyncApi);
        }

        return $this->createAsyncApiMessages($channelMessages);
    }

    /**
     * Due to the logic used to resolve references the messages need additional formatting.
     * - The payload has an unnecessary key with name of the message.
     * - The header has an unnecessary key header.
     *
     * @param array $messages
     *
     * @return array<string, \SprykerSdk\AsyncApi\AsyncApi\Message\AsyncApiMessageInterface>
     */
    protected function createAsyncApiMessages(array $messages): array
    {
        $formattedMessages = [];

        foreach ($messages as $messageName => $message) {
            $formattedMessages[$messageName] = new AsyncApiMessage($messageName, $this->createMessageAttributesCollection($message));
        }

        return $formattedMessages;
    }

    /**
     * @param array $message
     *
     * @return \SprykerSdk\AsyncApi\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface
     */
    protected function createMessageAttributesCollection(array $message): AsyncApiMessageAttributeCollectionInterface
    {
        $attributes = [];

        foreach ($message as $key => $value) {
            if (is_array($value)) {
                $attributes[$key] = $this->createMessageAttributesCollection($value);

                continue;
            }

            $attributes[$key] = new AsyncApiMessageAttribute($key, $value);
        }

        return new AsyncApiMessageAttributeCollection($attributes);
    }

    /**
     * @param array $asyncApi
     * @param string $propertyPath
     *
     * @return array|null
     */
    protected function getFromPropertyPath(array $asyncApi, string $propertyPath): ?array
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        return $propertyAccessor->getValue($asyncApi, $propertyPath);
    }

    /**
     * @param array<string, array> $messages
     * @param array<string, mixed> $asyncApi
     *
     * @return array<string, array>
     */
    protected function formatMessageFromOneOf(array $messages, array $asyncApi): array
    {
        $formattedMessages = [];

        foreach ($messages as $message) {
            foreach ($message as $reference) {
                $messageName = $this->resolveReferenceKey($reference);
                $resolvedMessage = $this->resolveReference($asyncApi, $reference);

                $messageWithResolvedReferences = $this->resolveReferences($resolvedMessage, $asyncApi);
                $formattedMessages[$messageName] = $messageWithResolvedReferences;
            }
        }

        return $formattedMessages;
    }

    /**
     * @param array<string> $messages
     * @param array<string, mixed> $asyncApi
     *
     * @return array<string, array>
     */
    protected function formatMessages(array $messages, array $asyncApi): array
    {
        $formattedMessages = [];

        foreach ($messages as $reference) {
            $messageName = $this->resolveReferenceKey($reference);
            $resolvedMessage = $this->resolveReference($asyncApi, $reference);
            $messageWithResolvedReferences = $this->resolveReferences($resolvedMessage, $asyncApi);

            $formattedMessages[$messageName] = $messageWithResolvedReferences;
        }

        return $formattedMessages;
    }

    /**
     * @param array<string, array> $asyncApi
     *
     * @return array<string>
     */
    protected function getChannelNames(array $asyncApi): array
    {
        $channelNames = [];

        if (!isset($asyncApi['channels'])) {
            return $channelNames;
        }

        foreach ($asyncApi['channels'] as $channelName => $channelDefinition) {
            $channelNames[] = $channelName;
        }

        return $channelNames;
    }

    /**
     * @param array<string, mixed> $array
     * @param array<string, mixed> $asyncApi
     *
     * @return array<string, mixed>
     */
    protected function resolveReferences(array $array, array $asyncApi): array
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = $this->resolveReferences($value, $asyncApi);
                if (isset($array['type']) && $array['type'] === 'array' && isset($value['$ref'])) {
                    $schemaParts = explode('/', $value['$ref']);
                    $array['typeOf'] = array_pop($schemaParts);
                }
            }

            if ($key === '$ref') {
                $resolved = $this->resolveReference($asyncApi, $value);
                $resolved = $this->resolveReferences($resolved, $asyncApi);
                $array += $resolved;

                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function resolveReferenceKey(string $key): string
    {
        $key = ltrim($key, '#/');
        $keyFragment = explode('/', $key);

        return array_pop($keyFragment);
    }

    /**
     * @param array<string, mixed> $asyncApi
     * @param string $reference
     *
     * @return array<string, mixed>
     */
    protected function resolveReference(array $asyncApi, string $reference): array
    {
        // This is Spryker internal. This file contains header information you can reference in your schema file.
        if (strpos($reference, '#/components/schemas/message-broker') !== false) {
            return $this->resolveRemoteReference($asyncApi, '#/components/schemas/message-broker', '#/components/schemas/headers');
        }

        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $propertyPath = ltrim($reference, '#/');
        $propertyPath = str_replace('/', '][', $propertyPath);

        $propertyPath = sprintf('[%s]', $propertyPath);

        return $propertyAccessor->getValue($asyncApi, $propertyPath);
    }

    /**
     * @param array<string, mixed> $asyncApi
     * @param string $reference
     * @param string $remoteReference
     *
     * @return array<string, mixed>
     */
    protected function resolveRemoteReference(array $asyncApi, string $reference, string $remoteReference): array
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $propertyPath = ltrim($reference, '#/');
        $propertyPath = str_replace('/', '][', $propertyPath);

        $propertyPath = sprintf('[%s]', $propertyPath);
        $value = $propertyAccessor->getValue($asyncApi, $propertyPath);

        return (!is_array($value)) ? [] : $this->resolveFromUrl($value, $remoteReference);
    }

    /**
     * @param array $value
     * @param string $remoteReference
     *
     * @return array<mixed>
     */
    protected function resolveFromUrl(array $value, string $remoteReference): array
    {
        $key = array_key_first($value);
        $value = $value[$key];

        return (!filter_var($value, FILTER_VALIDATE_URL)) ? [] : $this->resolveReference($this->getYmlArray($value), $remoteReference);
    }
}
