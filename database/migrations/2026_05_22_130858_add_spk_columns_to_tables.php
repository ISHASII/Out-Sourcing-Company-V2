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
            $table->json('requirements_config')->nullable()->after('is_active');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->unsignedTinyInteger('matching_score')->default(0)->after('is_priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn('requirements_config');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn('matching_score');
        });
    }
};
