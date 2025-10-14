<?php

namespace App\Events;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class ItemPriceUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private readonly int $percentage)
    {
        $this->pushToBeams($percentage);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('item-price-update'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'UpdateItemPrice';
    }

    private function pushToBeams(int $percentage): void
    {
        $instanceId = env('PUSHER_BEAMS_INSTANCE_ID');
        $secretKey  = env('PUSHER_BEAMS_SECRET_KEY');

        Http::withHeaders([
            'Authorization' => "Bearer $secretKey",
            'Content-Type'  => 'application/json',
        ])->post("https://$instanceId.pushnotifications.pusher.com/publish_api/v1/instances/$instanceId/publishes", [
            'interests' => ['item-price-update'],
            'web' => [
                'notification' => [
                    'title' => 'item-price-update',
                    'body' => "$percentage",
                ],
            ],
        ]);
    }
}
