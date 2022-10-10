<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\VerifyJWTToken;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/test-string', function () {
    return 'testttttt';
});

Route::controller(ProjectController::class)->prefix('project')->middleware('auth:api')->group(function () {
    Route::get('/getAll', 'index');
    Route::get('/get/{id}', 'getById')->where('id',  '[0-9]+'); 
    Route::post('/add', 'store');
    Route::post('/edit/{id}', 'edit')->where('id',  '[0-9]+'); 
    Route::delete('/delete/{id}', 'deleteById')->where('id',  '[0-9]+');
});

Route::controller(AuthController::class)->prefix('auth')->group(function () {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});