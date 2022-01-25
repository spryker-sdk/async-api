<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Kernel\Transfer\Fixtures;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer as ParentAbstractTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

class AbstractTransfer extends ParentAbstractTransfer
{
    /**
     * @var string
     */
    public const STRING = 'string';

    /**
     * @var string
     */
    public const INT = 'int';

    /**
     * @var string
     */
    public const BOOL = 'bool';

    /**
     * @var string
     */
    public const ARRAY_PROPERTY = 'array';

    /**
     * @var string
     */
    public const TRANSFER = 'transfer';

    /**
     * @var string
     */
    public const TRANSFER_COLLECTION = 'transferCollection';

    /**
     * @var string
     */
    protected $string;

    /**
     * @var int
     */
    protected $int;

    /**
     * @var bool
     */
    protected $bool;

    /**
     * @var array
     */
    protected $array = [];

    /**
     * @var \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected $transfer;

    /**
     * @var \ArrayObject<int, \Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    protected $transferCollection;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'string' => 'string',
        'int' => 'int',
        'bool' => 'bool',
        'array' => 'array',
        'transfer' => 'transfer',
        'transfer_collection' => 'transferCollection',
        'transferCollection' => 'transferCollection',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::STRING => [
            'type' => 'string',
            'name_underscore' => 'string',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::INT => [
            'type' => 'int',
            'name_underscore' => 'int',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::BOOL => [
            'type' => 'bool',
            'name_underscore' => 'bool',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::ARRAY_PROPERTY => [
            'type' => 'array',
            'name_underscore' => 'array',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::TRANSFER => [
            'type' => 'SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer',
            'name_underscore' => 'transfer',
            'is_collection' => false,
            'is_transfer' => true,
        ],
        self::TRANSFER_COLLECTION => [
            'type' => 'SprykerTest\Shared\Kernel\Transfer\Fixtures\AbstractTransfer',
            'name_underscore' => 'transfer_collection',
            'is_collection' => true,
            'is_transfer' => true,
        ],
    ];

    /**
     * @param string $string
     *
     * @return $this
     */
    public function setString(string $string)
    {
        $this->string = $string;
        $this->modifiedProperties[static::STRING] = true;

        return $this;
    }

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @return $this
     */
    public function requireString()
    {
        $this->assertPropertyIsSet(static::STRING);

        return $this;
    }

    /**
     * @param int $int
     *
     * @return $this
     */
    public function setInt(int $int)
    {
        $this->int = $int;
        $this->modifiedProperties[static::INT] = true;

        return $this;
    }

    /**
     * @return int
     */
    public function getInt(): int
    {
        return $this->int;
    }

    /**
     * @return $this
     */
    public function requireInt()
    {
        $this->assertPropertyIsSet(static::INT);

        return $this;
    }

    /**
     * @param bool $bool
     *
     * @return $this
     */
    public function setBool(bool $bool)
    {
        $this->bool = $bool;
        $this->modifiedProperties[static::BOOL] = true;

        return $this;
    }

    /**
     * @return bool
     */
    public function getBool(): bool
    {
        return $this->bool;
    }

    /**
     * @return $this
     */
    public function requireBool()
    {
        $this->assertPropertyIsSet(static::BOOL);

        return $this;
    }

    /**
     * @param array $array
     *
     * @return $this
     */
    public function setArray(array $array = [])
    {
        $this->array = $array;
        $this->modifiedProperties[static::ARRAY_PROPERTY] = true;

        return $this;
    }

    /**
     * @return array
     */
    public function getArray(): array
    {
        return $this->array;
    }

    /**
     * @param array $arr
     *
     * @return $this
     */
    public function addArr(array $arr)
    {
        $this->array[] = $arr;
        $this->modifiedProperties[static::ARRAY_PROPERTY] = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function requireArr()
    {
        $this->assertCollectionPropertyIsSet(static::ARRAY_PROPERTY);

        return $this;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $transfer
     *
     * @return $this
     */
    public function setTransfer(?TransferInterface $transfer = null)
    {
        $this->transfer = $transfer;
        $this->modifiedProperties[static::TRANSFER] = true;

        return $this;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function getTransfer(): TransferInterface
    {
        return $this->transfer;
    }

    /**
     * @return $this
     */
    public function requireTransfer()
    {
        $this->assertPropertyIsSet(static::TRANSFER);

        return $this;
    }

    /**
     * @param \ArrayObject<int, \Spryker\Shared\Kernel\Transfer\TransferInterface> $transferCollection
     *
     * @return $this
     */
    public function setTransferCollection(ArrayObject $transferCollection)
    {
        $this->transferCollection = $transferCollection;
        $this->modifiedProperties[static::TRANSFER_COLLECTION] = true;

        return $this;
    }

    /**
     * @return \ArrayObject<int, \Spryker\Shared\Kernel\Transfer\TransferInterface>
     */
    public function getTransferCollection()
    {
        return $this->transferCollection;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $transferCollection
     *
     * @return $this
     */
    public function addTransferCollection(TransferInterface $transferCollection)
    {
        $this->transferCollection[] = $transferCollection;
        $this->modifiedProperties[static::TRANSFER_COLLECTION] = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function requireTransferCollection()
    {
        $this->assertCollectionPropertyIsSet(static::TRANSFER_COLLECTION);

        return $this;
    }
}
