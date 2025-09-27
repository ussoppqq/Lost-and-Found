<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\FoundForm;
use App\Livewire\Auth\Forgotpassword;
use App\Livewire\Auth\RegisterExtra;
use App\Livewire\Auth\Login;
use App\Livewire\WhatsappVerification;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Moderator\Dashboard as ModeratorDashboard;



Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('register-extra', RegisterExtra::class)->name('register-extra');

Route::get('found-form', FoundForm::class)->name('found-form');

Route::get('/login', Login::class)->name('login');


Route::get('/forgotpassword', Forgotpassword::class)->name('forgotpassword');

Route::get('/whatsapp-verification', WhatsappVerification::class)
    ->name('whatsapp-verification');


Route::get('/dashboard', \App\Livewire\Admin\Dashboard::class)
    ->middleware(['auth'])->name('dashboard');


Route::get('/lost-form', App\Livewire\LostForm::class)->name('lost-form');


Route::get('/moderator', \App\Livewire\Moderator\Dashboard::class)
    ->middleware(['auth'])
    ->name('moderator');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('home');
})->name('logout');