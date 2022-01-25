<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilText\Model\Url;

/**
 * Parses and generates URLs based on URL parts. In favor of performance, URL parts are not validated.
 */
class Url
{
    public const SCHEME = 'scheme';
    public const HOST = 'host';
    public const PORT = 'port';
    public const USER = 'user';
    public const PASS = 'pass';
    public const PATH = 'path';
    public const QUERY = 'query';
    public const FRAGMENT = 'fragment';

    /**
     * @var string
     */
    protected $scheme;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var array
     */
    protected $query = [];

    /**
     * @var string
     */
    protected $fragment;

    /**
     * Factory method to create a new URL from a complete URL string
     *
     * @param string $url Full URL used to create a Url object
     *
     * @throws \Spryker\Service\UtilText\Model\Url\UrlInvalidException
     *
     * @return self
     */
    public static function parse($url)
    {
        static $defaults = [
            self::SCHEME => null,
            self::HOST => null,
            self::PORT => null,
            self::USER => null,
            self::PASS => null,
            self::PATH => null,
            self::QUERY => null,
            self::FRAGMENT => null,
        ];

        $parts = parse_url($url);
        if ($parts === false) {
            throw new UrlInvalidException('Was unable to parse malformed URL: ' . $url);
        }

        $parts += $defaults;

        return new static($parts);
    }

    /**
     * Factory method to create an internal URL from a path string
     *
     * @param string $url
     * @param array $query
     * @param array $options
     *
     * @return self
     */
    public static function generate($url, array $query = [], array $options = [])
    {
        $parts = [
            self::PATH => $url,
            self::QUERY => $query,
        ] + $options;

        return new static($parts);
    }

    /**
     * @param array $url
     */
    public function __construct(array $url = [])
    {
        $this->fromArray($url);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->build();
    }

    /**
     * @param array $url
     *
     * @return void
     */
    public function fromArray(array $url = [])
    {
        if (isset($url[self::QUERY]) && !is_array($url[self::QUERY])) {
            $url[self::QUERY] = self::parseQuery($url[self::QUERY]);
        }

        foreach ($url as $k => $v) {
            $this->{$k} = $v;
        }
    }

    /**
     * Build a URL. The generated URL will be a relative URL if a scheme or host are not provided.
     *
     * @return string
     */
    public function build()
    {
        $parts = $this->toArray();
        $url = $this->buildBaseUrl($parts);

        $url = $this->addPathComponent($url, $parts);
        $url = $this->addQueryComponent($url, $parts);
        $url = $this->addFragmentComponent($url, $parts);

        return $url;
    }

    /**
     * @return string
     */
    public function buildEscaped()
    {
        return $this->escape($this->build());
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function escape($url)
    {
        $charset = mb_internal_encoding() ?: 'UTF-8';

        return htmlspecialchars($url, ENT_QUOTES | ENT_SUBSTITUTE, $charset);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            self::SCHEME => $this->scheme,
            self::USER => $this->username,
            self::PASS => $this->password,
            self::HOST => $this->host,
            self::PORT => $this->port,
            self::PATH => $this->path,
            self::QUERY => $this->query ?: [],
            self::FRAGMENT => $this->fragment,
        ];
    }

    /**
     * @param array|string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        static $pathReplace = [' ' => '%20', '?' => '%3F'];
        if (is_array($path)) {
            $path = '/' . implode('/', $path);
        }

        $this->path = strtr($path, $pathReplace);

        return $this;
    }

    /**
     * Normalize the URL so that double slashes and relative paths are removed
     *
     * @return $this
     */
    public function normalizePath()
    {
        if (!$this->path || in_array($this->path, ['/', '*'], true)) {
            return $this;
        }

        $results = [];
        $segments = $this->getPathSegments();
        foreach ($segments as $segment) {
            if ($segment === '..') {
                array_pop($results);
            } elseif (!in_array($segment, ['.', ''], true)) {
                $results[] = $segment;
            }
        }

        // Combine the normalized parts and add the leading slash if needed
        $this->path = ($this->path[0] === '/' ? '/' : '') . implode('/', $results);

        return $this;
    }

