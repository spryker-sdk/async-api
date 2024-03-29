<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Transfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class AsyncApiRequestTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const TARGET_FILE = 'targetFile';

    /**
     * @var string
     */
    public const ORGANIZATION = 'organization';

    /**
     * @var string
     */
    public const ASYNC_API = 'asyncApi';

    /**
     * @var string
     */
    public const VERSION = 'version';

    /**
     * @var string
     */
    public const ASYNC_API_MESSSAGE = 'asyncApiMesssage';

    /**
     * @var string
     */
    public const PAYLOAD_TRANSFER_OBJECT_NAME = 'payloadTransferObjectName';

    /**
     * @var string
     */
    public const PROPERTIES = 'properties';

    /**
     * @var string
     */
    public const MODULE_NAME = 'moduleName';

    /**
     * @var string|null
     */
    protected $targetFile;

    /**
     * @var string|null
     */
    protected $organization;

    /**
     * @var \Transfer\AsyncApiTransfer|null
     */
    protected $asyncApi;

    /**
     * @var string|null
     */
    protected $version;

    /**
     * @var \Transfer\AsyncApiMessageTransfer|null
     */
    protected $asyncApiMesssage;

    /**
     * @var string|null
     */
    protected $payloadTransferObjectName;

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @var string|null
     */
    protected $moduleName;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'target_file' => 'targetFile',
        'targetFile' => 'targetFile',
        'TargetFile' => 'targetFile',
        'organization' => 'organization',
        'Organization' => 'organization',
        'async_api' => 'asyncApi',
        'asyncApi' => 'asyncApi',
        'AsyncApi' => 'asyncApi',
        'version' => 'version',
        'Version' => 'version',
        'async_api_messsage' => 'asyncApiMesssage',
        'asyncApiMesssage' => 'asyncApiMesssage',
        'AsyncApiMesssage' => 'asyncApiMesssage',
        'payload_transfer_object_name' => 'payloadTransferObjectName',
        'payloadTransferObjectName' => 'payloadTransferObjectName',
        'PayloadTransferObjectName' => 'payloadTransferObjectName',
        'properties' => 'properties',
        'Properties' => 'properties',
        'module_name' => 'moduleName',
        'moduleName' => 'moduleName',
        'ModuleName' => 'moduleName',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::TARGET_FILE => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'target_file',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::ORGANIZATION => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'organization',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::ASYNC_API => [
            'type' => 'Transfer\AsyncApiTransfer',
            'type_shim' => null,
            'name_underscore' => 'async_api',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::VERSION => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'version',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::ASYNC_API_MESSSAGE => [
            'type' => 'Transfer\AsyncApiMessageTransfer',
            'type_shim' => null,
            'name_underscore' => 'async_api_messsage',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::PAYLOAD_TRANSFER_OBJECT_NAME => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'payload_transfer_object_name',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::PROPERTIES => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'properties',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
        self::MODULE_NAME => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'operation_id',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
        ],
    ];

    /**
     * @module AsyncApi
     *
     * @param string|null $targetFile
     *
     * @return $this
     */
    public function setTargetFile($targetFile)
    {
        $this->targetFile = $targetFile;
        $this->modifiedProperties[self::TARGET_FILE] = true;

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @return string|null
     */
    public function getTargetFile()
    {
        return $this->targetFile;
    }

    /**
     * @module AsyncApi
     *
     * @param string|null $targetFile
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setTargetFileOrFail($targetFile)
    {
        if ($targetFile === null) {
            $this->throwNullValueException(static::TARGET_FILE);
        }

        return $this->setTargetFile($targetFile);
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getTargetFileOrFail()
    {
        if ($this->targetFile === null) {
            $this->throwNullValueException(static::TARGET_FILE);
        }

        return $this->targetFile;
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function requireTargetFile()
    {
        $this->assertPropertyIsSet(self::TARGET_FILE);

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @param string|null $organization
     *
     * @return $this
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        $this->modifiedProperties[self::ORGANIZATION] = true;

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @return string|null
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @module AsyncApi
     *
     * @param string|null $organization
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setOrganizationOrFail($organization)
    {
        if ($organization === null) {
            $this->throwNullValueException(static::ORGANIZATION);
        }

        return $this->setOrganization($organization);
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getOrganizationOrFail()
    {
        if ($this->organization === null) {
            $this->throwNullValueException(static::ORGANIZATION);
        }

        return $this->organization;
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function requireOrganization()
    {
        $this->assertPropertyIsSet(self::ORGANIZATION);

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @param \Transfer\AsyncApiTransfer|null $asyncApi
     *
     * @return $this
     */
    public function setAsyncApi(AsyncApiTransfer $asyncApi = null)
    {
        $this->asyncApi = $asyncApi;
        $this->modifiedProperties[self::ASYNC_API] = true;

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @return \Transfer\AsyncApiTransfer|null
     */
    public function getAsyncApi()
    {
        return $this->asyncApi;
    }

    /**
     * @module AsyncApi
     *
     * @param \Transfer\AsyncApiTransfer $asyncApi
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setAsyncApiOrFail(AsyncApiTransfer $asyncApi)
    {
        return $this->setAsyncApi($asyncApi);
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return \Transfer\AsyncApiTransfer
     */
    public function getAsyncApiOrFail()
    {
        if ($this->asyncApi === null) {
            $this->throwNullValueException(static::ASYNC_API);
        }

        return $this->asyncApi;
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function requireAsyncApi()
    {
        $this->assertPropertyIsSet(self::ASYNC_API);

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @param string|null $version
     *
     * @return $this
     */
    public function setVersion($version)
    {
        $this->version = $version;
        $this->modifiedProperties[self::VERSION] = true;

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @return string|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @module AsyncApi
     *
     * @param string|null $version
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setVersionOrFail($version)
    {
        if ($version === null) {
            $this->throwNullValueException(static::VERSION);
        }

        return $this->setVersion($version);
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getVersionOrFail()
    {
        if ($this->version === null) {
            $this->throwNullValueException(static::VERSION);
        }

        return $this->version;
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function requireVersion()
    {
        $this->assertPropertyIsSet(self::VERSION);

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @param \Transfer\AsyncApiMessageTransfer|null $asyncApiMesssage
     *
     * @return $this
     */
    public function setAsyncApiMesssage(AsyncApiMessageTransfer $asyncApiMesssage = null)
    {
        $this->asyncApiMesssage = $asyncApiMesssage;
        $this->modifiedProperties[self::ASYNC_API_MESSSAGE] = true;

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @return \Transfer\AsyncApiMessageTransfer|null
     */
    public function getAsyncApiMesssage()
    {
        return $this->asyncApiMesssage;
    }

    /**
     * @module AsyncApi
     *
     * @param \Transfer\AsyncApiMessageTransfer $asyncApiMesssage
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setAsyncApiMesssageOrFail(AsyncApiMessageTransfer $asyncApiMesssage)
    {
        return $this->setAsyncApiMesssage($asyncApiMesssage);
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return \Transfer\AsyncApiMessageTransfer
     */
    public function getAsyncApiMesssageOrFail()
    {
        if ($this->asyncApiMesssage === null) {
            $this->throwNullValueException(static::ASYNC_API_MESSSAGE);
        }

        return $this->asyncApiMesssage;
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function requireAsyncApiMesssage()
    {
        $this->assertPropertyIsSet(self::ASYNC_API_MESSSAGE);

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @param string|null $payloadTransferObjectName
     *
     * @return $this
     */
    public function setPayloadTransferObjectName($payloadTransferObjectName)
    {
        $this->payloadTransferObjectName = $payloadTransferObjectName;
        $this->modifiedProperties[self::PAYLOAD_TRANSFER_OBJECT_NAME] = true;

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @return string|null
     */
    public function getPayloadTransferObjectName()
    {
        return $this->payloadTransferObjectName;
    }

    /**
     * @module AsyncApi
     *
     * @param string|null $payloadTransferObjectName
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function setPayloadTransferObjectNameOrFail($payloadTransferObjectName)
    {
        if ($payloadTransferObjectName === null) {
            $this->throwNullValueException(static::PAYLOAD_TRANSFER_OBJECT_NAME);
        }

        return $this->setPayloadTransferObjectName($payloadTransferObjectName);
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getPayloadTransferObjectNameOrFail()
    {
        if ($this->payloadTransferObjectName === null) {
            $this->throwNullValueException(static::PAYLOAD_TRANSFER_OBJECT_NAME);
        }

        return $this->payloadTransferObjectName;
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function requirePayloadTransferObjectName()
    {
        $this->assertPropertyIsSet(self::PAYLOAD_TRANSFER_OBJECT_NAME);

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @param array|null $properties
     *
     * @return $this
     */
    public function setProperties(array $properties = null)
    {
        if ($properties === null) {
            $properties = [];
        }

        $this->properties = $properties;
        $this->modifiedProperties[self::PROPERTIES] = true;

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @module AsyncApi
     *
     * @param mixed $properties
     *
     * @return $this
     */
    public function addProperties($properties)
    {
        $this->properties[] = $properties;
        $this->modifiedProperties[self::PROPERTIES] = true;

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function requireProperties()
    {
        $this->assertPropertyIsSet(self::PROPERTIES);

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @param string|null $moduleName
     *
     * @return $this
     */
    public function setModuleName($moduleName)
    {
        $this->moduleName = $moduleName;
        $this->modifiedProperties[self::MODULE_NAME] = true;

        return $this;
    }

    /**
     * @module AsyncApi
     *
     * @return string|null
     */
    public function getModuleName()
    {
        return $this->moduleName;
    }

    /**
     * @module AsyncApi
     *
     * @param string|null $moduleName
     *
     * @return $this
     *@throws \Exception
     *
     */
    public function setModuleNameOrFail($moduleName)
    {
        if ($moduleName === null) {
            $this->throwNullValueException(static::MODULE_NAME);
        }

        return $this->setModuleName($moduleName);
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getModuleNameOrFail()
    {
        if ($this->moduleName === null) {
            $this->throwNullValueException(static::MODULE_NAME);
        }

        return $this->moduleName;
    }

    /**
     * @module AsyncApi
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function requireOperationId()
    {
        $this->assertPropertyIsSet(self::MODULE_NAME);

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     * @param bool $ignoreMissingProperty
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            $normalizedPropertyName = $this->transferPropertyNameMap[$property] ?? null;

            switch ($normalizedPropertyName) {
                case 'targetFile':
                case 'organization':
                case 'version':
                case 'payloadTransferObjectName':
                case 'properties':
                case 'moduleName':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'asyncApi':
                case 'asyncApiMesssage':
                    if (is_array($value)) {
                        $type = $this->transferMetadata[$normalizedPropertyName]['type'];
                        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $value */
                        $value = (new $type())->fromArray($value, $ignoreMissingProperty);
                    }

                    if ($value !== null && $this->isPropertyStrict($normalizedPropertyName)) {
                        $this->assertInstanceOfTransfer($normalizedPropertyName, $value);
                    }
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                default:
                    if (!$ignoreMissingProperty) {
                        throw new \InvalidArgumentException(sprintf('Missing property `%s` in `%s`', $property, static::class));
                    }
            }
        }

        return $this;
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function modifiedToArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayRecursiveCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveNotCamelCased();
        }
    }

    /**
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    public function toArray($isRecursive = true, $camelCasedKeys = false): array
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->toArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->toArrayRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->toArrayNotRecursiveNotCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->toArrayNotRecursiveCamelCased();
        }
    }

    /**
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollectionModified($value, $isRecursive, $camelCasedKeys): array
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->modifiedToArray($isRecursive, $camelCasedKeys);

                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollection($value, $isRecursive, $camelCasedKeys): array
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->toArray($isRecursive, $camelCasedKeys);

                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, true);

                continue;
            }
            switch ($property) {
                case 'targetFile':
                case 'organization':
                case 'version':
                case 'payloadTransferObjectName':
                case 'properties':
                case 'moduleName':
                    $values[$arrayKey] = $value;

                    break;
                case 'asyncApi':
                case 'asyncApiMesssage':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, true) : $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveNotCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, false);

                continue;
            }
            switch ($property) {
                case 'targetFile':
                case 'organization':
                case 'version':
                case 'payloadTransferObjectName':
                case 'properties':
                case 'moduleName':
                    $values[$arrayKey] = $value;

                    break;
                case 'asyncApi':
                case 'asyncApiMesssage':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, false) : $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveNotCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveCamelCased(): array
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
     * @return void
     */
    protected function initCollectionProperties(): void
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveCamelCased(): array
    {
        return [
            'targetFile' => $this->targetFile,
            'organization' => $this->organization,
            'version' => $this->version,
            'payloadTransferObjectName' => $this->payloadTransferObjectName,
            'properties' => $this->properties,
            'moduleName' => $this->moduleName,
            'asyncApi' => $this->asyncApi,
            'asyncApiMesssage' => $this->asyncApiMesssage,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'target_file' => $this->targetFile,
            'organization' => $this->organization,
            'version' => $this->version,
            'payload_transfer_object_name' => $this->payloadTransferObjectName,
            'properties' => $this->properties,
            'operation_id' => $this->moduleName,
            'async_api' => $this->asyncApi,
            'async_api_messsage' => $this->asyncApiMesssage,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'target_file' => $this->targetFile instanceof AbstractTransfer ? $this->targetFile->toArray(true, false) : $this->targetFile,
            'organization' => $this->organization instanceof AbstractTransfer ? $this->organization->toArray(true, false) : $this->organization,
            'version' => $this->version instanceof AbstractTransfer ? $this->version->toArray(true, false) : $this->version,
            'payload_transfer_object_name' => $this->payloadTransferObjectName instanceof AbstractTransfer ? $this->payloadTransferObjectName->toArray(true, false) : $this->payloadTransferObjectName,
            'properties' => $this->properties instanceof AbstractTransfer ? $this->properties->toArray(true, false) : $this->properties,
            'operation_id' => $this->moduleName instanceof AbstractTransfer ? $this->moduleName->toArray(true, false) : $this->moduleName,
            'async_api' => $this->asyncApi instanceof AbstractTransfer ? $this->asyncApi->toArray(true, false) : $this->asyncApi,
            'async_api_messsage' => $this->asyncApiMesssage instanceof AbstractTransfer ? $this->asyncApiMesssage->toArray(true, false) : $this->asyncApiMesssage,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'targetFile' => $this->targetFile instanceof AbstractTransfer ? $this->targetFile->toArray(true, true) : $this->targetFile,
            'organization' => $this->organization instanceof AbstractTransfer ? $this->organization->toArray(true, true) : $this->organization,
            'version' => $this->version instanceof AbstractTransfer ? $this->version->toArray(true, true) : $this->version,
            'payloadTransferObjectName' => $this->payloadTransferObjectName instanceof AbstractTransfer ? $this->payloadTransferObjectName->toArray(true, true) : $this->payloadTransferObjectName,
            'properties' => $this->properties instanceof AbstractTransfer ? $this->properties->toArray(true, true) : $this->properties,
            'moduleName' => $this->moduleName instanceof AbstractTransfer ? $this->moduleName->toArray(true, true) : $this->moduleName,
            'asyncApi' => $this->asyncApi instanceof AbstractTransfer ? $this->asyncApi->toArray(true, true) : $this->asyncApi,
            'asyncApiMesssage' => $this->asyncApiMesssage instanceof AbstractTransfer ? $this->asyncApiMesssage->toArray(true, true) : $this->asyncApiMesssage,
        ];
    }
}
