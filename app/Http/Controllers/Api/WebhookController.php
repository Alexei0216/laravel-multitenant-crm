<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Messaging\Incoming\Processing\IncomingUpdateProcessor;
use App\Messaging\Incoming\Processing\IncomingHandlerProcessor;
use App\Messaging\Incoming\Adapters\TelegramAdapter;
use App\Messaging\Incoming\Handlers\TelegramMessageHandler;
use App\Messaging\Incoming\Jobs\ProcessIncomingUpdateJob;

class WebhookController extends Controller
{
    protected IncomingUpdateProcessor $processor;
    protected IncomingHandlerProcessor $handlerProcessor;

    public function __construct()
    {
        $this->processor = new IncomingUpdateProcessor(
            [new TelegramAdapter()],
            [new TelegramMessageHandler()]
        );
    }

    public function handle(Request $request)
    {
        $update = $this->processor->process($request);

        ProcessIncomingUpdateJob::dispatch($update);

        return response()->json(['status' => 'queued'], 200);
    }
}
