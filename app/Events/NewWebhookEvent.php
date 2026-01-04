<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use App\Models\WebhookEvent;

class NewWebhookEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;

    public function __construct(WebhookEvent $event)
    {
        $this->event = $event;
    }

    public function broadcastOn()
    {
        return new Channel('webhook-events');
    }

    public function broadcastAs()
    {
        return 'webhook.received';
    }
}
