<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;

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

Route::controller(ProjectController::class)->prefix('project')->group(function () {
    Route::get('/getAll', 'index');
    Route::post('/add', 'store');
    Route::post('/edit', 'edit');
    Route::delete('/delete/{id}', 'deleteById');
});

