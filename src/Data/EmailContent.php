<?php

declare(strict_types=1);

namespace Deegitalbe\TrustupIoNotificationsContracts\Data;

readonly class EmailContent
{
    /** @param array<string, mixed> $variables */
    public function __construct(
        public array $variables,
        public ?string $senderName = null,
    ) {}
}
