<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\FoundForm;
use App\Livewire\Login;
use App\Livewire\Forgotpassword;
use App\Livewire\Auth\RegisterExtra;



Route::get('/', function () {
    return view('livewire.auth.home');
});
Route::get('/register-phone', function () {
    return view('livewire.auth.register-phone');
});
Route::get('/verify-otp', function () {
    return view('livewire.auth.verify-otp');
});
Route::get('/register', App\Livewire\Auth\RegisterExtra::class)->name('register');
Route::get('/login', App\Livewire\Login::class)->name('login');
Route::get('/forgot-password', App\Livewire\Forgotpassword::class)->name('forgot-password');
Route::get('/found', App\Livewire\FoundForm::class)->name('found');