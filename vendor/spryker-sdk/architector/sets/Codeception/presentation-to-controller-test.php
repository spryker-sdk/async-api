<?php
/**
 * Copyright © 2021-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use SprykerSdk\Architector\Codeception\PresentationToControllerConfig\PresentationToControllerConfigFileProcessor;
use SprykerSdk\Architector\Codeception\PresentationToControllerConfig\PresentationToControllerConfigRector;
use SprykerSdk\Architector\Codeception\PresentationToControllerTest\PresentationToControllerTestClassContFetchRector;
use SprykerSdk\Architector\Codeception\PresentationToControllerTester\PresentationToControllerTesterClassNameRector;
use SprykerSdk\Architector\Codeception\PresentationToControllerTester\PresentationToControllerTesterFileMoveRector;
use SprykerSdk\Architector\Codeception\PresentationToControllerTester\PresentationToControllerTesterTraitUseRector;
use SprykerSdk\Architector\Codeception\PresentationToControllerTest\PresentationToControllerTestFileMoveRector;
use SprykerSdk\Architector\Codeception\PresentationToControllerTest\PresentationToControllerTestMethodParamRector;
use SprykerSdk\Architector\Codeception\PresentationToControllerTest\PresentationToControllerTestNamespaceRector;
use SprykerSdk\Architector\Codeception\RectorHelper\TestSuiteHelper;

return static function (
    ContainerConfigurator $containerConfigurator
): void {
    $services = $containerConfigurator->services();

    $services->set(TestSuiteHelper::class);
    $services->set(PresentationToControllerConfigFileProcessor::class);
    $services->set(PresentationToControllerConfigRector::class);

    $services->set(PresentationToControllerTesterClassNameRector::class);
    $services->set(PresentationToControllerTesterTraitUseRector::class);
    $services->set(PresentationToControllerTesterFileMoveRector::class);

    $services->set(PresentationToControllerTestClassContFetchRector::class);
    $services->set(PresentationToControllerTestNamespaceRector::class);
    $services->set(PresentationToControllerTestMethodParamRector::class);
    $services->set(PresentationToControllerTestFileMoveRector::class);
};
