<?php

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

/**
 * Include all file api route by modules.
 */
Route::namespace('Api')->group(function () {
    Route::prefix('v1')->namespace('V1')->group(function () {
        foreach (glob(__DIR__ . '/api/V1/' . '*.php') as $filename) {
            require_once "$filename";
        }
    });
});
