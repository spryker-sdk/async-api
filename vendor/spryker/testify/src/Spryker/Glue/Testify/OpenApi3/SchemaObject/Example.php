<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3\SchemaObject;

use Spryker\Glue\Testify\OpenApi3\Primitive\Any;
use Spryker\Glue\Testify\OpenApi3\Primitive\StringPrimitive;
use Spryker\Glue\Testify\OpenApi3\Property\PropertyDefinition;
use Spryker\Glue\Testify\OpenApi3\Reference\ReferableInterface;

/**
 * @property-read string $summary
 * @property-read string $description
 * @property-read mixed $value
 * @property-read string $externalValue
 */
class Example extends AbstractObject implements ReferableInterface
{
    /**
     * @inheritDoc
     */
    public function getObjectSpecification(): ObjectSpecification
    {
        return (new ObjectSpecification())
            ->setProperty('summary', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('description', new PropertyDefinition(StringPrimitive::class))
            ->setProperty('value', new PropertyDefinition(Any::class))
            ->setProperty('externalValue', new PropertyDefinition(StringPrimitive::class));
    }
}
