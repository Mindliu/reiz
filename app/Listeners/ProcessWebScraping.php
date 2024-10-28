<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\WebScrapingRequested;
use App\Jobs\WebScrapingJob;

class ProcessWebScraping
{
    public function __construct()
    {
        //
    }

    public function handle(WebScrapingRequested $event): void
    {
        WebScrapingJob::dispatch($event->jobId, $event->url, $event->selectors);
    }
}
