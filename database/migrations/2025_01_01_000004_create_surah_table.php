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
        Schema::create('surah', function (Blueprint $table) {
            $table->id();
            $table->integer('nomor_surah');
            $table->string('nama_surah');
            $table->string('nama_latin');
            $table->integer('jumlah_ayat');
            $table->enum('jenis', ['makkiyah', 'madaniyah']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surah');
    }
};
