<?php

use App\Http\Controllers\PusherController;
use Illuminate\Support\Facades\Route;


/*Route::get('/', 'App\Http\Controllers\PusherController@index');
Route::post('/broadcast', 'App\Http\Controllers\PusherController@broadcast');
Route::post('/receive', 'App\Http\Controllers\PusherController@receive');*/
Route::get('/', [PusherController::class, 'index']);
Route::post('/broadcast', [PusherController::class, 'broadcast']);
Route::post('/receive', [PusherController::class, 'receive']);
