<?php

namespace App\Messaging\Incoming\Processing;

use App\Messaging\Incoming\Adapters\MessengerAdapter;
use App\Messaging\Incoming\Handlers\IncomingUpdateHandler;
use App\Messaging\Incoming\IncomingUpdate;
use App\Messaging\Incoming\Jobs\ProcessIncomingUpdateJob;
use Illuminate\Http\Request;
use RuntimeException;

class IncomingUpdateProcessor
{
    /** @var MessengerAdapter[] */
    protected array $adapters;

    /** @var IncomingUpdateHandler[] */
    protected array $handlers;

    public function __construct(array $adapters, array $handlers)
    {
        $this->adapters = $adapters;
        $this->handlers = $handlers;
    }

    public function process(Request $request): IncomingUpdate
    {
        foreach ($this->adapters as $adapter) {
            if ($adapter->supports($request)) {
                $adapter->verify($request);
                $update = $adapter->parse($request);

                ProcessIncomingUpdateJob::dispatch($update)
                    ->onQueue('messaging');

                return $update;
            }
        }

        throw new RuntimeException('No adapter supports this request');
    }
}
