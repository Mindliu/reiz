<?php

namespace App\Http\Resources;

use App\Models\ScrapeJob;
use Illuminate\Http\Resources\Json\JsonResource;

class ScrapeJobResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var ScrapeJob $scrapeJob */
        $scrapeJob = $this->resource;

        return [
            'uuid' => $scrapeJob->uuid,
            'url' => $scrapeJob->url,
            'selectors' => $scrapeJob->selectors,
            'content' => $scrapeJob->content,
            'status' => $scrapeJob->status,
            'created_at' => $scrapeJob->created_at,
        ];
    }
}
