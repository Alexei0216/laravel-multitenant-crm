<?php

namespace App\Messaging\Incoming\Jobs;

use App\Messaging\Incoming\Handlers\TelegramMessageHandler;
use App\Messaging\Incoming\IncomingUpdate;
use App\Messaging\Incoming\Processing\IncomingHandlerProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessIncomingUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private IncomingUpdate $update;
    private IncomingHandlerProcessor $handlerProcessor;

    public function __construct(IncomingUpdate $update)
    {
        $this->update = $update;
        $this->handlerProcessor = new IncomingHandlerProcessor([new TelegramMessageHandler()]);
    }

    public function handle()
    {
        $this->handlerProcessor->process($this->update);
    }
}
