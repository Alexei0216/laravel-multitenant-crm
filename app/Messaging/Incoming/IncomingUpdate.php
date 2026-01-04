<?php

namespace App\Messaging\Incoming;

class IncomingUpdate
{
    public function __construct(
        public readonly string $provider,
        public readonly string $type,
        public readonly string $externalId,
        public readonly ?string $chatId,
        public readonly ?string $userId,
        public readonly ?string $text,
        public readonly array $payload = [],
    ) {}
}
