<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Tests;

use Deegitalbe\TrustupIoNotificationsContracts\TrustupIoNotificationsContractsServiceProvider;
use Junges\Kafka\Providers\LaravelKafkaServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LaravelKafkaServiceProvider::class,
            TrustupIoNotificationsContractsServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('kafka.auto_commit', true);
    }
}
