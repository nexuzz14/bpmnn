<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviu_surats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('draf_surat_id')->constrained('draf_surats')->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->enum('tingkat', ['1', '2', 'final']);
            $table->enum('status', ['menunggu', 'disetujui', 'revisi'])->default('menunggu');
            $table->text('catatan_reviu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviu_surats');
    }
};
