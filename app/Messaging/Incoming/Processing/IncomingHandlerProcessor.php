<?php

namespace App\Messaging\Incoming\Processing;

use App\Messaging\Incoming\IncomingUpdate;
use App\Messaging\Incoming\Handlers\IncomingUpdateHandler;

class IncomingHandlerProcessor
{
    /**
     * @var IncomingUpdateHandler[]
     */
    protected array $handlers;

    public function __construct(array $handlers)
    {
        $this->handlers = $handlers;
    }

    public function process(IncomingUpdate $update): void
    {
        foreach ($this->handlers as $handler) {
            if ($handler->supports($update)) {
                $handler->handle($update);
            }
        }
    }
}
