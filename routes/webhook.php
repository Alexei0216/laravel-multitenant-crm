<?php

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Request;

Route::prefix('webhook')
    ->name('webhook.')
    ->group(function () {
        Route::post('/telegram', function (Request $request) {
            // Handle Telegram webhook
            $update = $request->request->all();
            Log::channel('webhook')->info('Telegram webhook received', [
                'update' => $update,
                'headers' => $request->headers->all(),
            ]);

            if (
                !isset($update['channel_post']['text'])
                && !isset($update['message']['text'])
            ) {
                return response()->json(['ok' => true]);
            }

            if (isset($update['channel_post'])) {
                $chatId = $update['channel_post']['chat']['id']; // -100...
                $text   = $update['channel_post']['text'];
            } else {
                $chatId = $update['message']['chat']['id'];
                $text   = $update['message']['text'];
            }

            Http::post("https://api.telegram.org/bot" . config('services.telegram.token') . "/sendMessage", [
                'chat_id' => $chatId,
                'text'    => $text, // echo
            ]);

            return response()->json(['ok' => true]);
        })->name('telegram');
    });
