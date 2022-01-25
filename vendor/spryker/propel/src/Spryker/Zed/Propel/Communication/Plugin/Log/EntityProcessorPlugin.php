<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Plugin\Log;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Propel\Business\PropelFacadeInterface getFacade()
 * @method \Spryker\Zed\Propel\Communication\PropelCommunicationFactory getFactory()
 * @method \Spryker\Zed\Propel\PropelConfig getConfig()
 */
class EntityProcessorPlugin extends AbstractPlugin implements LogProcessorPluginInterface
{
    /**
     * @var string
     */
    public const EXTRA = 'entity';

    /**
     * @var string
     */
    public const CONTEXT_KEY = 'entity';

    /**
     * @var string
     */
    public const RECORD_CONTEXT = 'context';

    /**
     * @var string
     */
    public const RECORD_EXTRA = 'extra';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        $entity = $this->findEntity((array)$record[static::RECORD_CONTEXT]);
        if (!($entity instanceof ActiveRecordInterface)) {
            return $record;
        }

        $contextData = $entity->toArray();
        $contextData['class'] = get_class($entity);
        $sanitizedData = $this->getFactory()->getLogFacade()->sanitize($contextData);

        $record[static::RECORD_EXTRA][static::EXTRA] = $sanitizedData;

        return $record;
    }

    /**
     * @param array $context
     *
     * @return \Propel\Runtime\ActiveRecord\ActiveRecordInterface|null
     */
    protected function findEntity(array $context)
    {
        if (!empty($context[static::CONTEXT_KEY])) {
            return $context[static::CONTEXT_KEY];
        }

        if (current($context) instanceof ActiveRecordInterface) {
            return current($context);
        }

        return null;
    }
}
