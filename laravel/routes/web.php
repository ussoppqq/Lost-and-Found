<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\FoundForm;
use App\Livewire\Login;
use App\Livewire\Forgotpassword;
use App\Livewire\Auth\RegisterExtra;

Route::get('/', function () {
    return view('livewire.auth.home');
});
Route::get('/found-form', FoundForm::class);

Route::get('register-extra', RegisterExtra::class)->name('register-extra');

Route::get(uri:'/login', action:Login::class)->name(name:'login');

Route::get('/forgot-password', function () {
    return view('livewire.auth.forgotpassword');
})->name('forgot-password');

