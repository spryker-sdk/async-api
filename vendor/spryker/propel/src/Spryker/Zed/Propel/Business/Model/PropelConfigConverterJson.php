<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business\Model;

use Spryker\Service\UtilEncoding\Model\Json;
use Spryker\Zed\Propel\Business\Exception\ConfigFileNotCreatedException;
use Spryker\Zed\Propel\Business\Exception\ConfigMissingPropertyException;

class PropelConfigConverterJson implements PropelConfigConverterInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->validateConfig();

        $this->fixMissingZedConfig();

        $this->createTargetDirectoryIfNotExists();
    }

    /**
     * This method can be removed when clients use fixed `config/Shared/config_propel.php`
     *
     * @return void
     */
    private function fixMissingZedConfig()
    {
        if (empty($this->config['database']['connections']['zed'])) {
            $this->config['database']['connections']['zed'] = $this->config['database']['connections']['default'];
        }
    }

    /**
     * @throws \Spryker\Zed\Propel\Business\Exception\ConfigMissingPropertyException
     *
     * @return void
     */
    protected function validateConfig()
    {
        if (empty($this->config['paths']['phpConfDir'])) {
            throw new ConfigMissingPropertyException('Could not find "phpConfDir" configuration');
        }
    }

    /**
     * @return void
     */
    public function convertConfig()
    {
        $this->writeToFile();
        $this->validateFileExists();
    }

    /**
     * @return void
     */
    protected function writeToFile()
    {
        file_put_contents($this->getFileName(), $this->convertToJson());
    }

    /**
     * @return string
     */
    protected function convertToJson()
    {
        $config = ['propel' => $this->config];
        $jsonUtil = new Json();

        return $jsonUtil->encode($config);
    }

    /**
     * @return string
     */
    protected function getTargetDirectory()
    {
        return $this->config['paths']['phpConfDir'];
    }

    /**
     * @return void
     */
    protected function createTargetDirectoryIfNotExists()
    {
        $configDirectory = $this->getTargetDirectory();

        if (!is_dir($configDirectory)) {
            $this->createTargetDirectory($configDirectory);
        }
    }

    /**
     * @param string $configDirectory
     *
     * @return void
     */
    protected function createTargetDirectory($configDirectory)
    {
        mkdir($configDirectory, 0775, true);
    }

    /**
     * @return string
     */
    protected function getFileName()
    {
        return $this->getTargetDirectory() . DIRECTORY_SEPARATOR . 'propel.json';
    }

    /**
     * @throws \Spryker\Zed\Propel\Business\Exception\ConfigFileNotCreatedException
     *
     * @return void
     */
    protected function validateFileExists()
    {
        if (!is_file($this->getFileName())) {
            throw new ConfigFileNotCreatedException(sprintf('Could not create config file "%s"', $this->getFileName()));
        }
    }
}
