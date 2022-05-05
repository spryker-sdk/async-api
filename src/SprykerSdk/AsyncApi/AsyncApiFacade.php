<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi;

use Generated\Shared\Transfer\AsyncApiRequestTransfer;
use Generated\Shared\Transfer\AsyncApiResponseTransfer;
use Generated\Shared\Transfer\ValidateRequestTransfer;
use Generated\Shared\Transfer\ValidateResponseTransfer;

class AsyncApiFacade implements AsyncApiFacadeInterface
{
    /**
     * @var \SprykerSdk\AsyncApi\AsyncApiFactory|null
     */
    protected ?AsyncApiFactory $asyncApiFactory = null;

    /**
     * @param \SprykerSdk\AsyncApi\AsyncApiFactory $asyncApiFactory
     *
     * @return void
     */
    public function setFactory(AsyncApiFactory $asyncApiFactory): void
    {
        $this->asyncApiFactory = $asyncApiFactory;
    }

    /**
     * @return \SprykerSdk\AsyncApi\AsyncApiFactory
     */
    protected function getFactory(): AsyncApiFactory
    {
        if (!$this->asyncApiFactory) {
            $this->asyncApiFactory = new AsyncApiFactory();
        }

        return $this->asyncApiFactory;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function addAsyncApi(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer
    {
        return $this->getFactory()->createAsyncApiBuilder()->addAsyncApi($asyncApiRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function addAsyncApiMessage(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer
    {
        return $this->getFactory()->createAsyncApiBuilder()->addAsyncApiMessage($asyncApiRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AsyncApiResponseTransfer
     */
    public function buildFromAsyncApi(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer
    {
        return $this->getFactory()->createAsyncApiCodeBuilder()->build($asyncApiRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ValidateResponseTransfer
     */
    public function validateAsyncApi(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer
    {
        return $this->getFactory()->createAsyncApiValidator()->validate($validateRequestTransfer);
    }
}