    /**
     * Add a relative path to the currently set path
     *
     * @param array|string $relativePath
     *
     * @return $this
     */
    public function addPath($relativePath)
    {
        if (is_string($relativePath)) {
            $relativePath = explode('/', $relativePath);
        }

        // Add a leading slash if needed
        $path = $this->getPath();
        foreach ($relativePath as $element) {
            if ($element !== '') {
                $path .= '/' . $element;
            }
        }

        $this->setPath($path);

        return $this;
    }

    /**
     * Get the path part of the URL
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $scheme
     *
     * @return $this
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
    }

    /**
     * @param int $port
     *
     * @return $this
     */
    public function setPort($port)
    {
        $this->port = $port;

        return $this;
    }

    /**
     * @param string $host
     *
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * Get the path segments of the URL as an array
     *
     * @return array
     */
    public function getPathSegments()
    {
        return array_slice(explode('/', $this->getPath()), 1);
    }

    /**
     * Get the query part of the URL as a QueryString object
     *
     * @return array
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function addQuery($key, $value)
    {
        $this->query[$key] = $value;

        return $this;
    }

    /**
     * Set the query part of the URL
     *
     * @param array $query Query to set
     *
     * @return $this
     */
    public function setQuery(array $query)
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @param string $query
     *
     * @return array
     */
    public static function parseQuery($query)
    {
        parse_str($query, $array);

        return $array;
    }

    /**
     * @deprecated We are using `http_build_query()` internally instead.
     *
     * @param string $value
     *
     * @return string
     */
    protected function encodeQuery($value)
    {
        return urlencode($value);
    }

    /**
     * @param array $parts
     *
     * @return string
     */
    protected function buildBaseUrl(array $parts)
    {
        $url = $scheme = '';

        if (isset($parts[static::SCHEME])) {
            $scheme = $parts[static::SCHEME];
            $url .= $scheme . ':';
        }

        if (!isset($parts[static::HOST])) {
            return $url;
        }

        $url .= '//';
        if (isset($parts[static::USER])) {
            $url .= $parts[static::USER];
            if (isset($parts[static::PASS])) {
                $url .= ':' . $parts[static::PASS];
            }
            $url .= '@';
        }

        $url .= $parts[static::HOST];

        // Only include the port if it is not the default port of the scheme
        if (
            isset($parts[static::PORT])
            && !(($scheme === 'http' && $parts[static::PORT] === 80) || ($scheme === 'https' && $parts[static::PORT] === 443))
        ) {
            $url .= ':' . $parts[static::PORT];
        }

        return $url;
    }

    /**
     * @param string $url
     * @param array $parts
     *
     * @return string
     */
    protected function addPathComponent($url, array $parts)
    {
        if (isset($parts[self::PATH]) && strlen($parts[self::PATH]) !== 0) {
            // Always ensure that the path begins with '/' if set and something is before the path
            if ($url && $parts[self::PATH][0] !== '/' && mb_substr($url, -1) !== '/') {
                $url .= '/';
            }
            $url .= $parts[self::PATH];
        } else {
            $url .= '/';
        }

        return $url;
    }

    /**
     * @param string $url
     * @param array $parts
     *
     * @return string
     */
    protected function addQueryComponent($url, array $parts)
    {
        if (!empty($parts[self::QUERY])) {
            $url .= '?' . http_build_query($parts[self::QUERY]);
        }

        return $url;
    }

    /**
     * @param string $url
     * @param array $parts
     *
     * @return string
     */
    protected function addFragmentComponent($url, array $parts)
    {
        if (isset($parts[self::FRAGMENT])) {
            $url .= '#' . $parts[self::FRAGMENT];
        }

        return $url;
    }
}
