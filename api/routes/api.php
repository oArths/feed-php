<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UserContrller;
use App\Http\Controllers\TagController;
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


Route::post('/singup', [UserContrller::class, 'create_user'])->name('create.user');
Route::post('/siguin', [UserContrller::class, 'login_user'])->name('login.user');

Route::prefix('auth')->group(function(){
    Route::post('/siguin/update', [UserContrller::class, 'update_user'])->name('update.user')->middleware('jwt');
    
    Route::post('/articles', [ArticleController::class, 'create_article'])->name('create.article')->middleware('jwt');
    Route::get('/articles', [ArticleController::class, 'list_article'])->name('list.article')->middleware('jwt');
    Route::get('/articles/user/{id?}', [ArticleController::class, 'get_user_article'])->name('list.article')->middleware('jwt');
    Route::get('/articles/all/{id?}', [ArticleController::class, 'get_article'])->name('get.article')->middleware('jwt');
    Route::delete('/articles/delete/{id?}', [ArticleController::class, 'delete_article'])->name('delete.article')->middleware('jwt');
    Route::put('/articles/update/', [ArticleController::class, 'update_article'])->name('update.article')->middleware('jwt');
    Route::get('/articles/recently', [ArticleController::class, 'recently_article'])->name('rencently.article')->middleware('jwt');
    Route::get('/articles/recently/user/{userid?}', [ArticleController::class, 'articleTagsUser'])->name('rencently.user.article')->middleware('jwt');
    Route::get('/articles/tags/user/{userId?}', [ArticleController::class, 'TagsArticleUser'])->name('tags.user.article')->middleware('jwt');

    Route::post('/comment', [CommentController::class, 'Create_Comment'])->name('create.comment')->middleware('jwt');
    Route::put('/comment/update', [CommentController::class, 'Update_comment'])->name('update.comment')->middleware('jwt');
    Route::delete('/comment/delete/{id?}', [CommentController::class, 'delete_comment'])->name('delete.comment')->middleware('jwt');
    Route::get('/comment/all/{id?}', [CommentController::class, 'list_comment'])->name('list.comment')->middleware('jwt');
    
    Route::post('/tag/create', [TagController::class, 'create_tag'])->name('create.tag')->middleware('jwt');
    Route::get('/tag/all', [TagController::class, 'get_tags'])->name('gat.tag')->middleware('jwt');
});