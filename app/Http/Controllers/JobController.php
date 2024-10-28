<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateJobRequest;
use App\Http\Resources\ScrapeJobResource;
use App\Models\ScrapeJob;
use App\Repositories\JobRepository;
use App\Repositories\RedisJobRepository;
use App\Services\ScrapeJobService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;


class JobController extends Controller
{
    public function __construct(
        private readonly JobRepository      $jobRepository,
        private readonly ScrapeJobService   $scrapeJobService,
        private readonly RedisJobRepository $redisJobRepository,
    )
    {
    }

    public function create(CreateJobRequest $request): JsonResponse
    {
        $this->scrapeJobService->createScrapeJob(
            urls: $request->validated('urls'),
            selectors: $request->validated('selectors'),
        );

        return response()->json(['message' => 'Web scraping has been queued.']);
    }

    public function show(ScrapeJob $scrapeJob): ScrapeJobResource
    {
        // If redis is used instead
        $scrapeJob = $this->redisJobRepository->getByUuid($scrapeJob->uuid);

        return new ScrapeJobResource($scrapeJob);
    }

    public function delete(ScrapeJob $scrapeJob): JsonResponse
    {
        try {
            $this->jobRepository->delete($scrapeJob);

            // If redis is used instead
            $this->redisJobRepository->delete($scrapeJob->uuid);
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
