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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number')->unique();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->foreignId('campaign_type_id')->nullable()->constrained('campaign_types')->onDelete('set null');

            // Basic Information
            $table->string('patient_name');
            $table->integer('age');
            $table->enum('sex', ['Male', 'Female', 'Other']);
            $table->date('date');

            // Location Details
            $table->string('village');
            $table->foreignId('taluka_id')->nullable()->constrained('talukas')->onDelete('set null');
            $table->foreignId('district_id')->nullable()->constrained('districts')->onDelete('set null');
            $table->foreignId('state_id')->nullable()->constrained('states')->onDelete('set null');
            $table->foreignId('country_id')->nullable()->constrained('countries')->onDelete('set null');
            $table->string('mobile', 10);
            $table->string('aadhar', 12)->nullable();

            // Vital Signs
            $table->decimal('height', 5, 2)->nullable(); // cm
            $table->decimal('weight', 5, 2)->nullable(); // kg
            $table->string('bp')->nullable(); // e.g., 120/80
            $table->integer('rbs')->nullable(); // mg/dL
            $table->integer('bsl')->nullable(); // mg/dL
            $table->decimal('hb', 5, 2)->nullable(); // g/dL

            // Clinical Information
            $table->text('complaints');
            $table->text('known_conditions')->nullable(); // K/O/C
            $table->text('diagnosis');

            // Treatment
            $table->text('treatment');
            $table->text('dosage');

            // Lab & Referral
            $table->json('lab_tests')->nullable(); // Array of selected tests
            $table->enum('sample_collected', ['Yes', 'No', 'NA'])->nullable();
            $table->string('referral_type')->nullable();
            $table->text('referral_details')->nullable();

            // Additional
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('serial_number');
            $table->index('mobile');
            $table->index('created_by');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
