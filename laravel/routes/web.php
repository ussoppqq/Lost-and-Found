<?php

use App\Http\Controllers\ReportPdfController;
use App\Livewire\Admin\Categories\Index as CategoriesIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Items\Index as ItemsIndex;
use App\Livewire\Admin\LostAndFound\Index as LostFoundIndex;
use App\Livewire\Admin\Matches\MatchList;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Auth\Forgotpassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\RegisterExtra;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\FoundForm;
use App\Livewire\LostForm;
use App\Livewire\TrackingDetail;
use App\Livewire\TrackingIndex;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\Statistic;
use App\Http\Controllers\Admin\StatisticPdfController;
use App\Http\Controllers\LostPdfController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/categories', function () {
    return view('categories');
})->name('categories');

// Auth Routes
Route::get('/login', Login::class)->name('login');
Route::get('register-extra', RegisterExtra::class)->name('register-extra');
Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');

// Public Forms
Route::get('/found-form', FoundForm::class)->name('found-form');
Route::get('/lost-form', LostForm::class)->name('lost-form');

// Tracking Routes (Public)
Route::prefix('tracking')->name('tracking.')->group(function () {
    Route::get('/', TrackingIndex::class)->name('index');
    Route::get('/{reportId}', TrackingDetail::class)
        ->name('detail')
        ->where('reportId', '[0-9a-f\-]+');
});

Route::get('/reports/{report}/pdf', [ReportPdfController::class, 'download'])
    ->name('reports.pdf');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Profile Routes
    Route::get('/profile', \App\Livewire\Profile::class)->name('profile');
    Route::get('/profile/show', function () {
        return view('profile');
    })->name('profile.show');

    // Logout Route
    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $roleCode = auth()->user()->role?->role_code;
        
        if (in_array($roleCode, ['ADMIN', 'MODERATOR'])) {
            return redirect()->route('admin.dashboard');
        }
        
        abort(403, 'Unauthorized access.');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Routes - Untuk Admin dan Moderator
| Kecuali route /users yang HANYA untuk Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'moderator'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard - Admin & Moderator
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard.home');
    
    // Found Form - Admin & Moderator
    Route::get('/found-form', FoundForm::class)->name('found-form');
    
    // Lost & Found Management - Admin & Moderator
    Route::get('/lost-found', LostFoundIndex::class)->name('lost-found');
    
    // Items - Admin & Moderator
    Route::get('/items', ItemsIndex::class)->name('items');
    
    // Matches - Admin & Moderator
    Route::get('/matches', MatchList::class)->name('matches');
    
    // Categories - Admin & Moderator
    Route::get('/categories', CategoriesIndex::class)->name('categories');
    
    // Statistics - Admin & Moderator
    Route::get('/statistic', Statistic::class)->name('statistic');
    Route::get('/statistic/pdf', [StatisticPdfController::class, 'export'])->name('statistic.pdf');
});

// Users Management - HANYA ADMIN (menggunakan middleware 'admin')
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', UsersIndex::class)->name('users');
});

Route::redirect('/admin', '/admin/dashboard');

Route::get('/profile', \App\Livewire\Profile::class)
    ->middleware(['auth'])->name('profile');

Route::prefix('tracking')->name('tracking.')->group(function () {
    // Halaman tracking utama - Form input nomor HP
    Route::get('/', TrackingIndex::class)
        ->name('index');

    // Halaman detail tracking - Detail report by report ID
    Route::get('/{reportId}', TrackingDetail::class)
        ->name('detail')
        ->where('reportId', '[0-9a-f\-]+');
});

Route::get('/reports/{report}/pdf', [ReportPdfController::class, 'download'])
            ->name('reports.pdf');
            Route::middleware(['auth'])->group(function () {
                Route::get('/admin/statistic', Statistic::class)->name('admin.statistic');
            
                // PDF export
                Route::get('/admin/statistic/pdf', [StatisticPdfController::class, 'export'])
                    ->name('admin.statistic.pdf');
            });
            
// routes/web.php
Route::get('/admin/statistic/pdf', [\App\Http\Controllers\Admin\StatisticPdfController::class, 'export'])
    ->name('admin.statistic.pdf');
