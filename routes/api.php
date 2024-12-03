<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\LikeController;

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

Route::get('courses', [CourseController::class, 'index']);
Route::get('courses/search', [CourseController::class, 'search']);
Route::post('courses/{course}/register', [CourseController::class, 'registerUser']);
Route::get('courses/{course}/videos', [CourseController::class, 'getVideos']);
Route::post('courses/{course}/comments', [CommentController::class, 'store']);
Route::post('courses/{course}/like', [LikeController::class, 'store']);
Route::post('courses/{course}/progress', [CourseController::class, 'updateProgress']);
