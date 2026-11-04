<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

use Deegitalbe\TrustupIoNotificationsContracts\Enums\SenderRole;

final readonly class EmailSender
{
    public function __construct(
        public SenderRole $role = SenderRole::Default,
        public ?string $name = null,
    ) {}
}
