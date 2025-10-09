<?php

use App\Livewire\Admin\Categories\Index as CategoriesIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Items\Index as ItemsIndex;
use App\Livewire\Admin\LostAndFound\Index as LostFoundIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;
use App\Livewire\Auth\Forgotpassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\RegisterExtra;
use App\Livewire\FoundForm;
use App\Livewire\LostForm;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)
    ->middleware(['auth'])->name('dashboard');

Route::get('/moderator', \App\Livewire\Moderator\Dashboard::class)
    ->middleware(['auth'])
    ->name('moderator');

Route::middleware(['auth'])->group(function () {
    // User profile routes (simple profile view)
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile.show');

    // Logout route
    Route::post('/logout', function () {
        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});

// Public Forms
Route::get('/found-form', FoundForm::class)->name('found-form');
Route::get('/lost-form', LostForm::class)->name('lost-form');

/*
|--------------------------------------------------------------------------
| Admin Routes - Protected by auth and admin middleware
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Routes
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class)->name('dashboard.home');
    Route::get('/found-form', FoundForm::class)->name('found-form');
    // Lost & Found Management
    Route::get('/lost-found', LostFoundIndex::class)->name('lost-found');

    Route::get('/items', ItemsIndex::class)->name('items');

    Route::get('/categories', CategoriesIndex::class)->name('categories');
    // Users Management
    Route::get('/users', UsersIndex::class)->name('users');

    // Settings (untuk pengembangan selanjutnya)
    // Route::get('/settings', Settings::class)->name('settings');

});
Storage::disk('public')->put('test.txt', 'hello');
Route::middleware(['auth', 'moderator'])->prefix('moderator')->name('moderator.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/categories', App\Livewire\Moderator\categories\Index::class)->name('categories');
    Route::get('/categories/create', App\Livewire\Moderator\categories\Create::class)->name('categories.create');
    Route::get('/categories/{id}/edit', App\Livewire\Moderator\categories\Edit::class)->name('categories.edit');
});
Route::redirect('/admin', '/admin/dashboard');

Route::get('/profile', \App\Livewire\Profile::class)
    ->middleware(['auth'])->name('profile');
