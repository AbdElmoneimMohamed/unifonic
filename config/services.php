<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use App\Campaigns\Business\SMS\Factory\SmsHttpClientFactory;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;

return function (ContainerConfigurator $container): void {
    // default configuration for services in *this* file
    $services = $container->services();

    $services
        ->defaults()
        ->autowire()      // Automatically injects dependencies in your services.
        ->autoconfigure() // Automatically registers your services as commands, event subscribers, etc.
        ->bind(GuzzleClientInterface::class . ' $smsHttpClient', service('app.sms.http_client'))
    ;

    $services->set(GuzzleClientInterface::class, GuzzleClient::class);

    // makes classes in src/ available to be used as services
    // this creates a service per class whose id is the fully-qualified class name
    $services->load('App\\', '../src/')
        ->exclude('../src/{DependencyInjection,Entity,Kernel.php}');

    registerSmsServices($services);

    // order is important in this file because service definitions
    // always *replace* previous ones; add your own service configuration below
};

function registerSmsServices(ServicesConfigurator $services): void
{
    $services->set('app.sms.http_client', GuzzleClientInterface::class)
        ->factory(service(SmsHttpClientFactory::class));

    $services->set(SmsHttpClientFactory::class)
        ->args([
            '$baseUrl' => env('SMS_URL'),
        ]);
}
