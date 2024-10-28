<?php

use App\Enums\JobStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scrape_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->string('url');
            $table->json('selectors')->nullable();
            $table->json('content')->nullable();
            $table->string('status')->default(JobStatusEnum::QUEUED->value);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scrape_jobs');
    }
};
