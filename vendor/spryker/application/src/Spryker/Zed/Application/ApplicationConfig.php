<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Shared\Application\ApplicationConfig getSharedConfig()
 */
class ApplicationConfig extends AbstractBundleConfig
{
    /**
     * @const string
     */
    protected const HEADER_X_FRAME_OPTIONS_VALUE = 'SAMEORIGIN';

    /**
     * @const string
     */
    protected const HEADER_CONTENT_SECURITY_POLICY_VALUE = 'frame-ancestors \'self\'';

    /**
     * @const string
     */
    protected const HEADER_X_CONTENT_TYPE_OPTIONS_VALUE = 'nosniff';

    /**
     * @const string
     */
    protected const HEADER_X_XSS_PROTECTION_VALUE = '1; mode=block';

    /**
     * @const string
     */
    protected const HEADER_REFERRER_POLICY_VALUE = 'same-origin';

    /**
     * @deprecated {@link https://www.w3.org/TR/permissions-policy-1/#introduction}
     *
     * @const string
     */
    protected const HEADER_FEATURE_POLICY_VALUE = '';

    /**
     * @const string
     */
    protected const HEADER_PERMISSION_POLICY_VALUE = '';

    /**
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getHostName()
    {
        return $this->get(ApplicationConstants::HOST_ZED);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getYvesHostName(): string
    {
        return $this->get(ApplicationConstants::HOST_YVES);
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Shared\Router\RouterConstants::ZED_IS_SSL_ENABLED} instead.
     *
     * @return bool
     */
    public function isSslEnabled()
    {
        return $this->get(ApplicationConstants::ZED_SSL_ENABLED, true);
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Shared\Router\RouterConstants::ZED_SSL_EXCLUDED_ROUTE_NAMES} instead.
     *
     * @return array
     */
    public function getSslExcludedResources()
    {
        return $this->get(ApplicationConstants::ZED_SSL_EXCLUDED, []);
    }

    /**
     * @api
     *
     * @return array
     */
    public function getTrustedProxies()
    {
        return $this->get(ApplicationConstants::ZED_TRUSTED_PROXIES, []);
    }

    /**
     * @api
     *
     * @return int
     */
    public function getTrustedHeader(): int
    {
        return $this->get(
            ApplicationConstants::YVES_TRUSTED_HEADER,
            Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO
        );
    }

    /**
     * @api
     *
     * @return array
     */
    public function getTrustedHosts()
    {
        return $this->get(ApplicationConstants::ZED_TRUSTED_HOSTS, []);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProjectNamespace(): string
    {
        return $this->get(ApplicationConstants::PROJECT_NAMESPACE);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getTwigEnvironmentName(): string
    {
        return $this->get(ApplicationConstants::TWIG_ENVIRONMENT_NAME, $this->getTwigEnvironmentNameDefaultValue());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    protected function getTwigEnvironmentNameDefaultValue(): string
    {
        return APPLICATION_ENV;
    }

    /**
     * @api
     *
     * @deprecated The WhoopsErrorHandler is deprecated and replaced by a new ErrorHandlerApplicationPlugin.
     *
     * @return bool
     */
    public function isPrettyErrorHandlerEnabled(): bool
    {
        return $this->get(ApplicationConstants::ENABLE_PRETTY_ERROR_HANDLER, $this->getPrettyErrorHandlerDefaultValue());
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return bool
     */
    protected function getPrettyErrorHandlerDefaultValue(): bool
    {
        return APPLICATION_ENV === 'development';
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getSecurityHeaders(): array
    {
        $headers = [
            'X-Frame-Options' => static::HEADER_X_FRAME_OPTIONS_VALUE,
            'Content-Security-Policy' => static::HEADER_CONTENT_SECURITY_POLICY_VALUE,
            'X-Content-Type-Options' => static::HEADER_X_CONTENT_TYPE_OPTIONS_VALUE,
            'X-XSS-Protection' => static::HEADER_X_XSS_PROTECTION_VALUE,
            'Referrer-Policy' => static::HEADER_REFERRER_POLICY_VALUE,
            'Permissions-Policy' => static::HEADER_PERMISSION_POLICY_VALUE,
        ];

        $headers = $this->addFeaturePolicyHeader($headers);

        return $headers;
    }

    /**
     * @deprecated {@link https://www.w3.org/TR/permissions-policy-1/#introduction}
     *
     * @param string[] $headers
     *
     * @return string[]
     */
    protected function addFeaturePolicyHeader(array $headers): array
    {
        $headers['Feature-Policy'] = static::HEADER_FEATURE_POLICY_VALUE;

        return $headers;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->getSharedConfig()->isDebugModeEnabled();
    }
}
