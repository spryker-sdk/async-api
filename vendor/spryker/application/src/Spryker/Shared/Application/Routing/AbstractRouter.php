<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Routing;

use Silex\Application;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractRouter implements RouterInterface
{
    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @var bool|null
     */
    protected $sslEnabled;

    /**
     * @var \Symfony\Component\Routing\RequestContext|null
     */
    protected $context;

    /**
     * Set the sslEnabledFlag to
     *     true to force ssl
     *     false to force http
     *     null to not force anything (both https or http allowed)
     *
     * @param \Silex\Application $app
     * @param bool|null $sslEnabled
     */
    public function __construct(Application $app, $sslEnabled = null)
    {
        $this->app = $app;
        $this->sslEnabled = $sslEnabled;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Symfony\Component\Routing\RequestContext $context
     *
     * @return void
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * @inheritDoc
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @inheritDoc
     */
    public function getRouteCollection()
    {
        return new RouteCollection();
    }

    /**
     * @return bool
     */
    public function isSslEnabled()
    {
        return $this->sslEnabled;
    }

    /**
     * @param string $pathInfo
     *
     * @return array|null
     */
    protected function checkScheme($pathInfo)
    {
        $wantedScheme = $this->isSslEnabled() ? 'https' : 'http';
        if ($this->getContext()->getScheme() !== $wantedScheme) {
            $url = $wantedScheme . '://' . $this->context->getHost() . $pathInfo;

            return [
                '_controller' => function ($url) {
                    return new RedirectResponse($url, 301);
                },
                '_route' => null,
                'url' => $url,
            ];
        }

        return null;
    }

    /**
     * @param string $pathInfo
     * @param int|string $referenceType
     *
     * @return string
     */
    protected function getUrlOrPathForType($pathInfo, $referenceType)
    {
        $url = $pathInfo;
        $scheme = $this->context->getScheme();

        if (
            $referenceType !== self::NETWORK_PATH &&
            ($scheme === 'http' && $this->sslEnabled === true || $scheme === 'https' && $this->sslEnabled === false)
        ) {
            $referenceType = self::ABSOLUTE_URL;
        }

        switch ($referenceType) {
            case self::ABSOLUTE_URL:
            case self::NETWORK_PATH:
                $url = $this->buildUrl($pathInfo, $referenceType);

                break;
            case self::ABSOLUTE_PATH:
                $url = $pathInfo;

                break;
            case self::RELATIVE_PATH:
                $url = UrlGenerator::getRelativePath($this->context->getPathInfo(), $pathInfo);

                break;
        }

        return $url;
    }

    /**
     * @param string $pathInfo
     * @param int|string $referenceType
     *
     * @return string
     */
    private function buildUrl($pathInfo, $referenceType)
    {
        $scheme = $this->getScheme();
        $port = $this->getPortPart($scheme);
        $schemeAuthority = $referenceType === self::NETWORK_PATH ? '//' : "$scheme://";
        $schemeAuthority .= $this->context->getHost() . $port;

        return $schemeAuthority . $this->context->getBaseUrl() . $pathInfo;
    }

    /**
     * @param string $scheme
     *
     * @return string
     */
    private function getPortPart($scheme)
    {
        $port = '';
        if ($scheme === 'http' && $this->context->getHttpPort() !== 80) {
            $port = ':' . $this->context->getHttpPort();
        } elseif ($scheme === 'https' && $this->context->getHttpsPort() !== 443) {
            $port = ':' . $this->context->getHttpsPort();
        }

        return $port;
    }

    /**
     * @return string
     */
    private function getScheme()
    {
        $scheme = $this->context->getScheme();
        if (is_bool($this->sslEnabled)) {
            $scheme = ($this->sslEnabled) ? 'https' : 'http';
        }

        return $scheme;
    }
}
