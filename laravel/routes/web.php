<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\FoundForm;
use App\Livewire\Forgotpassword;
use App\Livewire\Auth\RegisterExtra;
use App\Livewire\Login;
use App\Livewire\WhatsappVerification;

Route::get('/', function () {
    return view('livewire.auth.home');
});


Route::get('register-extra', RegisterExtra::class)->name('register-extra');

Route::get('found-form', FoundForm::class)->name('found-form');

Route::get('login', Login::class)->name('login');

Route::get('/forgotpassword', Forgotpassword::class)->name('forgotpassword');

Route::get('/whatsapp-verification', WhatsappVerification::class)
    ->name('whatsapp-verification');