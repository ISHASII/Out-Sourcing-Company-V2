<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('location_city')->nullable()->after('second_requires_placement_ready');
            $table->string('shift_type')->nullable()->after('location_city');
            $table->unsignedInteger('salary_min')->nullable()->after('shift_type');
            $table->unsignedInteger('salary_max')->nullable()->after('salary_min');
            $table->boolean('salary_hidden')->default(false)->after('salary_max');
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->dropColumn([
                'location_city',
                'shift_type',
                'salary_min',
                'salary_max',
                'salary_hidden',
            ]);
        });
    }
};
