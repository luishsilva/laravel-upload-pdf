<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;

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


Route::group([
    'middleware' => ['api'],
    'prefix'     => 'auth'
], function () {
    Route::post('/register', [AuthController::class, 'register'])->name('user.register');
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');
});


Route::group([
    'middleware' => ['api'],
    'prefix'     => 'upload'
], function () {
    Route::post('store', [FileController::class, 'store'])->name('upload.store');
    Route::get('list', [FileController::class, 'index'])->name('upload.list');
});
