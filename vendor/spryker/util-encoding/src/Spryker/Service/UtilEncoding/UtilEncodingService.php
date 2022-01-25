<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncoding;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilEncoding\UtilEncodingServiceFactory getFactory()
 */
class UtilEncodingService extends AbstractService implements UtilEncodingServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param mixed $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string|null
     */
    public function encodeJson($value, $options = null, $depth = null)
    {
        return $this->getFactory()
            ->createJsonEncoder()
            ->encode($value, $options, $depth);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $jsonValue
     * @param bool $assoc
     * @param int|null $depth
     * @param int|null $options
     *
     * @return mixed|null
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null)
    {
        return $this->getFactory()
            ->createJsonEncoder()
            ->decode($jsonValue, $assoc, $depth, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param array $data
     * @param string $format
     *
     * @return string|null
     *
     * @throws \Spryker\Service\UtilEncoding\Exception\FormatNotSupportedException
     */
    public function encodeToFormat(array $data, string $format): ?string
    {
        return $this->getFactory()
            ->createEncoder()
            ->encodeToFormat($data, $format);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $data
     * @param string $format
     *
     * @return array|null
     *
     * @throws \Spryker\Service\UtilEncoding\Exception\FormatNotSupportedException
     */
    public function decodeFromFormat(string $data, string $format): ?array
    {
        return $this->getFactory()
            ->createDecoder()
            ->decodeFromFormat($data, $format);
    }
}
