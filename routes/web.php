<?php

use App\Livewire\CoursesManager\CoursesManager;
use App\Livewire\CourseVideos\CourseVideos;
use App\Livewire\StudentManager\StudentManager;
use App\Livewire\VideoManager;
use App\Livewire\VideosManager\VideosManager;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('layouts.app');
});
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/courses', CoursesManager::class)->name('courses');
    Route::get('/videos', VideosManager::class)->name('videos');
    Route::get('/student', StudentManager::class)->name('student');
    Route::get('/courses/{course}/videos', CourseVideos::class)->name('course.videos');
});
