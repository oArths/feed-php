<?php

use App\Http\Controllers\ArticleController;
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
Route::post('/siguin', [UserContrller::class, 'login_user'])->name('login.user');

Route::prefix('auth')->group(function(){
Route::post('/articles', [ArticleController::class, 'create_article'])->name('create.article')->middleware('jwt');
Route::get('/articles', [ArticleController::class, 'list_article'])->name('list.article')->middleware('jwt');
Route::get('/articles/user/{id?}', [ArticleController::class, 'get_user_article'])->name('list.article')->middleware('jwt');
Route::get('/articles/all/{id?}', [ArticleController::class, 'get_article'])->name('get.article')->middleware('jwt');
Route::delete('/articles/delete/{id?}', [ArticleController::class, 'delete_article'])->name('delete.article')->middleware('jwt');
Route::put('/articles/update/', [ArticleController::class, 'update_article'])->name('update.article')->middleware('jwt');
});