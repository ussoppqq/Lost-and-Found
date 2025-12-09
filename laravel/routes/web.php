<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ReportPdfController;
use App\Http\Controllers\Admin\StatisticPdfController;
use App\Http\Controllers\LostPdfController;
use App\Http\Controllers\ReportReceiptController;

// Livewire Components
use App\Livewire\Auth\Login;
use App\Livewire\Auth\RegisterExtra;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ResetPassword;

use App\Livewire\Profile;

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Categories\Index as CategoriesIndex;
use App\Livewire\Admin\Items\Index as ItemsIndex;
use App\Livewire\Admin\LostAndFound\Index as LostFoundIndex;
use App\Livewire\Admin\Matches\MatchList;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Admin\Statistic;

use App\Livewire\FoundForm;
use App\Livewire\LostForm;
use App\Livewire\LostItems;
use App\Livewire\TrackingIndex;
use App\Livewire\TrackingDetail;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', fn() => view('home'))->name('home');
Route::get('/categories', fn() => view('categories'))->name('categories');

// Auth Routes (Public)
// Route::middleware(middleware: 'guest')->group(function (): void {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register-extra', RegisterExtra::class)->name('register-extra');
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
// });

// Public Forms
Route::get('/found-form', FoundForm::class)->name('found-form');
Route::get('/lost-form', LostForm::class)->name('lost-form');

// Lost Items Browse
Route::get('/lost-items', LostItems::class)->name('lost-items');

// Tracking (Public)
Route::prefix('tracking')->name('tracking.')->group(function () {
    Route::get('/', TrackingIndex::class)->name('index');
    Route::get('/{reportId}', TrackingDetail::class)
        ->where('reportId', '[0-9a-f\-]+')
        ->name('detail');
});

// Public PDF Routes
Route::get('/reports/{report}/pdf', [ReportPdfController::class, 'download'])
    ->name('reports.pdf');
Route::get('/reports/{report}/receipt/pdf', [ReportReceiptController::class, 'generatePdf'])
    ->middleware('signed')
    ->name('reports.receipt.pdf');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (All Users)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    // Profile
    Route::get('/profile', Profile::class)->name('profile');

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // Dashboard Redirect (Role-based)
    Route::get('/dashboard', function () {
        $roleCode = auth()->user()->role?->role_code;
        
        if (in_array($roleCode, ['ADMIN', 'MODERATOR'])) {
            return redirect()->route('admin.dashboard');
        }
        
        return redirect()->route('profile');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin & Moderator Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'moderator'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard.home');

    // Lost & Found Management
    Route::get('/lost-found', LostFoundIndex::class)->name('lost-found');
    Route::get('/found-form', FoundForm::class)->name('found-form');
    
    // Items Management
    Route::get('/items', ItemsIndex::class)->name('items');
    
    // Matches Management (route sederhana untuk kompatibilitas dengan layout)
    Route::get('/matches', MatchList::class)->name('matches');
    
    // Categories Management
    Route::get('/categories', CategoriesIndex::class)->name('categories');

    // Statistics
    Route::get('/statistic', Statistic::class)->name('statistic');
    Route::get('/statistic/pdf', [StatisticPdfController::class, 'export'])
        ->name('statistic.pdf');
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // User Management (Admin Only)
    Route::get('/users', UsersIndex::class)->name('users');
});

/*
|--------------------------------------------------------------------------
| Redirects & Fallbacks
|--------------------------------------------------------------------------
*/

// Short redirect
Route::redirect('/admin', '/admin/dashboard')->name('admin.redirect');

// Fallback for 404
Route::fallback(function () {
    abort(404, 'Page not found');
});