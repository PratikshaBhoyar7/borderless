<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('analytics_cache', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->string('metric_key')->comment('Metric identifier (disease_distribution, age_demographics, etc)');
            $table->json('metric_value')->nullable()->comment('Cached metric data');
            $table->dateTime('cached_at')->useCurrent()->comment('When the cache was created');
            $table->dateTime('expires_at')->comment('When the cache expires (24 hours by default)');
            $table->timestamps();

            $table->unique(['campaign_id', 'metric_key']);
            $table->index(['campaign_id', 'metric_key']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_cache');
    }
};
