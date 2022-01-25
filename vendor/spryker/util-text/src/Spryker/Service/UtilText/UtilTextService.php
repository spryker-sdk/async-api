<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilText\UtilTextServiceFactory getFactory()
 */
class UtilTextService extends AbstractService implements UtilTextServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $value
     *
     * @return string
     */
    public function generateSlug($value)
    {
        return $this->getFactory()
            ->createTextSlug()
            ->generate($value);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $length
     *
     * @return string
     */
    public function generateRandomString($length)
    {
        return $this->getFactory()
            ->createStringGenerator()
            ->generateRandomString($length);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $length
     *
     * @return string
     */
    public function generateRandomByteString(int $length = 32): string
    {
        return $this->getFactory()
            ->createStringGenerator()
            ->generateRandomByteString($length);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param mixed $value
     * @param string $algorithm
     *
     * @return string
     */
    public function hashValue($value, $algorithm)
    {
        return $this->getFactory()->createHash()->hashValue($value, $algorithm);
    }

    /**
     * Specification:
     * - Converts a camel cased string into a string where every word is linked with the other by specified separator.
     *
     * @api
     *
     * @param string $string
     * @param string $separator
     *
     * @return string
     */
    public function camelCaseToSeparator($string, $separator = '-')
    {
        return $this->getFactory()->createCamelCaseToSeparator()->filter($string, $separator);
    }

    /**
     * Specification:
     * - Converts a camel cased string into a string where every word is linked with the other by a dash (-) separator.
     *
     * @api
     *
     * @param string $string
     *
     * @return string
     */
    public function camelCaseToDash($string)
    {
        return $this->getFactory()->createCamelCaseToSeparator()->filter($string, '-');
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $string
     * @param string $separator
     * @param bool $upperCaseFirst
     *
     * @return string
     */
    public function separatorToCamelCase($string, $separator = '-', $upperCaseFirst = false)
    {
        return $this->getFactory()->createSeparatorToCamelCase()->filter($string, $separator, $upperCaseFirst);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $string
     * @param bool $upperCaseFirst
     *
     * @return string
     */
    public function dashToCamelCase($string, $upperCaseFirst = false)
    {
        return $this->getFactory()->createSeparatorToCamelCase()->filter($string, '-', $upperCaseFirst);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $rawToken
     * @param array $options
     *
     * @return string
     */
    public function generateToken($rawToken, array $options = [])
    {
        return $this->getFactory()->createToken()->generate($rawToken, $options);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $rawToken
     * @param string $hash
     *
     * @return bool
     */
    public function checkToken($rawToken, $hash)
    {
        return $this->getFactory()->createToken()->check($rawToken, $hash);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $prefix
     * @param bool $moreEntropy
     *
     * @return string
     */
    public function generateUniqueId(string $prefix = '', bool $moreEntropy = false): string
    {
        return $this->getFactory()->createUniqueIdGenerator()->generateUniqueId($prefix, $moreEntropy);
    }
}
