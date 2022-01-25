<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\Table;

use Spryker\Zed\Gui\Communication\Plugin\Twig\Buttons\AbstractButtonTwig;

/**
 * @method \Spryker\Zed\Gui\GuiConfig getConfig()
 * @method \Spryker\Zed\Gui\Communication\GuiCommunicationFactory getFactory()
 */
class CreateTableButtonTwigPlugin extends AbstractButtonTwig
{
    /**
     * @return string
     */
    protected function getFunctionName(): string
    {
        return 'createTableButton';
    }

    /**
     * @return string
     */
    protected function getButtonClass(): string
    {
        return 'btn-create';
    }

    /**
     * @return string
     */
    protected function getIcon(): string
    {
        return '<i class="fa fa-plus"></i> ';
    }

    /**
     * @return string
     */
    protected function getButtonDefaultClass(): string
    {
        return 'btn-xs btn-outline';
    }
}
