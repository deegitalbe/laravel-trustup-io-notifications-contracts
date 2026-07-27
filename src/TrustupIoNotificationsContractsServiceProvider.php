<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TrustupIoNotificationsContractsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package->name('trustup-io-notifications-contracts');
    }
}
