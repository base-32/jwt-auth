<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication API Routes
|--------------------------------------------------------------------------
|
| Here are the authentication routes that are already pre-configured with the 
| JWTAuth package.
|
*/

Route::post('/login', 'AuthController@login');
Route::post('/forgot-password', 'PasswordResetController@sendResetEmail')->name('password.reset');
Route::post('/verify-token', 'PasswordResetController@verifyPasswordReset');
Route::post('/reset-password', 'PasswordResetController@resetPassword');
