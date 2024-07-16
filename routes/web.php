<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseVideoController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscribeTransactionController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FrontController::class, 'index'])->name('front.index');

Route::get('/details/{course:slug}', [FrontController::class, 'details'])->name('front.details');

Route::get('/pricing', [FrontController::class, 'pricing'])->name('front.pricing');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // must looged in before create a transaction
    Route::get('/checkout', [FrontController::class, 'checkout'])->name('front.checkout')->middleware('role:student');
    Route::post('/checkout/store', [FrontController::class, 'checkout_store'])->name('front.checkout.store')->middleware('role:student');

    // domain.com/learing/100/5 = belajar course dengan id 100 dan video ke 5
    Route::get('/learing/{course}/{courseVideo}', [FrontController::class, 'learning'])->name('front.learning')->middleware('role:student|teacher|owner ');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('categories', CategoryController::class)->middleware('role:owner');
        Route::resource('teachers', TeacherController::class)->middleware('role:owner|teacher');
        Route::resource('courses', CourseController::class)->middleware('role:owner|teacher');
        Route::resource('subscribe_transactions', SubscribeTransactionController::class)->middleware('role:owner');


        Route::get('/add/video/{course}', [CourseVideoController::class, 'create'])->middleware('role:owner|teacher')->name('course.add_video');

        Route::post('/add/video/{course}', [CourseVideoController::class, 'store'])->middleware('role:owner|teacher')->name('course.add_video.save');

        Route::resource('course_videos', CourseVideoController::class)->middleware('role:owner|teacher');
    });
});

require __DIR__ . '/auth.php';
