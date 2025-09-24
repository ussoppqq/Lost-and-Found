<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\RegisterPhone;
use App\Livewire\Auth\VerifyOtp;
use App\Livewire\Auth\RegisterExtra;
use App\Livewire\FoundForm;



Route::get('/', function () {
    return view('livewire.auth.home');
});
Route::get('/found-form', FoundForm::class);

Route::get('register-extra', RegisterExtra::class)->name('register-extra');

Route::get('/login', function () {
    return view('livewire.auth.login');
})->name('login');

Route::get('/found', function () {
    return view('found');
})->name('found');