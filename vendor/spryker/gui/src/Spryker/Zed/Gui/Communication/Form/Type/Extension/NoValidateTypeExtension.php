<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Form\Type\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class NoValidateTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritDoc}
     *
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['attr'] = array_merge($view->vars['attr'], [
            'novalidate' => 'novalidate',
        ]);
    }

    /**
     * {@inheritDoc}
     *
     * @return string
     */
    public function getExtendedType()
    {
        return Form::class;
    }

    /**
     * {@inheritDoc}
     *
     * @phpstan-return array<class-string<\Symfony\Component\Form\Form>>
     *
     * @return array<string>
     */
    public static function getExtendedTypes(): iterable
    {
        return [Form::class];
    }
}
