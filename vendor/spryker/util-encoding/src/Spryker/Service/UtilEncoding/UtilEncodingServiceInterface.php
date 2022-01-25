<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncoding;

/**
 * @method \Spryker\Service\UtilEncoding\UtilEncodingServiceFactory getFactory()
 */
interface UtilEncodingServiceInterface
{
    /**
     * Specification:
     * - Encodes given value to JSON string.
     *
     * @api
     *
     * @param array $value
     * @param int|null $options
     * @param int|null $depth
     *
     * @return string|null
     */
    public function encodeJson($value, $options = null, $depth = null);

    /**
     * Specification:
     * - Decodes given JSON string, returns array or stdObject.
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
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null);

    /**
     * Specification:
     * - Encodes the given array to the given format.
     * - Throws an exception if format is not supported.
     *
     * @api
     *
     * @param array $data
     * @param string $format
     *
     * @throws \Spryker\Service\UtilEncoding\Exception\FormatNotSupportedException
     *
     * @return string|null
     */
    public function encodeToFormat(array $data, string $format): ?string;

    /**
     * Specification:
     * - Decodes the given string from the given format.
     * - Throws an exception if format is not supported.
     * - Returns null if decoding process was failed.
     *
     * @api
     *
     * @param string $data
     * @param string $format
     *
     * @throws \Spryker\Service\UtilEncoding\Exception\FormatNotSupportedException
     *
     * @return array|null
     */
    public function decodeFromFormat(string $data, string $format): ?array;
}
