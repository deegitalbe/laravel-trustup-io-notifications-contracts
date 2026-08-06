<?php

declare(strict_types=1);

use Illuminate\Support\ServiceProvider;

it('publishes the contracts config to the host app', function (): void {
    $paths = ServiceProvider::pathsToPublish(
        Deegitalbe\TrustupIoNotificationsContracts\TrustupIoNotificationsContractsServiceProvider::class,
        'trustup-io-notifications-contracts-config',
    );

    expect($paths)->not->toBeEmpty();

    $target = array_values($paths)[0];
    expect($target)->toEndWith('config/trustup-io-notifications-contracts.php');
});
