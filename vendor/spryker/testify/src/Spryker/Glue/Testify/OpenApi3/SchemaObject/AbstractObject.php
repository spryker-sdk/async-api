<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Property\PropertyValueInterface;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyValues;
use Spryker\Glue\Testify\OpenApi3\SchemaFieldInterface;

abstract class AbstractObject implements ObjectInterface
{
    /**
     * @var \Spryker\Glue\Testify\OpenApi3\Property\PropertyValues|null
     */
    protected $propertyValues;

    /**
     * @inheritDoc
     */
    abstract public function getObjectSpecification(): ObjectSpecification;

    /**
     * @inheritDoc
     */
    public function hydrate($content): SchemaFieldInterface
    {
        if ($content instanceof PropertyValues === false) {
            trigger_error(
                sprintf(
                    'Invalid argument for hydration: expected %s, but %s found',
                    PropertyValueInterface::class,
                    get_class($content),
                ),
                E_USER_WARNING,
            );

            return $this;
        }

        $this->propertyValues = $content;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function export(): ObjectInterface
    {
        return $this;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if ($this->propertyValues && $this->propertyValues->offsetExists($name)) {
            return $this->propertyValues[$name]->getValue()->export();
        }

        if ($this->getObjectSpecification()->offsetExists($name)) {
            $class = $this->getObjectSpecification()->offsetGet($name)->getType();

            return new $class();
        }

        trigger_error(sprintf('Getting unknown property: %s::%s', static::class, $name), E_USER_WARNING);

        return null;
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        trigger_error(sprintf('Trying to mutate readonly object: %s::%s', static::class, $name), E_USER_WARNING);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return $this->propertyValues && $this->propertyValues->offsetExists($name) && $this->propertyValues[$name]->getValue()->export() !== null;
    }
}
