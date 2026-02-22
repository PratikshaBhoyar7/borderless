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
        // Disable foreign key checks
        Schema::disableForeignKeyConstraints();

        // Remove campaign_type_id from patients table first
        Schema::table('patients', function (Blueprint $table) {
            if (Schema::hasColumn('patients', 'campaign_type_id')) {
                $table->dropForeign(['campaign_type_id']);
                $table->dropColumn('campaign_type_id');
            }
        });

        // Drop campaign-related tables
        Schema::dropIfExists('patient_campaign_values');
        Schema::dropIfExists('patient_campaign_entries');
        Schema::dropIfExists('campaign_fields');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('campaign_types');

        // Re-enable foreign key checks
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate patients table columns
        Schema::table('patients', function (Blueprint $table) {
            if (!Schema::hasColumn('patients', 'campaign_type_id')) {
                $table->foreignId('campaign_type_id')->nullable()->constrained('campaign_types')->nullOnDelete();
            }
        });
    }
};
