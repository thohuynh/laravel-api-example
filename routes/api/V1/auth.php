<?php

Route::prefix('auth')->namespace('Auth')->group(function () {
    Route::post('login', 'LoginController@login')->name('auth.login');
    Route::middleware(['jwt'])->group(function () {
        Route::get('me', 'LoginController@me')->name('auth.me');
        Route::post('logout', 'LoginController@logout')->name('auth.logout');
    });
});
