<?php

namespace Database\Factories;

use App\Enums\JobStatusEnum;
use App\Models\ScrapeJob;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ScrapeJob>
 */
class ScrapeJobFactory extends Factory
{
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'url' => fake()->url(),
            'selectors' => [],
            'content' => json_encode(fake()->text),
            'status' => JobStatusEnum::QUEUED->value,
        ];
    }
}
