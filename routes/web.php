<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HrdHiringController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PelamarLowonganController;
use App\Http\Controllers\HrdPartnerController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\JobCategoryController;

Route::get('/', [LandingPageController::class, 'index']);

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Routes with Role Guard
Route::middleware(['auth'])->group(function () {
    // === HRD ROUTES ===
    Route::prefix('hrd')->group(function () {
        Route::get('/dashboard', [HrdHiringController::class, 'dashboard'])->name('hrd.dashboard');

        Route::get('/hiring', [HrdHiringController::class, 'index'])->name('hrd.hiring');
        Route::get('/hiring/create', [HrdHiringController::class, 'create'])->name('hrd.hiring.create');
        Route::post('/hiring', [HrdHiringController::class, 'store'])->name('hrd.hiring.store');
        Route::get('/hiring/{jobPosting}', [HrdHiringController::class, 'show'])->name('hrd.hiring.show');
        Route::get('/hiring/{jobPosting}/edit', [HrdHiringController::class, 'edit'])->name('hrd.hiring.edit');
        Route::put('/hiring/{jobPosting}', [HrdHiringController::class, 'update'])->name('hrd.hiring.update');
        Route::delete('/hiring/{jobPosting}', [HrdHiringController::class, 'destroy'])->name('hrd.hiring.destroy');
        Route::patch('/hiring/{jobPosting}/toggle', [HrdHiringController::class, 'toggleActive'])->name('hrd.hiring.toggle');
        Route::post('/hiring/{jobPosting}/execute-spk', [HrdHiringController::class, 'executeSpk'])->name('hrd.hiring.execute-spk');

        Route::get('/pelamar-aktif', [HrdHiringController::class, 'pelamarAktif'])->name('hrd.pelamar-aktif');

        Route::get('/profil', function () {
            if (Auth::user()->role !== 'hrd') return redirect()->route('pelamar.dashboard');
            return view('hrd.profil');
        })->name('hrd.profil');

        Route::put('/profil', [AuthController::class, 'updateHrdProfile'])->name('hrd.profil.update');

        // Mitra (Partners) CRUD
        Route::get('/partners', [HrdPartnerController::class, 'index'])->name('hrd.partners.index');
        Route::post('/partners', [HrdPartnerController::class, 'store'])->name('hrd.partners.store');
        Route::put('/partners/{partner}', [HrdPartnerController::class, 'update'])->name('hrd.partners.update');
        Route::delete('/partners/{partner}', [HrdPartnerController::class, 'destroy'])->name('hrd.partners.destroy');

        // Application Decision & PDF routes
        Route::post('/applications/{jobApplication}/accept', [HrdHiringController::class, 'acceptApplication'])->name('hrd.applications.accept');
        Route::post('/applications/{jobApplication}/reject', [HrdHiringController::class, 'rejectApplication'])->name('hrd.applications.reject');
        Route::post('/applications/{jobApplication}/mark-valid', [HrdHiringController::class, 'markValid'])->name('hrd.applications.markValid');
        Route::post('/applications/{jobApplication}/mark-invalid', [HrdHiringController::class, 'markInvalid'])->name('hrd.applications.markInvalid');
        Route::get('/applications/{jobApplication}/pdf', [HrdHiringController::class, 'downloadPdf'])->name('hrd.applications.pdf');
        
        Route::post('/hiring/{jobPosting}/send-report', [HrdHiringController::class, 'sendReportToPimpinan'])->name('hrd.hiring.sendReport');

        // Kategori Pekerjaan CRUD (Dedicated)
        Route::get('/kategori-pekerjaan', [JobCategoryController::class, 'index'])->name('hrd.kategori.index');
        Route::post('/kategori-pekerjaan', [JobCategoryController::class, 'store'])->name('hrd.kategori.store');
        Route::delete('/kategori-pekerjaan/{category}', [JobCategoryController::class, 'destroy'])->name('hrd.kategori.destroy');

        // Kriteria Seleksi CRUD
        Route::get('/kriteria', [CriteriaController::class, 'index'])->name('hrd.kriteria.index');
        Route::post('/kriteria/category', [CriteriaController::class, 'storeCategory'])->name('hrd.kriteria.category.store');
        Route::delete('/kriteria/category/{category}', [CriteriaController::class, 'destroyCategory'])->name('hrd.kriteria.category.destroy');
        Route::get('/kriteria/{category}', [CriteriaController::class, 'show'])->name('hrd.kriteria.show');
        Route::post('/kriteria', [CriteriaController::class, 'store'])->name('hrd.kriteria.store');
        Route::put('/kriteria/{criterion}', [CriteriaController::class, 'update'])->name('hrd.kriteria.update');
        Route::delete('/kriteria/{criterion}', [CriteriaController::class, 'destroy'])->name('hrd.kriteria.destroy');
    });

    // === PELAMAR ROUTES ===
    Route::prefix('pelamar')->group(function () {
        Route::get('/dashboard', [PelamarLowonganController::class, 'dashboard'])->name('pelamar.dashboard');

        Route::get('/profil', function () {
            if (Auth::user()->role !== 'pelamar') return redirect()->route('hrd.dashboard');
            $jobCategories = \App\Models\JobCategory::where('is_active', true)->get();
            return view('pelamar.profil', compact('jobCategories'));
        })->name('pelamar.profil');

        Route::post('/profil', [AuthController::class, 'updatePelamarProfile'])->name('pelamar.profil.update');

        Route::get('/riwayat', [PelamarLowonganController::class, 'history'])->name('pelamar.riwayat');

        Route::get('/lowongan', [PelamarLowonganController::class, 'index'])->name('pelamar.lowongan');
        Route::get('/lowongan/{jobPosting}/apply', [PelamarLowonganController::class, 'create'])->name('pelamar.lowongan.apply');
        Route::post('/lowongan/{jobPosting}/apply', [PelamarLowonganController::class, 'store'])->name('pelamar.lowongan.store');

        // Notification read route
        Route::post('/notifications/mark-read', [PelamarLowonganController::class, 'markNotificationsRead'])->name('pelamar.notifications.markRead');
    });

    // === SUPERADMIN ROUTES ===
    Route::prefix('superadmin')->group(function () {
        Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('superadmin.dashboard');
        Route::get('/admins/create', [SuperAdminController::class, 'create'])->name('superadmin.admins.create');
        Route::post('/admins', [SuperAdminController::class, 'store'])->name('superadmin.admins.store');
        Route::get('/admins/{admin}/edit', [SuperAdminController::class, 'edit'])->name('superadmin.admins.edit');
        Route::put('/admins/{admin}', [SuperAdminController::class, 'update'])->name('superadmin.admins.update');
        Route::delete('/admins/{admin}', [SuperAdminController::class, 'destroy'])->name('superadmin.admins.destroy');
        Route::patch('/admins/{admin}/toggle', [SuperAdminController::class, 'toggleStatus'])->name('superadmin.admins.toggle');
    });

    // === PIMPINAN ROUTES ===
    Route::prefix('pimpinan')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\PimpinanController::class, 'dashboard'])->name('pimpinan.dashboard');
        Route::get('/laporan', [\App\Http\Controllers\PimpinanController::class, 'laporan'])->name('pimpinan.laporan');
        Route::get('/hiring', [\App\Http\Controllers\PimpinanController::class, 'hiring'])->name('pimpinan.hiring');
        Route::get('/hiring/{jobPosting}', [\App\Http\Controllers\PimpinanController::class, 'hiringShow'])->name('pimpinan.hiring.show');
        Route::get('/partners', [\App\Http\Controllers\PimpinanController::class, 'partners'])->name('pimpinan.partners');
        Route::get('/pelamar', [\App\Http\Controllers\PimpinanController::class, 'applicants'])->name('pimpinan.applicants');
    });
});

// ============================================================
//  REGISTRATION WITH OTP VERIFICATION
// ============================================================
Route::get('/register', function () {
    $jobCategories = \App\Models\JobCategory::where('is_active', true)->get();
    return view('auth.register', compact('jobCategories'));
})->name('register');

Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::get('/register/verify', [AuthController::class, 'showRegistrationVerify'])->name('register.verify');
Route::post('/register/verify', [AuthController::class, 'verifyRegistrationOtp'])->name('register.verifyOtp');
Route::post('/register/resend-otp', [AuthController::class, 'resendRegistrationOtp'])->name('register.resendOtp');

// ============================================================
//  FORGOT PASSWORD WITH OTP VERIFICATION
// ============================================================
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendPasswordResetOtp'])->name('password.sendOtp');
Route::get('/forgot-password/verify', [AuthController::class, 'showPasswordVerify'])->name('password.verify');
Route::post('/forgot-password/verify', [AuthController::class, 'verifyPasswordResetOtp'])->name('password.verifyOtp');
Route::post('/forgot-password/resend', [AuthController::class, 'resendPasswordOtp'])->name('password.resendOtp');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
