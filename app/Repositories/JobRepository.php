<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\RepositoryInterface;
use App\Models\ScrapeJob;

class JobRepository implements RepositoryInterface
{
    public function getById(int $id): ScrapeJob
    {
        return ScrapeJob::query()->findOrFail($id);
    }

    public function store(array $data): ScrapeJob
    {
        return ScrapeJob::query()->create($data);
    }

    public function update($model, array $data): int
    {
        return $model->update($data);
    }

    public function delete($model): mixed
    {
        return $model->delete();
    }
}