<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Application;

use Codeception\Actor;
use Silex\Application;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Zed\Application\Communication\Plugin\ServiceProvider\SslServiceProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 *
 * @SuppressWarnings(PHPMD)
 */
class ApplicationCommunicationTester extends Actor
{
    use _generated\ApplicationCommunicationTesterActions;

    /**
     * @param string $controllerResponse
     * @param bool $isSslEnabled
     *
     * @return \Silex\Application
     */
    public function getApplicationForSslTest(string $controllerResponse = '', bool $isSslEnabled = true): Application
    {
        $this->setConfig(ApplicationConstants::ZED_SSL_ENABLED, $isSslEnabled);
        $this->setConfig(ApplicationConstants::ZED_TRUSTED_HOSTS, []);

        $application = new Application();
        $application->register(new SslServiceProvider());

        $application['controllers']->get('/foo', function () use ($controllerResponse) {
            return $controllerResponse;
        });

        return $application;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequestForSslTest(): Request
    {
        return Request::create('/foo');
    }
}
