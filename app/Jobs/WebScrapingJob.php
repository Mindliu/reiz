<?php

declare(strict_types=1);

namespace App\Jobs;


use App\Enums\JobStatusEnum;
use App\Repositories\JobRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class WebScrapingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $url;
    protected int $jobId;
    protected array $selectors;

    public function __construct(int $jobId, string $url, array $selectors)
    {
        $this->jobId = $jobId;
        $this->url = $url;
        $this->selectors = $selectors;
    }

    public function handle(
        JobRepository $jobRepository
    ): void
    {
        $job = $jobRepository->getById($this->jobId);
        $jobRepository->update($job, [
            'status' => JobStatusEnum::STARTED->value,
        ]);

        try {
            $content = $this->scrape();
        } catch (\Exception $exception) {
            $jobRepository->update($job, [
                'status' => JobStatusEnum::FAILED->value,
            ]);

            Log::warning('FAILED_SCRAPER_JOB', [
                'message' => $exception->getMessage(),
                'id' => $this->jobId,
            ]);

            return;
        }

        $jobRepository->update($job, [
            'status' => JobStatusEnum::DONE->value,
            'content' => ['data' => $content],
        ]);

        // Further code goes here
    }

    /**
     * @throws GuzzleException
     */
    private function scrape (): string
    {
        $client = new Client();

        // Process and store scraped content as needed
        $response = $client->get($this->url);

        // Full implementation for the scrapper goes here
        return $response->getBody()->getContents();
    }
}
