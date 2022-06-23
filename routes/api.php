<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    ['namespace' => 'Auth', 'prefix' => 'auth'],
    function () {
        // Login
        Route::post('login', 'AccessController@store');

        // Verify email
        Route::get(
            'email/verify/{id}',
            'VerificationController@verify'
        )->name('verification.verify');

        // Resend verification email
        Route::get(
            'email/resend',
            'VerificationController@resend'
        )->name('verification.resend');

        Route::group(
            ['middleware' => ['auth:sanctum']],
            function () {

                // Logout
                Route::delete('logout', 'AccessController@destroy');

                // Get profile
                Route::get('user', 'UserController@show');

                // Update profile
                Route::put('user', 'UserController@update');
            }
        );
    }
);

Route::group(
    ['middleware' => ['auth:sanctum', 'verified']],
    function () {

        Route::post('/orders/calculatePrice', 'OrderController@calculatePrice');
        
        Route::apiResource('orders', 'OrderController')->only(['index', 'store', 'show']);
        Route::apiResource('locations', 'LocationController')->only(['index']);

    }
);