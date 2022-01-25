<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel;

use Exception;
use InvalidArgumentException;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Kernel\Locale\LocaleNotFoundException;

class Store
{
    /**
     * @var string
     */
    public const APPLICATION_ZED = 'ZED';

    /**
     * @var static|null
     */
    protected static $instance;

    /**
     * @var string
     */
    protected $storeName;

    /**
     * List of all storeNames
     *
     * @var array<string>
     */
    protected $allStoreNames;

    /**
     * @var array
     */
    protected $allStores;

    /**
     * List of locales
     *
     * E.g: "de" => "de_DE"
     *
     * @var array<string>
     */
    protected $locales;

    /**
     * List of countries
     *
     * @var array<string>
     */
    protected $countries;

    /**
     * Examples: DE, PL
     *
     * @var string
     */
    protected $currentCountry;

    /**
     * Examples: de_DE, pl_PL
     *
     * @var string
     */
    protected $currentLocale;

    /**
     * @var array
     */
    protected $contexts;

    /**
     * @var string
     */
    protected static $defaultStore;

    /**
     * Cache for data from stores.php to not open it too many times.
     *
     * @var array
     */
    protected $stores;

    /**
     * Examples: EUR, PLN
     *
     * @link http://en.wikipedia.org/wiki/ISO_4217
     *
     * @var string
     */
    protected $currencyIsoCode;

    /**
     * @var array<string>
     */
    protected $currencyIsoCodes = [];

    /**
     * @var array Keys are queue pool names, values are lists of queue connection names.
     */
    protected $queuePools = [];

    /**
     * @var array<string>
     */
    protected $storesWithSharedPersistence = [];

    /**
     * @var bool
     */
    protected static $isDynamicStoreMode;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return bool
     */
    public static function isDynamicStoreMode(): bool
    {
        if (static::$isDynamicStoreMode === null) {
            static::$isDynamicStoreMode = !file_exists(APPLICATION_ROOT_DIR . '/config/Shared/stores.php');
        }

        return static::$isDynamicStoreMode;
    }

    /**
     * @return string
     */
    public static function getDefaultStore()
    {
        if (static::$defaultStore === null) {
            static::$defaultStore = require APPLICATION_ROOT_DIR . '/config/Shared/default_store.php';
        }

        return static::$defaultStore;
    }

    protected function __construct()
    {
        $currentStoreName = APPLICATION_STORE;
        $this->initializeSetup($currentStoreName);
        $this->publish();
    }

    /**
     * @return void
     */
    protected function publish()
    {
    }

    /**
     * @param string $currentStoreName
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function getStoreSetup($currentStoreName)
    {
        $stores = $this->getStores();

        if (array_key_exists($currentStoreName, $stores) === false) {
            throw new Exception('Missing setup for store: ' . $currentStoreName);
        }

        return $stores;
    }

    /**
     * @return array
     */
    protected function getStores(): array
    {
        if ($this->stores === null) {
            $this->stores = require APPLICATION_ROOT_DIR . '/config/Shared/stores.php';
        }

        return $this->stores;
    }

    /**
     * @param string $currentStoreName
     *
     * @throws \Exception
     *
     * @return void
     */
    public function initializeSetup($currentStoreName)
    {
        $stores = $this->getStoreSetup($currentStoreName);
        $storeArray = $stores[$currentStoreName];
        $vars = get_object_vars($this);
        foreach ($storeArray as $k => $v) {
            if (!array_key_exists($k, $vars)) {
                throw new Exception('Unknown setup-key: ' . $k);
            }
            $this->$k = $v;
        }

        $this->storeName = $currentStoreName;
        $this->allStoreNames = array_keys($stores);
        $this->allStores = $stores;

        $this->setCurrencyIsoCode($this->getDefaultCurrencyCode());
        $this->setCurrentLocale((string)current($this->locales));
        $this->setCurrentCountry((string)current($this->countries));
    }

    /**
     * @throws \Spryker\Shared\Kernel\Locale\LocaleNotFoundException
     *
     * @return string
     */
    public function getCurrentLocale()
    {
        if ($this->currentLocale === null) {
            throw new LocaleNotFoundException('Locale is not defined.');
        }

        return $this->currentLocale;
    }

