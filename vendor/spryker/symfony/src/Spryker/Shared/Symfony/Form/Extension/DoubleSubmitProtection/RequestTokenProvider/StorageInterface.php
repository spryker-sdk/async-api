<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider;

/**
 * @deprecated Use {@link \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\StorageInterface} instead.
 */
interface StorageInterface
{
    /**
     * @param string $formName
     *
     * @return string
     */
    public function getToken($formName);

    /**
     * @param string $formName
     *
     * @return void
     */
    public function deleteToken($formName);

    /**
     * @param string $formName
     * @param string $token
     *
     * @return void
     */
    public function setToken($formName, $token);
}
