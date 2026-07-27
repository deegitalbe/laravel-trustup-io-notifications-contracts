<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Tests\Stubs;

use Deegitalbe\TrustupIoNotificationsContracts\Contracts\Serializable;
use Deegitalbe\TrustupIoNotificationsContracts\Data\Concerns\SerializesFromConstructor;

/** Has a nullable parameter with no default to exercise the null fallback. */
final readonly class NullableNoDefaultStub implements Serializable
{
    use SerializesFromConstructor;

    public function __construct(public ?string $value) {}
}
