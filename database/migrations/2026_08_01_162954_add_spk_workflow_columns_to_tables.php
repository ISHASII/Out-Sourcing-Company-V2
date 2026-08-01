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
        Schema::table('job_postings', function (Blueprint $table) {
            $table->enum('spk_status', ['pending', 'completed'])->default('pending')->after('requirements_config');
            $table->json('spk_execution_logs')->nullable()->after('spk_status');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->json('spk_details')->nullable()->after('matching_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn(['spk_status', 'spk_execution_logs']);
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('spk_details');
        });
    }
};
