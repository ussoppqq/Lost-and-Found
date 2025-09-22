<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\RegisterPhone;
use App\Livewire\Auth\VerifyOtp;
use App\Livewire\Auth\RegisterExtra;

Route::get('/register-phone', RegisterPhone::class)->name('register-phone');
Route::get('/verify-otp', VerifyOtp::class)->name('verify-otp');
Route::get('/register-extra', RegisterExtra::class)->name('register-extra');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/forms', function () {
    return view('forms.form');
});
