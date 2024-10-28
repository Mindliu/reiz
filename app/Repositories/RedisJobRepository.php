<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\ScrapeJob;
use Illuminate\Support\Facades\Redis;

class RedisJobRepository
{
    public function getByUuid(string $uuid): ScrapeJob
    {
        $modelData = Redis::get($uuid);

        return new ScrapeJob(json_decode($modelData, true));
    }

    public function create(string $name, array $data): void
    {
        Redis::set($name, json_encode($data));
    }

    public function delete(string $name): void
    {
        Redis::del($name);
    }
}