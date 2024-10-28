<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WebScrapingRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $jobId;
    public string $url;
    public array $selectors;

    public function __construct(int $jobId, string $url, array $selectors)
    {
        $this->jobId = $jobId;
        $this->url = $url;
        $this->selectors = $selectors;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
