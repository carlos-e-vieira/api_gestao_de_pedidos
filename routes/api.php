<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

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

Route::prefix('v1')->group(function () {
    Route::get('cliente', [ClienteController::class, 'index']);
    Route::post('cliente', [ClienteController::class, 'store']);
    Route::get('cliente/{id}', [ClienteController::class, 'show']);
    Route::put('cliente/{id}', [ClienteController::class, 'update']);
    Route::patch('cliente/{id}', [ClienteController::class, 'update']);
});