    /**
     * @param string $locale string The locale, e.g. 'de_DE'
     *
     * @throws \InvalidArgumentException
     *
     * @return string The language, e.g. 'de'
     */
    protected function getLanguageFromLocale($locale)
    {
        $position = strpos($locale, '_');
        if ($position === false) {
            throw new InvalidArgumentException(sprintf('Invalid format for locale `%s`, expected `xx_YY`.', $locale));
        }

        return substr($locale, 0, $position);
    }

    /**
     * @return string
     */
    public function getCurrentLanguage()
    {
        return $this->getLanguageFromLocale($this->currentLocale);
    }

    /**
     * @return array<string>
     */
    public function getAllowedStores()
    {
        return $this->allStoreNames;
    }

    /**
     * @return array<string>
     */
    public function getInactiveStores()
    {
        $inActiveStores = [];
        foreach ($this->getAllowedStores() as $store) {
            if ($this->storeName !== $store) {
                $inActiveStores[] = $store;
            }
        }

        return $inActiveStores;
    }

    /**
     * @return array<string>
     */
    public function getLocales()
    {
        return $this->locales;
    }

    /**
     * @param string $storeName
     *
     * @return array<string>
     */
    public function getLocalesPerStore($storeName)
    {
        if (!array_key_exists($storeName, $this->allStores)) {
            return [];
        }

        return $this->allStores[$storeName]['locales'];
    }

    /**
     * @return string
     */
    public function getStoreName()
    {
        return $this->storeName;
    }

    /**
     * @param string $storeName
     *
     * @return $this
     */
    public function setStoreName($storeName)
    {
        $this->storeName = $storeName;

        return $this;
    }

    /**
     * @param string $currentLocale
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function setCurrentLocale($currentLocale)
    {
        if (!in_array($currentLocale, $this->locales)) {
            throw new InvalidArgumentException(sprintf('"%s" locale is not a valid value. Please use one of "%s".', $currentLocale, implode('", "', $this->locales)));
        }

        $this->currentLocale = $currentLocale;
    }

    /**
     * @return array
     */
    public function getContexts()
    {
        return $this->contexts;
    }

    /**
     * @return array<string>
     */
    public function getCountries()
    {
        return $this->countries;
    }

    /**
     * @param string $currentCountry
     *
     * @return void
     */
    public function setCurrentCountry($currentCountry)
    {
        $this->currentCountry = $currentCountry;
    }

    /**
     * @return string
     */
    public function getCurrentCountry()
    {
        return $this->currentCountry;
    }

    /**
     * @return string
     */
    public function getStorePrefix()
    {
        $prefix = Config::get(KernelConstants::STORE_PREFIX, '');
        $prefix .= $this->getStoreName();

        return $prefix;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function setCurrencyIsoCode($currencyIsoCode)
    {
        if (count($this->currencyIsoCodes) === 0) {
            $this->currencyIsoCode = $currencyIsoCode;

            return;
        }

        if (!in_array($currencyIsoCode, $this->currencyIsoCodes)) {
            throw new InvalidArgumentException(
                sprintf(
                    '"%s" currency is not a valid value. Please use one of "%s".',
                    $currencyIsoCode,
                    implode('", "', $this->currencyIsoCodes),
                ),
            );
        }

        $this->currencyIsoCode = $currencyIsoCode;
    }

    /**
     * @return array<string>
     */
    public function getCurrencyIsoCodes()
    {
        return $this->currencyIsoCodes;
    }

    /**
     * @return string
     */
    public function getCurrencyIsoCode()
    {
        return $this->currencyIsoCode;
    }

    /**
     * @param string $storeName
     *
     * @return array
     */
    public function getConfigurationForStore($storeName)
    {
        return $this->getStoreSetup($storeName)[$storeName];
    }

    /**
     * @return string
     */
    public function getDefaultCurrencyCode()
    {
        $defaultCurrencyCode = current($this->currencyIsoCodes);
        if (!$defaultCurrencyCode) {
            return $this->currencyIsoCode;
        }

        return $defaultCurrencyCode;
    }

    /**
     * @return array
     */
    public function getQueuePools()
    {
        return $this->queuePools;
    }

    /**
     * @return array<string>
     */
    public function getStoresWithSharedPersistence()
    {
        return $this->storesWithSharedPersistence;
    }
}
