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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('major')->nullable()->after('education_level');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->string('major')->nullable()->after('education_level');
            $table->string('placement_choice')->nullable()->after('placement_ready');
            $table->json('additional_documents')->nullable()->after('sim_b1_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn('major');
        });

        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['major', 'placement_choice', 'additional_documents']);
        });
    }
};
