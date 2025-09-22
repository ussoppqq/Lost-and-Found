<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\RegisterPhone;
use App\Livewire\Auth\VerifyOtp;
use App\Livewire\Auth\RegisterExtra;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/forms', function () {
    return view('forms.form');
});
Route::get('/register-phone', function () {
    return view('livewire.auth.register-phone');
});
Route::get('/verify-otp', function () {
    return view('livewire.auth.verify-otp');
});
Route::get('/register-extra', function () {
    return view('livewire.auth.register-extra');
});