<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Tests\Stubs;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;

final readonly class NestedSerializableStub implements Serializable
{
    use SerializesFromConstructor;

    public function __construct(public string $value) {}
}
