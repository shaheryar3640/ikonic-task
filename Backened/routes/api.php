<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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
Route::post('/Auth/login', [UserController::class, 'login']);
Route::post('/Auth/register', [UserController::class, 'register']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/Auth/user', [UserController::class, 'user']);
    Route::get('/Auth/logout', [UserController::class, 'logout']);
    Route::controller(FeedbackController::class)->group(function() {
        Route::get('/all-feedback', 'index');
        Route::get('/show/feedback/{id}', 'show');
        Route::post('/create/feedback', 'store');
        Route::post('/update/feedback', 'update');
        Route::get('/edit/feedback/{id}', 'edit');
        Route::post('/feedback/delete', 'destroy');
        Route::get('/all-category', 'categories');
        Route::get('/feedback/comment/{id}', 'feedbackComment');
    });
    Route::controller(CommentController::class)->group(function() {
        Route::get('/all/user', 'allUser');
        Route::post('/create/comment', 'store');
        Route::post('/create/newComment', 'newComment');
    });
    
});

