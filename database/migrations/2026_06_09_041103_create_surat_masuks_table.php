<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda')->nullable();
            $table->string('nomor_surat')->unique();
            $table->date('tanggal_surat');
            $table->date('tanggal_terima');
            $table->string('asal_surat');
            $table->string('perihal');
            $table->enum('jenis_surat', ['fisik', 'digital']);
            $table->string('file_surat')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['diterima', 'diproses', 'menunggu_reviu', 'revisi', 'selesai'])->default('diterima');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuks');
    }
};
