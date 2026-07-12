<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_posting_id')->constrained('job_postings')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('gender');
            $table->date('birth_date');
            $table->string('education_level');
            $table->boolean('has_agd')->default(false);
            $table->string('agd_certificate_path')->nullable();
            $table->string('sim_c_path')->nullable();
            $table->string('sim_b1_path')->nullable();
            $table->unsignedTinyInteger('experience_years')->default(0);
            $table->boolean('placement_ready')->default(false);
            $table->boolean('is_priority')->default(false);
            $table->timestamps();

            $table->unique(['job_posting_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
