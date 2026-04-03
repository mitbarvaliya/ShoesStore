<?php

namespace App\Events;

use App\Models\Market;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MarketPriceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $marketData;
    public string $timestamp;

    public function __construct(public Market $market)
    {
        $this->marketData = [
            'id' => $market->id,
            'symbol' => $market->symbol,
            'name' => $market->name,
            'type' => $market->type,
            'price' => (float) $market->price,
            'previous_price' => (float) ($market->previous_price ?? 0),
            'status' => $market->getPriceChangeStatus(),
            'difference' => $market->getPriceDifference(),
            'percentage' => $market->getPriceChangePercentage(),
        ];
        $this->timestamp = now()->toIso8601String();
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('market-prices'),
            new PrivateChannel('market-updates'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'price.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'market' => $this->marketData,
            'timestamp' => $this->timestamp,
        ];
    }
}
