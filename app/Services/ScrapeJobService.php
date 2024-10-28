<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\JobStatusEnum;
use App\Events\WebScrapingRequested;
use App\Repositories\JobRepository;
use App\Repositories\RedisJobRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;

readonly class ScrapeJobService
{
    public function __construct(
        private JobRepository $jobRepository,
        private RedisJobRepository $redisJobRepository,
    )
    {
    }

    public function createScrapeJob(array $urls, array $selectors): void
    {
        foreach ($urls as $url) {
            $uuid = (string) Str::uuid();

            $data = [
                'uuid' => $uuid,
                'url' => $url,
                'selectors' => $selectors,
                'status' => JobStatusEnum::QUEUED->value,
            ];

            $job = $this->jobRepository->store($data);

            // If redis is used instead
           $this->redisJobRepository->create($uuid, $data);

            event(new WebScrapingRequested($job->id, $url, $selectors));
        }
    }
}