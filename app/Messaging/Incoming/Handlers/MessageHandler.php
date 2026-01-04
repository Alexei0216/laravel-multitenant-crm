<?php

namespace App\Messaging\Incoming\Handlers;

use App\Messaging\Incoming\IncomingUpdate;
use Illuminate\Support\Facades\Log;

class MessageHandler implements IncomingUpdateHandler
{
    public function supports(IncomingUpdate $update): bool
    {
        return $update->type === 'message';
    }

    public function handle(IncomingUpdate $update): void
    {
        Log::info("MessageHandler received message", [
            'provider' => $update->provider,
            'chat_id' => $update->chatId,
            'user_id' => $update->userId,
            'text' => $update->text,
        ]);
    }
}
