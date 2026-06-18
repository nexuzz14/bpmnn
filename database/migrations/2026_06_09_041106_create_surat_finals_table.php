<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_finals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained('surat_masuks')->onDelete('cascade');
            $table->foreignId('draf_surat_id')->constrained('draf_surats')->onDelete('cascade');
            $table->string('nomor_surat_final')->nullable();
            $table->string('file_ttd')->nullable();
            $table->foreignId('ditandatangani_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->date('tanggal_ttd')->nullable();
            $table->string('file_distribusi')->nullable();
            $table->enum('via', ['email', 'fisik', 'keduanya'])->default('email');
            $table->string('status')->default('belum');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_finals');
    }
};
