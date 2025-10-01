<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\FoundForm;
use App\Livewire\LostForm;
use App\Livewire\Auth\Forgotpassword;
use App\Livewire\Auth\RegisterExtra;
use App\Livewire\Auth\Login;
use App\Livewire\WhatsappVerification;


use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\LostAndFound\Index as LostFoundIndex;
use App\Livewire\Admin\Users\Index as UsersIndex;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/


Route::get('/', function () {
    return view('home');
})->name('home');


// Auth Routes
Route::get('/login', Login::class)->name('login');
Route::get('register-extra', RegisterExtra::class)->name('register-extra');
Route::get('/forgotpassword', Forgotpassword::class)->name('forgotpassword');
Route::get('/whatsapp-verification', WhatsappVerification::class)->name('whatsapp-verification');


Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)
    ->middleware(['auth'])->name('dashboard');

Route::get('/moderator', \App\Livewire\Moderator\Dashboard::class)
    ->middleware(['auth'])
    ->name('moderator');

Route::middleware(['auth'])->group(function () {
    // User profile routes (simple profile view)
    Route::get('/profile', function () {
        return view('profile'); // Atau redirect ke dashboard
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
    
    // Lost & Found Management
    Route::get('/lost-found', LostFoundIndex::class)->name('lost-found');
    
    // Users Management  
    Route::get('/users', UsersIndex::class)->name('users');
    
    // Settings (untuk pengembangan selanjutnya)
    // Route::get('/settings', Settings::class)->name('settings');
    
});

Route::redirect('/admin', '/admin/dashboard');

Route::get('/profile', \App\Livewire\Profile::class)
    ->middleware(['auth'])->name('profile');