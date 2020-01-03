<?php

Route::prefix('user')->namespace('User')->group(function () {
    Route::post('register-teacher', 'RegisterController@registerTeacher')->name('user.register.teacher.v1');
});
