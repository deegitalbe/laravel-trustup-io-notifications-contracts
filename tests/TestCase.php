<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Tests;

use Deegitalbe\TrustupIoNotificationsContracts\TrustupIoNotificationsContractsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TrustupIoNotificationsContractsServiceProvider::class,
        ];
    }
}
