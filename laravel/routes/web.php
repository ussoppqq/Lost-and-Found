<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\FoundForm;
use App\Livewire\Auth\Forgotpassword;
use App\Livewire\Auth\RegisterExtra;
use App\Livewire\Auth\Login;
use App\Livewire\WhatsappVerification;
use App\Livewire\Admin\Dashboard;


Route::get('/', function () {
    return view('home');
});

Route::get('register-extra', RegisterExtra::class)->name('register-extra');

Route::get('found-form', FoundForm::class)->name('found-form');

Route::get('/login', Login::class)->name('login');

Route::get('/forgotpassword', Forgotpassword::class)->name('forgotpassword');

Route::get('/whatsapp-verification', WhatsappVerification::class)
    ->name('whatsapp-verification');

Route::get('/lost-form', App\Livewire\LostForm::class)->name('lost-form');

