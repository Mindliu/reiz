<?php

namespace Tests\Feature;

use App\Events\WebScrapingRequested;
use App\Http\Resources\ScrapeJobResource;
use App\Models\ScrapeJob;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class JobsTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_fails_validation(): void
    {
        $this->withoutMiddleware();

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('api/jobs', [
            'urls' => 'not-array',
        ], [
            'accept' => 'application/json'
        ]);

        $response->assertStatus(422);
    }

    public function test_job_is_created_and_queued(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $this->actingAs($user);

        $urls = ['https://example.com', 'https://another-example.com'];

        $response = $this->post('api/jobs', [
            'urls' => $urls,
            'selectors' => ['h1'],
        ], [
            'accept' => 'application/json'
        ]);

        foreach ($urls as $url) {
            Event::assertDispatched(WebScrapingRequested::class, function ($event) use ($url) {
                return $event->url === $url;
            });
        }

        // Redis entries are being set correctly
        $createdJobsUuids = ScrapeJob::query()->get()->pluck('uuid');
        foreach ($createdJobsUuids as $uuid) {
            $this->assertNotNull(Redis::get($uuid));
        }

        $response->assertJson(['message' => 'Web scraping has been queued.']);
    }

    public function test_job_is_retrieved(): void
    {
        Event::fake();

        /** @var ScrapeJob $job */
        $job = ScrapeJob::factory()->create();
        Redis::set($job->uuid, json_encode($job->toArray()));

        $response = $this->get('api/jobs/' . $job->id);

        $resource = new ScrapeJobResource($job);

        $response->assertOk();
        $response->assertJsonFragment([
            'uuid' => $resource->uuid,
        ]);
    }

    public function test_job_is_deleted(): void
    {
        Event::fake();

        /** @var ScrapeJob $job */
        $job = ScrapeJob::factory()->create();
        Redis::set($job->uuid, json_encode($job->toArray()));

        $response = $this->delete('api/jobs/' . $job->id);

        $this->assertNull(Redis::get($job->uuid));
        $this->assertDatabaseEmpty('scrape_jobs');
        $response->assertOk();
        $response->assertJsonFragment([
            "message" => "Scrape job deleted",
        ]);
    }
}
