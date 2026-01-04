<?php

namespace App\Messaging\Incoming\Handlers;

use App\Messaging\Incoming\IncomingUpdate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class TelegramMessageHandler implements IncomingUpdateHandler
{
    public function supports(IncomingUpdate $update): bool
    {
        return $update->provider === 'telegram' && $update->type === 'message';
    }

    public function handle(IncomingUpdate $update): void
    {
        Log::info('Telegram message received', [
            'chat_id' => $update->chatId,
            'user_id' => $update->userId,
            'text' => $update->text,
        ]);
    }
}
