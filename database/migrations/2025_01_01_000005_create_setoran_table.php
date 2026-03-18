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
        Schema::create('setoran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('santri_id')->constrained('santri')->onDelete('cascade');
            $table->foreignId('ustadz_id')->constrained('ustadz')->onDelete('cascade');
            $table->date('tanggal_setoran');
            $table->enum('jenis', ['setoran_baru', 'murajaah']);
            $table->unsignedInteger('surah_id');
            $table->integer('ayat_awal');
            $table->integer('ayat_akhir');
            $table->integer('jumlah_ayat_disetor');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('surah_id')->references('id')->on('surah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setoran');
    }
};
