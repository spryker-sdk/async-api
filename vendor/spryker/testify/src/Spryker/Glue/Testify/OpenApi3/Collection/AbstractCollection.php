<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\Collection;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface;
use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

abstract class AbstractCollection implements CollectionInterface, IteratorAggregate, ArrayAccess, Countable
{
    /**
     * @var array<\Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface>
     */
    protected $elements = [];

    /**
     * @inheritDoc
     */
    abstract public function getElementDefinition(): PropertyDefinition;

    /**
     * @inheritDoc
     */
    public function hydrate($content): SchemaFieldInterface
    {
        $this->elements = [];

        foreach ((array)$content as $key => $element) {
            if ($element instanceof PropertyValueInterface === false) {
                trigger_error(
                    sprintf(
                        'Invalid argument for hydration: expected %s, but %s found',
                        PropertyValueInterface::class,
                        get_class($element),
                    ),
                    E_USER_WARNING,
                );

                continue;
            }

            $this->elements[$key] = $element;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function export(): CollectionInterface
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        $result = [];

        foreach ($this as $key => $value) {
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @return \Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator(array_map(function (PropertyValueInterface $element) {
            return $element->getValue()->export();
        }, $this->elements));
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->elements);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            trigger_error(sprintf('Accessing non-existing offset: %s::%s', static::class, $offset), E_USER_WARNING);

            $class = $this->getElementDefinition()->getType();

            return new $class();
        }

        return $this->elements[$offset]->getValue()->export();
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        trigger_error(sprintf('Trying to set readonly property: %s::%s', static::class, $offset), E_USER_WARNING);
    }

    /**
     * @param mixed $offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        trigger_error(sprintf('Trying to unset readonly property: %s::%s', static::class, $offset), E_USER_WARNING);
    }

    /**
     * @return array<\Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface>
     */
    public function __debugInfo()
    {
        return $this->elements;
    }
}
