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

interface AsyncApiFacadeInterface
{
    /**
     * Specification:
     * - Adds an AsyncAPI file.
     *
     * @api
     *
     * @param \Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Transfer\AsyncApiResponseTransfer
     */
    public function addAsyncApi(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer;

    /**
     * Specification:
     * - Adds an AsyncAPI message to a given file.
     * - When the file does not exist, it will raise an error.
     *
     * @api
     *
     * @param \Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Transfer\AsyncApiResponseTransfer
     */
    public function addAsyncApiMessage(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer;

    /**
     * Specification:
     * - Reads an AsyncAPI file and builds code that is required.
     *
     * @api
     *
     * @param \Transfer\AsyncApiRequestTransfer $asyncApiRequestTransfer
     *
     * @return \Transfer\AsyncApiResponseTransfer
     */
    public function buildFromAsyncApi(AsyncApiRequestTransfer $asyncApiRequestTransfer): AsyncApiResponseTransfer;

    /**
     * Specification:
     * - Reads an AsyncAPI file and validates it.
     * - Validates that an AsyncAPI file contains at least one message.
     * - Validates that an AsyncAPI file does not contain duplicated messages.
     * - Validates that all messages in the AsyncAPI file have an operationId.
     *
     * @api
     *
     * @param \Transfer\ValidateRequestTransfer $validateRequestTransfer
     *
     * @return \Transfer\ValidateResponseTransfer
     */
    public function validateAsyncApi(ValidateRequestTransfer $validateRequestTransfer): ValidateResponseTransfer;
}
