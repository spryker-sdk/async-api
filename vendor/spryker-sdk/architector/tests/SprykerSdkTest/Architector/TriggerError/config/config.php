<?php declare(strict_types = 1);

use SprykerSdk\Architector\TriggerError\TriggerErrorMessagesWithSprykerPrefixRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (
    ContainerConfigurator $containerConfigurator
): void {
    $services = $containerConfigurator->services();
    $services->set(TriggerErrorMessagesWithSprykerPrefixRector::class);
};
