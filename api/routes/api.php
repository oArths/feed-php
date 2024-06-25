<?php

use App\Http\Controllers\UserContrller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('/singup', [UserContrller::class, 'create_user'])->name('create.user');
Route::post('/siguin', [UserContrller::class, 'login_user'])->name('login.user')->middleware('jwt');