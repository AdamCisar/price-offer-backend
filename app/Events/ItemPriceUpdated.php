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
    public function __construct(private readonly int $itemId, private readonly array $data)
    {
        $this->pushToBeams($itemId, $data);
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

    private function pushToBeams(int $itemId, array $data): void
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
                    'title' => 'Item price updated',
                    'body' => $data['error'] ?? "Updated to {$data['percentage']}%",
                ],
                'data' => [
                    'type' => 'item-price-update',
                    'item_id' => $itemId,
                    'percentage' => $data['percentage'] ?? 0,
                    'price_offer_id' => $data['price_offer_id'],
                    'price' => $data['price'] ?? 0,
                    'error' => $data['error'] ?? '',
                ],
            ],
        ]);
    }
}
