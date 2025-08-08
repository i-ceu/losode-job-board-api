<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobPostController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('/v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/jobs', [JobPostController::class, 'index']);
    Route::get('/jobs/{job}', [JobPostController::class, 'show']);
    Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'store']);

    Route::middleware(['api', 'auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);

        Route::post('/my/jobs', [JobPostController::class, 'store']);
        Route::get('/my/jobs', [JobPostController::class, 'myJobs']);
        Route::delete('/my/jobs/{job}', [JobPostController::class, 'destroy']);
        Route::patch('/jobs/{job}', [JobPostController::class, 'update']);

        Route::get('/my/jobs/{job}/applications', [JobApplicationController::class, 'index']);
    });
});
