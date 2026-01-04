<?php

namespace App\Messaging\Incoming\Handlers;

use App\Messaging\Incoming\IncomingUpdate;

interface IncomingUpdateHandler
{
    public function supports(IncomingUpdate $update): bool;

    public function handle(IncomingUpdate $update): void;
}
