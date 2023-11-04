<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\TableController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Auth
Route::group(['prefix' => 'auth'], function () {
    Route::get('/users', [AuthController::class, 'getAllUsers']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
    Route::get('/check', [AuthController::class, 'checkTokenValid']);
});

//Menus
Route::resource('/menus', MenuController::class)->except(['create', 'edit']);
Route::get('/menus-dashboard', [MenuController::class, 'indexDashboard']);

//Orders
Route::resource('/orders', OrderController::class)->except(['create', 'show', 'edit', 'update']);
Route::put('/orders/{order}', [OrderController::class, 'completeOrder']);
Route::get('/orders/metrics', [OrderController::class, 'metrics']);
Route::get('/orders/weekly', [OrderController::class, 'getWeeklyOrders']);
Route::get('/orders/category', [OrderController::class, 'getOrderByCategory']);

//Tables
Route::resource('/tables', TableController::class)->except(['create', 'edit', 'update']);
Route::put('/tables/{table}', [TableController::class, 'tableFinished']);