<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function getById(int $id): Model;
    public function store(array $data): Model;
    public function update($model, array $data): int;

    public function delete($model): mixed;
}