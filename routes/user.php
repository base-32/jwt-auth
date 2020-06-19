<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication user routes.
|--------------------------------------------------------------------------
|
| This route is home of the route that will allow you to get the currently logged in user.
|
*/

Route::get(config('jwt-auth.current_user.route'), 'UserController@find');
