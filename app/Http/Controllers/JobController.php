<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\JobStatusEnum;
use App\Events\WebScrapingRequested;
use App\Http\Requests\CreateJobRequest;
use App\Http\Resources\ScrapeJobResource;
use App\Models\ScrapeJob;
use App\Repositories\JobRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;


class JobController extends Controller
{
    public function __construct(
        private readonly JobRepository $jobRepository,
    )
    {
    }

    /**
     * @throws \RedisException
     */
    public function create(CreateJobRequest $request): JsonResponse
    {
        foreach ($request->validated('urls') as $url) {
            $name = (string) Str::uuid();
            $selectors = $request->validated('selectors');

            $data = [
                'name' => $name,
                'url' => $url,
                'selectors' => $request->validated('selectors'),
                'status' => JobStatusEnum::QUEUED->value,
            ];

            $job = $this->jobRepository->store($data);

//            Redis::rpush('jobs_queue', json_encode($data));

            event(new WebScrapingRequested($job->id, $url, $selectors));
        }

        return response()->json(['message' => 'Web scraping has been queued.']);
    }

    public function show(ScrapeJob $job): ScrapeJobResource
    {
        return new ScrapeJobResource($job);
    }

    public function delete(ScrapeJob $scrapeJob): JsonResponse
    {
        try {
            $this->jobRepository->delete($scrapeJob);
        } catch (Exception $exception) {
            Log::error("FAILED_TO_DELETE_SCRAPE_JOB", [
                'message' => $exception->getMessage(),
                'id' => $scrapeJob->id,
            ]);

            return response()->json(['message' => 'Failed to delete scrape job']);
        }

        return response()->json(['message' => 'Scrape job deleted']);
    }
}
