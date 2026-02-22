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
        // Create campaign_types table
        Schema::create('campaign_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        // Add campaign_type_id to patients table
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'campaign_type_id')) {
                $table->foreignId('campaign_type_id')
                    ->nullable()
                    ->after('date')
                    ->constrained('campaign_types')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'campaign_type_id')) {
                $table->dropForeign(['campaign_type_id']);
                $table->dropColumn('campaign_type_id');
            }
        });

        Schema::dropIfExists('campaign_types');
    }
};
