<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('category');         // 'Driver Ambulance', 'Cleaning Service', dll
            $table->string('key');              // slug unik per kategori: 'gender', 'age', 'sertifikat_agd'
            $table->string('label');            // Label tampil: 'Jenis Kelamin', 'Sertifikat AGD'
            $table->string('type');             // Tipe: 'select','range','number','file','checkbox','text'
            $table->json('config')->nullable(); // Config spesifik tipe (opsi dropdown, min/max, dll)
            $table->string('default_status')->default('secondary'); // 'core' | 'secondary'
            $table->unsignedTinyInteger('default_weight')->default(0); // Default bobot per-kriteria (%)
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['category', 'key']); // Prevent duplicates
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criteria');
    }
};
