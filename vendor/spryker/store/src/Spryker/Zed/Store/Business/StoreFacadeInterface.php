<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * @method \Spryker\Zed\Store\Business\StoreBusinessFactory getFactory()
 */
interface StoreFacadeInterface
{
    /**
     * Specification:
     *  - Returns currently selected store transfer
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore();

    /**
     * Specification:
     *  - Reads all active stores and returns list of transfers
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getAllStores();

    /**
     * Specification:
     *  - Read store by primary id from database
     *
     * @api
     *
     * @param int $idStore
     *
     * @throws \Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreById($idStore);

    /**
     * Specification:
     *  - Read store by store name
     *
     * @api
     *
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByName($storeName);

    /**
     * Specification:
     *  - Retrieves store by store name
     *
     * @api
     *
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\StoreTransfer|null
     */
    public function findStoreByName(string $storeName): ?StoreTransfer;

    /**
     * Specification:
     *  - Reads all shared store from Store transfer and populates data from configuration.
     *  - The list of stores with which this store shares database, the value is store name.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresWithSharedPersistence(StoreTransfer $storeTransfer);

    /**
     * Specification:
     * - Returns store countries
     *
     * @api
     *
     * @return string[]
     */
    public function getCountries();

    /**
     * Specification:
     * - Validates store transfer in quote
     * - Returns QuoteValidationResponseTransfer.isSuccessful=false if QuoteTransfer.Store does not exist
     * - Returns QuoteValidationResponseTransfer.isSuccessful=false if QuoteTransfer.Store does not have a name
     * - Returns QuoteValidationResponseTransfer.isSuccessful=false if QuoteTransfer.Store has a store that does not exist
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validateQuoteStore(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer;

    /**
     * Specification:
     * - Gets array of StoreTransfer by array of store names.
     *
     * @api
     *
     * @param string[] $storeNames
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoreTransfersByStoreNames(array $storeNames): array;

    /**
     * Specification:
     * - Checks if multi store per Zed is enabled.
     * - Gets the value from module configuration.
     *
     * @api
     *
     * @return bool
     */
    public function isMultiStorePerZedEnabled(): bool;

    /**
     * Specification:
     * - Gets currently selected store transfer.
     * - Fetches all shared stores related to current store transfer.
     * - Returns a list of stores available for current persistence.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getStoresAvailableForCurrentPersistence(): array;
}
