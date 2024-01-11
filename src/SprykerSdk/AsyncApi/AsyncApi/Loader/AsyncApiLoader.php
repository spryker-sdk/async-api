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

                $formattedMessages[$messageName] = $this->resolveWithResolvedReferences($asyncApi, $reference);
            }
        }

        return $formattedMessages;
    }

    /**
     * @param array $asyncApi
     * @param string $reference
     *
     * @return array<mixed>
     */
    protected function resolveWithResolvedReferences(array $asyncApi, string $reference): array
    {
        $resolved = $this->resolveReference($asyncApi, $reference);

        if (!$resolved) {
            return $this->resolveWithResolvedReferencesFromRemoteApi($asyncApi, $reference);
        }

        return $this->resolveReferences($resolved, $asyncApi);
    }

    /**
     * @param array $asyncApi
     * @param string $reference
     *
     * @return array<mixed>
     */
    protected function resolveWithResolvedReferencesFromRemoteApi(array $asyncApi, string $reference): array
    {
        [$value, $remoteReference] = $this->getReferenceInRootAsyncApi($asyncApi, $reference);

        $key = array_key_first($value);
        $value = $value[$key];

        $valueFragments = explode('#', $value);
        $value = $valueFragments[0];

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            return [];
        }

        if (isset($valueFragments[1])) {
            // Ensure that if the prefix has a trailing slash we only have one at the end.
            $remoteReferencePrefix = rtrim($valueFragments[1], '/') . '/';
            $remoteReference = $remoteReferencePrefix . $remoteReference;
        }

        $remoteReference = sprintf('#/%s', ltrim($remoteReference, '#/'));

        $remoteAsyncApi = $this->getYmlArray($value);

        $resolvedMessage = $this->resolveReference($remoteAsyncApi, $remoteReference);

        return $this->resolveReferences($resolvedMessage, $remoteAsyncApi);
    }

    /**
     * @param array $asyncApi
     * @param string $reference
     *
     * @return array
     */
    protected function getReferenceInRootAsyncApi(array $asyncApi, string $reference): array
    {
        $referenceFragments = explode('/', $reference);
        $referenceFragmentsLength = count($referenceFragments);

        while (true) {
            $currentPosition = --$referenceFragmentsLength;

            $thisReference = array_slice($referenceFragments, 0, $currentPosition);
            $thisReference = implode('/', $thisReference);

            $remoteReference = array_slice($referenceFragments, $currentPosition);
            $remoteReference = implode('/', $remoteReference);

            $propertyPath = $this->getPropertyPath($thisReference);
            $value = $this->getFromPropertyPath($asyncApi, $propertyPath);
            if ($value !== null) {
                return [$value, $remoteReference];
            }
        }
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

            $formattedMessages[$messageName] = $this->resolveWithResolvedReferences($asyncApi, $reference);
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
                $array += $this->resolveWithResolvedReferences($asyncApi, $value);

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
     * @return array<mixed>
     */
    protected function resolveReference(array $asyncApi, string $reference): array
    {
        $propertyPath = $this->getPropertyPath($reference);

        return (array)$this->getFromPropertyPath($asyncApi, $propertyPath);
    }

    /**
     * @param string $reference
     *
     * @return string
     */
    protected function getPropertyPath(string $reference): string
    {
        $propertyPath = ltrim($reference, '#/');
        $propertyPath = str_replace('/', '][', $propertyPath);

        return sprintf('[%s]', $propertyPath);
    }
}
