<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui;

use Spryker\Shared\Gui\GuiConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class GuiConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    protected const FORM_RESOURCES_PATH = '/Presentation/Form/Type';

    /**
     * @var array<string>
     */
    protected const FORM_DEFAULT_TEMPLATE_FILE_NAMES = [
        'form_div_layout.html.twig',
        'bootstrap_3_layout.html.twig',
    ];

    /**
     * @var string
     */
    protected const TABS_DEFAULT_TEMPLATE_PATH = '@Gui/Tabs/tabs.twig';

    /**
     * @var string
     */
    protected const SUBMIT_BUTTON_DEFAULT_TEMPLATE_PATH = '@Gui/Form/button/submit_button.twig';

    /**
     * @var string
     */
    protected const MODAL_DEFAULT_TEMPLATE_PATH = '@Gui/Modal/modal.twig';

    /**
     * @var string
     */
    protected const PANEL_DEFAULT_TEMPLATE_PATH = '@Gui/Panel/panel.twig';

    /**
     * @var string
     */
    protected const LIST_GROUP_DEFAULT_TEMPLATE_PATH = '@Gui/ListGroup/list-group.twig';

    /**
     * @var string
     */
    protected const LIST_GROUP_MULTI_DEFAULT_TEMPLATE_PATH = '@Gui/ListGroup/list-group-multidimensional.twig';

    /**
     * @api
     *
     * @return string
     */
    public function getFormResourcesPath(): string
    {
        return __DIR__ . static::FORM_RESOURCES_PATH;
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getTemplatePaths(): array
    {
        return [
            $this->getFormResourcesPath(),
        ];
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getDefaultTemplateFileNames(): array
    {
        return static::FORM_DEFAULT_TEMPLATE_FILE_NAMES;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getZedAssetsPath(): string
    {
        return $this->get(GuiConstants::ZED_ASSETS_BASE_URL, '/assets/');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTabsDefaultTemplatePath(): string
    {
        return static::TABS_DEFAULT_TEMPLATE_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSubmitButtonDefaultTemplatePath(): string
    {
        return static::SUBMIT_BUTTON_DEFAULT_TEMPLATE_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultModalTemplatePath(): string
    {
        return static::MODAL_DEFAULT_TEMPLATE_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultPanelTemplatePath(): string
    {
        return static::PANEL_DEFAULT_TEMPLATE_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultListGroupTemplatePath(): string
    {
        return static::LIST_GROUP_DEFAULT_TEMPLATE_PATH;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getDefaultMultiListGroupTemplatePath(): string
    {
        return static::LIST_GROUP_MULTI_DEFAULT_TEMPLATE_PATH;
    }
}
