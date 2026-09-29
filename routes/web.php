<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\TicketController as PortalTicketController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TicketController as AdminTicketController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ContentController;
use App\Http\Controllers\Admin\SopController;
use App\Http\Controllers\Admin\AdminKontakController;
use App\Http\Controllers\KontakController;

// ===================== PUBLIK =====================
Route::get('/', [PublicController::class, 'beranda'])->name('beranda');

Route::get('/profil/{section?}', [PublicController::class, 'profil'])
    ->name('profil')
    ->whereIn('section', ['tentang', 'tugas-fungsi', 'visi-misi', 'struktur', 'kontak']);

Route::post('/profil/kontak/kirim', [KontakController::class, 'kirimKontak'])
    ->name('profil.kontak.kirim');

Route::get('/informasi', [PublicController::class, 'informasi'])->name('informasi');
Route::get('/informasi/{announcement}', [PublicController::class, 'informasiShow'])->name('informasi.show');
Route::get('/sop-pusdatin', [PublicController::class, 'sop'])->name('sop');
Route::get('/sop-pusdatin/{sop}/unduh', [PublicController::class, 'sopDownload'])->name('sop.unduh');
Route::post('/chatbot', [ChatbotController::class, 'ask'])->name('chatbot.ask');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===================== PORTAL PEGAWAI =====================
Route::middleware(['auth'])->prefix('portal')->name('portal.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/notifikasi/baca-semua', [DashboardController::class, 'readAllNotifications'])->name('notifikasi.baca');

    Route::get('/tiket', [PortalTicketController::class, 'index'])->name('tiket.index');
    Route::get('/tiket/buat/{kategori?}', [PortalTicketController::class, 'create'])->name('tiket.create');
    Route::post('/tiket/buat/{kategori}', [PortalTicketController::class, 'store'])->name('tiket.store');
    Route::get('/tiket/{tiket}/download-local', [PortalTicketController::class, 'downloadLocal'])->name('tiket.download-local');
    Route::delete('/tiket/{tiket}', [PortalTicketController::class, 'destroy'])->name('tiket.destroy');
    Route::get('/tiket/{tiket}', [PortalTicketController::class, 'show'])->name('tiket.show');
});

// ===================== ADMIN =====================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::get('/tiket', [AdminTicketController::class, 'index'])->name('tiket.index');
    Route::get('/tiket/{tiket}', [AdminTicketController::class, 'show'])->name('tiket.show');
    Route::post('/tiket/{tiket}/status', [AdminTicketController::class, 'updateStatus'])->name('tiket.status');

    Route::resource('pengumuman', AnnouncementController::class);

    Route::prefix('konten')->name('konten.')->group(function () {
        Route::get('/', [ContentController::class, 'index'])->name('index');
        Route::post('/section', [ContentController::class, 'storeGroup'])->name('section.store');
        Route::get('/{group:slug}', [ContentController::class, 'edit'])->name('edit');
        Route::put('/{group:slug}', [ContentController::class, 'update'])->name('update');
        Route::post('/{group:slug}/field', [ContentController::class, 'storeField'])->name('field.store');
        Route::post('/{group:slug}/mitra', [ContentController::class, 'storeMitra'])->name('mitra.store');
        Route::delete('/{group:slug}/mitra/{index}', [ContentController::class, 'destroyMitra'])->name('mitra.destroy');
        Route::delete('/field/{field}', [ContentController::class, 'destroyField'])->name('field.destroy');
    });

    Route::resource('sop', SopController::class)->except(['show']);
    Route::get('/sop/{sop}/unduh', [SopController::class, 'download'])->name('sop.unduh');

    // ==== PESAN KONTAK ====
    Route::get('/kontak', [AdminKontakController::class, 'index'])->name('kontak.index');
    Route::get('/kontak/{kontak}', [AdminKontakController::class, 'show'])->name('kontak.show');
    Route::delete('/kontak/{kontak}', [AdminKontakController::class, 'destroy'])->name('kontak.destroy');

});