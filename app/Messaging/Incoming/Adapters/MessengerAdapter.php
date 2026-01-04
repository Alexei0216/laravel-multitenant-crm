<?php

namespace App\Messaging\Incoming\Adapters;

use App\Messaging\Incoming\IncomingUpdate;
use Illuminate\Http\Request;

interface MessengerAdapter
{
    public function verify(Request $request): void;

    public function parse(Request $request): IncomingUpdate;

    public function provider(): string;

    public function supports(Request $request): bool;
}
