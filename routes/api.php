<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',[AuthController::class,'register']);
Route::get('/login', [AuthController::class, 'login']);


Route::group(['middleware'=>['auth:sanctum']],
function(){
   Route::post('/logout', [AuthController::class,'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::put('/user', [AuthController::class, 'updateu']); 

        // post 
        Route::get('/posts',[PostController::class,'index']);
        Route::post('/posts',[PostController::class,'store']);
        Route::get('/posts/{id}',[PostController::class,'show']);
        Route::put('/posts/{id}',[PostController::class,'update']);
        Route::delete('/posts/{id}', [PostController::class, 'destroy']);

        // comments 
        Route::get('/posts/{id}/comments',[CommentController::class,'index']); //all comments
        Route::post('/posts/{id}/comments',[CommentController::class,'store']); //create comments
        Route::put('comments/{id}',[CommentController::class,'update']); //create comments
        Route::delete('comments/{id}', [CommentController::class, 'destroy']); //create comments
        
        // like 
        Route::post('/posts/{id}/likes',[LikeController::class,'likeOrUnlike']);

});