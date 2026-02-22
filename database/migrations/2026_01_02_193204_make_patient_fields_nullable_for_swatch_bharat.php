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
        Schema::table('patients', function (Blueprint $table) {
            $table->text('complaints')->nullable()->change();
            $table->text('diagnosis')->nullable()->change();
            $table->text('treatment')->nullable()->change();
            $table->text('dosage')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->text('complaints')->nullable(false)->change();
            $table->text('diagnosis')->nullable(false)->change();
            $table->text('treatment')->nullable(false)->change();
            $table->text('dosage')->nullable(false)->change();
        });
    }
};
