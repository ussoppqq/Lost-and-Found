<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\ReportPdfController;
use App\Http\Controllers\Admin\StatisticPdfController;

use App\Http\Controllers\ReportReceiptController;
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
use App\Livewire\TrackingIndex;
use App\Livewire\TrackingDetail;



Route::get('/', fn() => view('home'))->name('home');
Route::get('/categories', fn() => view('categories'))->name('categories');

// Auth (public)
Route::get('/login', Login::class)->name('login');
Route::get('/register-extra', RegisterExtra::class)->name('register-extra');
Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');

// Public Forms
Route::get('/found-form', FoundForm::class)->name('found-form');
Route::get('/lost-form',  LostForm::class)->name('lost-form');

// Tracking (PUBLIC) – jangan duplikat
Route::prefix('tracking')->name('tracking.')->group(function () {
    Route::get('/', TrackingIndex::class)->name('index');
    Route::get('/{reportId}', TrackingDetail::class)
        ->where('reportId', '[0-9a-f\-]+')
        ->name('detail');
});

// Report PDF (public link khusus 1 file—kalau memang perlu)
Route::get('/reports/{report}/pdf', [ReportPdfController::class, 'download'])->name('reports.pdf');
// Receipt PDF (pakai signed kalau link publik)
Route::get('/reports/{report}/receipt/pdf', [ReportReceiptController::class, 'generatePdf'])
    ->middleware('signed')
    ->name('reports.receipt.pdf');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (user biasa setelah login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', Profile::class)->name('profile');

    // Logout
    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    // Redirect dashboard sesuai role
    Route::get('/dashboard', function () {
        $roleCode = auth()->user()->role?->role_code;
        if (in_array($roleCode, ['ADMIN','MODERATOR'])) {
            return redirect()->route('admin.dashboard');
        }
        abort(403, 'Unauthorized access.');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin & Moderator
|--------------------------------------------------------------------------
*/

Route::middleware(['auth','moderator'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard.home');

    // Modules
    Route::get('/found-form',  FoundForm::class)->name('found-form');
    Route::get('/lost-found',  LostFoundIndex::class)->name('lost-found');
    Route::get('/items',       ItemsIndex::class)->name('items');
    Route::get('/matches',     MatchList::class)->name('matches');
    Route::get('/categories',  CategoriesIndex::class)->name('categories');

    // Statistic (UI)
    Route::get('/statistic',   Statistic::class)->name('statistic');

    // Statistic PDF (admin/moderator)
    Route::get('/statistic/pdf', [StatisticPdfController::class, 'export'])->name('statistic.pdf');

    // Optional: report ringkasan versi PDF (harian/mingguan/bulanan) jika kamu bikin di ReportPdfController
    // Route::get('/reports/daily.pdf',   [ReportPdfController::class, 'daily'])->name('reports.daily.pdf');
    // Route::get('/reports/weekly.pdf',  [ReportPdfController::class, 'weekly'])->name('reports.weekly.pdf');
    // Route::get('/reports/monthly.pdf', [ReportPdfController::class, 'monthly'])->name('reports.monthly.pdf');
});

// Users management – HANYA ADMIN
Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', UsersIndex::class)->name('users');
});

// Redirect pendek
Route::redirect('/admin', '/admin/dashboard')->name('admin.redirect');
