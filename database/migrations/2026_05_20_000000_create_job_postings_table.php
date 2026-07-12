<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('core_gender')->default('male');
            $table->unsignedTinyInteger('core_min_age')->default(25);
            $table->unsignedTinyInteger('core_max_age')->default(35);
            $table->string('core_min_education')->default('SMA/SMK');
            $table->boolean('core_requires_agd')->default(true);
            $table->boolean('core_requires_sim_c')->default(true);
            $table->boolean('core_requires_sim_b1')->default(true);
            $table->unsignedTinyInteger('second_min_experience')->default(0);
            $table->boolean('second_requires_placement_ready')->default(true);
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};
