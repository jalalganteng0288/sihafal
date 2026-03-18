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
        Schema::create('evaluasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setoran_id')->unique()->constrained('setoran')->onDelete('cascade');
            $table->decimal('nilai_kelancaran', 5, 2);
            $table->decimal('nilai_tajwid', 5, 2);
            $table->decimal('nilai_makhraj', 5, 2);
            $table->decimal('nilai_akhir', 5, 2);
            $table->enum('kategori', ['Mumtaz', 'Jayyid Jiddan', 'Jayyid', 'Maqbul', 'Dhaif']);
            $table->text('catatan_evaluasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluasi');
    }
};
