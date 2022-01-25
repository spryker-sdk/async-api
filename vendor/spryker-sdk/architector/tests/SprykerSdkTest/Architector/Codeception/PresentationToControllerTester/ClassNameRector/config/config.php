<?php declare(strict_types = 1);

use SprykerSdk\Architector\Codeception\PresentationToControllerTester\PresentationToControllerTesterClassNameRector;
use SprykerSdk\Architector\Codeception\RectorHelper\TestSuiteHelper;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (
    ContainerConfigurator $containerConfigurator
): void {
    $services = $containerConfigurator->services();
    $services->set(PresentationToControllerTesterClassNameRector::class);
    $services->set(TestSuiteHelper::class);
};
