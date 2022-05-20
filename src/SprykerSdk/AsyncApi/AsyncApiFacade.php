<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\AsyncApi;

use Transfer\AsyncApiRequestTransfer;
use Transfer\AsyncApiResponseTransfer;
use Transfer\ValidateRequestTransfer;
use Transfer\ValidateResponseTransfer;

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
     * @param \Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Transfer\AsyncApiResponseTransfer
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
     * @param \Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Transfer\AsyncApiResponseTransfer
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
     * @param \Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Transfer\AsyncApiResponseTransfer
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
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validateAsyncApi(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer
    {
        return $this->getFactory()->createAsyncApiValidator()->validate($validateRequestTransfer);
    }
}
