<?php

namespace App\Messaging\Incoming\Adapters;

use App\Messaging\Incoming\IncomingUpdate;
use App\Messaging\Incoming\Adapters\MessengerAdapter;
use Illuminate\Http\Request;
use RuntimeException;

class TelegramAdapter implements MessengerAdapter
{
    public function supports(Request $request): bool
    {
        return $request->hasAny(['message', 'callback_query']);
    }

    public function verify(Request $request): void
    {
        //
    }

    public function parse(Request $request): IncomingUpdate
    {
        $payload = $request->all();

        if (isset($payload['message'])) {
            return $this->parseMessage($payload);
        }

        if (isset($payload['callback_query'])) {
            return $this->parseCallback($payload);
        }

        throw new \RuntimeException('Unsupported Telegram update type');
    }

    protected function parseMessage(array $payload): IncomingUpdate
    {
        $message = $payload['message'];

        return new IncomingUpdate(
            provider: 'telegram',
            type: 'message',
            externalId: (string) $message['message_id'],
            chatId: (string) $message['chat']['id'],
            userId: (string) $message['from']['id'],
            text: $message['text'] ?? null,
            payload: $payload,
        );
    }

    protected function parseCallback(array $payload): IncomingUpdate
    {
        $callback = $payload['callback_query'];

        return new IncomingUpdate(
            provider: 'telegram',
            type: 'callback',
            externalId: (string) $callback['id'],
            chatId: (string) $callback['message']['chat']['id'],
            userId: (string) $callback['from']['id'],
            text: $callback['data'] ?? null,
            payload: $payload,
        );
    }

    public function provider(): string
    {
        return 'telegram';
    }
}
