<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

// Redirect root to login or dashboard
Route::get('/', function () {
    return redirect()->route('login');
});

// Common Dashboard Controller to handle routing based on roles
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::get('/notifications/{id}', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');

    // Admin Persuratan
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'admin'])->name('dashboard');
        Route::resource('surat-masuk', \App\Http\Controllers\Admin\SuratMasukController::class);

        Route::get('progress', [\App\Http\Controllers\Admin\ProgressController::class, 'index'])->name('progress.index');
        Route::get('progress/{id}', [\App\Http\Controllers\Admin\ProgressController::class, 'show'])->name('progress.show');
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('units', \App\Http\Controllers\Admin\UnitController::class);
        Route::get('arsip', [\App\Http\Controllers\Admin\ArsipController::class, 'index'])->name('arsip.index');
        Route::get('arsip/export', [\App\Http\Controllers\Admin\ArsipController::class, 'exportPdf'])->name('arsip.pdf');
        Route::get('arsip/{id}/unduh', [\App\Http\Controllers\Admin\ArsipController::class, 'pdf'])->name('arsip.unduh');
        
        Route::get('rekap', [\App\Http\Controllers\Admin\RekapController::class, 'index'])->name('rekap.index');
        Route::get('rekap/export', [\App\Http\Controllers\Admin\RekapController::class, 'exportExcel'])->name('rekap.export');
        Route::get('rekap/pdf', [\App\Http\Controllers\Admin\RekapController::class, 'exportPdf'])->name('rekap.pdf');
        
        // Admin - Template Surat
        Route::get('/template-surat', [\App\Http\Controllers\Admin\TemplateSuratController::class, 'index'])->name('template-surat.index');
        Route::post('/template-surat', [\App\Http\Controllers\Admin\TemplateSuratController::class, 'store'])->name('template-surat.store');
        Route::delete('/template-surat/{templateSurat}', [\App\Http\Controllers\Admin\TemplateSuratController::class, 'destroy'])->name('template-surat.destroy');
    });

    // Tata Usaha
    Route::middleware('role:tata_usaha')->prefix('tu')->name('tu.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'tataUsaha'])->name('dashboard');
        Route::get('progress', [\App\Http\Controllers\Tu\ProgressController::class, 'index'])->name('progress.index');
        Route::get('progress/{id}', [\App\Http\Controllers\Tu\ProgressController::class, 'show'])->name('progress.show');
        
        // TU - Surat Masuk
        Route::get('/surat-masuk', [\App\Http\Controllers\Tu\SuratMasukController::class, 'index'])->name('surat-masuk.index');
        Route::get('/surat-masuk/{suratMasuk}', [\App\Http\Controllers\Tu\SuratMasukController::class, 'show'])->name('surat-masuk.show');
        Route::delete('/surat-masuk/{suratMasuk}', [\App\Http\Controllers\Tu\SuratMasukController::class, 'destroy'])->name('surat-masuk.destroy');

        // TU - Buku Agenda
        Route::get('/buku-agenda', [\App\Http\Controllers\Tu\BukuAgendaController::class, 'index'])->name('buku-agenda.index');
        Route::get('/buku-agenda/export', [\App\Http\Controllers\Tu\BukuAgendaController::class, 'export'])->name('buku-agenda.export');

        // TU - Disposisi
        Route::get('/disposisi', [\App\Http\Controllers\Tu\DisposisiController::class, 'index'])->name('disposisi.index');
        Route::get('/disposisi/riwayat', [\App\Http\Controllers\Tu\RiwayatDisposisiController::class, 'index'])->name('disposisi.riwayat');
        Route::get('/disposisi/{suratMasuk}/create', [\App\Http\Controllers\Tu\DisposisiController::class, 'create'])->name('disposisi.create');
        Route::post('/disposisi/{suratMasuk}', [\App\Http\Controllers\Tu\DisposisiController::class, 'store'])->name('disposisi.store');
        Route::get('/disposisi/pdf/{disposisi}', [\App\Http\Controllers\Tu\DisposisiController::class, 'pdf'])->name('disposisi.pdf');
        
        // TU - Distribusi Surat Final
        Route::get('/surat-final', [\App\Http\Controllers\Tu\SuratFinalController::class, 'index'])->name('surat-final.index');
        Route::get('/surat-final/{id}', [\App\Http\Controllers\Tu\SuratFinalController::class, 'show'])->name('surat-final.show');
        Route::patch('/surat-final/{suratFinal}/distribusikan', [\App\Http\Controllers\Tu\SuratFinalController::class, 'distribusikan'])->name('surat-final.distribusikan');

        // TU - Upload TTD
        Route::get('/upload-ttd', [\App\Http\Controllers\Tu\UploadTtdController::class, 'index'])->name('upload-ttd.index');
        Route::get('/upload-ttd/{drafSurat}', [\App\Http\Controllers\Tu\UploadTtdController::class, 'create'])->name('upload-ttd.create');
        Route::post('/upload-ttd/{drafSurat}', [\App\Http\Controllers\Tu\UploadTtdController::class, 'store'])->name('upload-ttd.store');

    });

    // Kepala Bagian
    Route::middleware('role:kepala_bagian')->prefix('kabag')->name('kabag.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'kepalaBagian'])->name('dashboard');
        Route::get('progress', [\App\Http\Controllers\Kabag\ProgressController::class, 'index'])->name('progress.index');
        Route::get('progress/{id}', [\App\Http\Controllers\Kabag\ProgressController::class, 'show'])->name('progress.show');
        Route::get('/rekap', [\App\Http\Controllers\Kabag\RekapController::class, 'index'])->name('rekap.index');
        Route::get('/rekap/pdf', [\App\Http\Controllers\Kabag\RekapController::class, 'exportPdf'])->name('rekap.pdf');
        Route::get('/terdistribusi', [\App\Http\Controllers\Kabag\TerdistribusiController::class, 'index'])->name('terdistribusi.index');
        Route::get('/terdistribusi/{id}', [\App\Http\Controllers\Kabag\TerdistribusiController::class, 'show'])->name('terdistribusi.show');
        Route::get('/disposisi/{disposisi}/detail', [\App\Http\Controllers\Kabag\DisposisiController::class, 'detail'])->name('disposisi.detail');
        Route::resource('disposisi', \App\Http\Controllers\Kabag\DisposisiController::class);
        Route::resource('review', \App\Http\Controllers\Kabag\ReviewController::class);
        Route::get('/menunggu-kabiro', [\App\Http\Controllers\Kabag\ReviewController::class, 'menungguKabiro'])->name('menunggu-kabiro.index');
        Route::get('/menunggu-kabiro/{id}', [\App\Http\Controllers\Kabag\ReviewController::class, 'showMenungguKabiro'])->name('menunggu-kabiro.show');
        Route::get('/buat-surat', [\App\Http\Controllers\Kabag\BuatSuratController::class, 'index'])->name('buat-surat.index');
        Route::get('draf-saya/template/{id}/download', [\App\Http\Controllers\Kabag\DrafSayaController::class, 'downloadTemplate'])->name('draf-saya.download-template');
        Route::resource('draf-saya', \App\Http\Controllers\Kabag\DrafSayaController::class)->parameters(['draf-saya' => 'drafSurat']);
    });

    // Kepala Sub Tim
    Route::middleware('role:kepala_sub_tim')->prefix('kasubtim')->name('kasubtim.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'kepalaSubTim'])->name('dashboard');
        Route::get('progress', [\App\Http\Controllers\Kasubtim\ProgressController::class, 'index'])->name('progress.index');
        Route::get('progress/{id}', [\App\Http\Controllers\Kasubtim\ProgressController::class, 'show'])->name('progress.show');
        Route::resource('penugasan', \App\Http\Controllers\Kasubtim\PenugasanController::class);
        Route::get('/draft', [\App\Http\Controllers\Kasubtim\DraftSuratController::class, 'index'])->name('draft.index');
        Route::get('/draft/{id}', [\App\Http\Controllers\Kasubtim\DraftSuratController::class, 'show'])->name('draft.show');
        Route::get('/buat-surat', [\App\Http\Controllers\Kasubtim\BuatSuratController::class, 'index'])->name('buat-surat.index');
        Route::get('draf-saya/template/{id}/download', [\App\Http\Controllers\Kasubtim\DrafSayaController::class, 'downloadTemplate'])->name('draf-saya.download-template');
        Route::resource('draf-saya', \App\Http\Controllers\Kasubtim\DrafSayaController::class)->parameters(['draf-saya' => 'drafSurat']);
        Route::resource('review', \App\Http\Controllers\Kasubtim\ReviewController::class);
        Route::get('/riwayat', [\App\Http\Controllers\Kasubtim\RiwayatController::class, 'index'])->name('riwayat.index');
    });

    // Staf
    Route::middleware('role:staf')->prefix('staf')->name('staf.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'staf'])->name('dashboard');
        Route::get('progress', [\App\Http\Controllers\Staf\ProgressController::class, 'index'])->name('progress.index');
        Route::get('progress/{id}', [\App\Http\Controllers\Staf\ProgressController::class, 'show'])->name('progress.show');
        Route::get('/tugas', [\App\Http\Controllers\Staf\TugasController::class, 'index'])->name('tugas.index');
        Route::get('/buat-surat', [\App\Http\Controllers\Staf\BuatSuratController::class, 'index'])->name('buat-surat.index');
        Route::get('/selesai', [\App\Http\Controllers\Staf\SelesaiController::class, 'index'])->name('selesai.index');
        Route::get('/selesai/{id}', [\App\Http\Controllers\Staf\SelesaiController::class, 'show'])->name('selesai.show');
        Route::get('/template-surat/{id}/download', [\App\Http\Controllers\Staf\DrafSuratController::class, 'downloadTemplate'])->name('template-surat.download');
        Route::resource('draf-surat', \App\Http\Controllers\Staf\DrafSuratController::class);
    });

    // Kepala Biro
    Route::middleware('role:kepala_biro')->prefix('kabiro')->name('kabiro.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'kepalaBiro'])->name('dashboard');
        Route::get('progress', [\App\Http\Controllers\Kabiro\ProgressController::class, 'index'])->name('progress.index');
        Route::get('progress/{id}', [\App\Http\Controllers\Kabiro\ProgressController::class, 'show'])->name('progress.show');
        Route::get('/rekap', [\App\Http\Controllers\Kabiro\RekapController::class, 'index'])->name('rekap.index');
        Route::get('/rekap/pdf', [\App\Http\Controllers\Kabiro\RekapController::class, 'exportPdf'])->name('rekap.pdf');
        Route::get('/terdistribusi', [\App\Http\Controllers\Kabiro\TerdistribusiController::class, 'index'])->name('terdistribusi.index');
        Route::resource('review-final', \App\Http\Controllers\Kabiro\ReviewFinalController::class);
    });
});

require __DIR__.'/auth.php';