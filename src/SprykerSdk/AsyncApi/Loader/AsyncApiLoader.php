<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi\Loader;

use SprykerSdk\AsyncApi\AsyncApi;
use SprykerSdk\AsyncApi\AsyncApiInterface;
use SprykerSdk\AsyncApi\Channel\AsyncApiChannel;
use SprykerSdk\AsyncApi\Channel\AsyncApiChannelCollection;
use SprykerSdk\AsyncApi\Channel\AsyncApiChannelCollectionInterface;
use SprykerSdk\AsyncApi\Message\AsyncApiMessage;
use SprykerSdk\AsyncApi\Message\AsyncApiMessageCollection;
use SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttribute;
use SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollection;
use SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Yaml\Yaml;

class AsyncApiLoader implements AsyncApiLoaderInterface
{
    /**
     * @param string $asyncApiPath
     *
     * @return \SprykerSdk\AsyncApi\AsyncApiInterface
     */
    public function load(string $asyncApiPath): AsyncApiInterface
    {
        $asyncApi = Yaml::parseFile($asyncApiPath);
        $asyncApi = $this->resolveReferences($asyncApi);

        return new AsyncApi($this->getChannels($asyncApi));
    }

    /**
     * @param array<string, mixed> $asyncApi
     *
     * @return \SprykerSdk\AsyncApi\Channel\AsyncApiChannelCollectionInterface
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

        $messages = $this->getFromPropertyPath($asyncApi, $propertyPath);

        if ($messages) {
            $messages = $this->formatMessageFromOneOf($messages);

            $channelMessages += $messages;
        }

        return $this->formatMessages($channelMessages);
    }

    /**
     * Due to the logic used to resolve references the messages need additional formatting.
     * - The payload has an unnecessary key with name of the message.
     * - The header has an unnecessary key header.
     *
     * @param array $messages
     *
     * @return array<string, \SprykerSdk\AsyncApi\Message\AsyncApiMessageInterface>
     */
    protected function formatMessages(array $messages): array
    {
        $formattedMessages = [];

        foreach ($messages as $messageName => $message) {
            $message['payload'] = $message['payload'][$messageName];
            $message['headers'] = $message['headers']['headers'];

            $formattedMessages[$messageName] = new AsyncApiMessage($messageName, $this->createMessageAttributesCollection($message));
        }

        return $formattedMessages;
    }

    /**
     * @param array $message
     *
     * @return \SprykerSdk\AsyncApi\Message\Attributes\AsyncApiMessageAttributeCollectionInterface
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
     *
     * @return array<string, array>
     */
    protected function formatMessageFromOneOf(array $messages): array
    {
        $formattedMessages = [];

        foreach ($messages as $message) {
            if (is_array($message)) {
                $formattedMessages[(string)array_key_first($message)] = current(array_values($message));
            }
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

        foreach ($asyncApi['channels'] as $channelName => $channelDefinition) {
            $channelNames[] = $channelName;
        }

        return $channelNames;
    }

    /**
     * @param array $loadedYml
     *
     * @return array
     */
    protected function resolveReferences(array $loadedYml): array
    {
        $arrayWalkRecursive = function (&$array) use ($loadedYml, &$arrayWalkRecursive) {
            foreach ($array as $key => &$value) {
                if (is_array($value)) {
                    $arrayWalkRecursive($value);

                    continue;
                }

                if ($key === '$ref') {
                    $newKey = $this->resolveReferenceKey($value);
                    $resolved = $this->resolveReference($loadedYml, $value);

                    $array[$newKey] = $resolved;
                    unset($array[$key]);
                }
            }
        };

        $arrayWalkRecursive($loadedYml);

        return $loadedYml;
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
     * @param array $loadedYml
     * @param string $reference
     *
     * @return array
     */
    protected function resolveReference(array $loadedYml, string $reference): array
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();

        $propertyPath = ltrim($reference, '#/');
        $propertyPath = str_replace('/', '][', $propertyPath);

        $propertyPath = sprintf('[%s]', $propertyPath);

        return $propertyAccessor->getValue($loadedYml, $propertyPath);
    }
}
