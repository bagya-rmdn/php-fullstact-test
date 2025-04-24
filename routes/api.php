<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyClientController;
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

Route::post('clients', [MyClientController::class, 'store'])->name('clients.store');
Route::put('clients/{client}', [MyClientController::class, 'update'])->name('clients.update');
Route::delete('clients/{client}', [MyClientController::class, 'destroy'])->name('clients.destroy');
