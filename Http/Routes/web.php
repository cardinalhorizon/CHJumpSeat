<?php

Route::get('/', 'IndexController@index')->name('index');
Route::get('/create', [\Modules\CHJumpSeat\Http\Controllers\Frontend\IndexController::class, 'create'])->name('create');
Route::post('/', [\Modules\CHJumpSeat\Http\Controllers\Frontend\IndexController::class, 'store'])->name('store');
/*
 * To register a route that needs to be authentication, wrap it in a
 * Route::group() with the auth middleware
 */
// Route::group(['middleware' => 'auth'], function() {
//     Route::get('/', 'IndexController@index');
// })
