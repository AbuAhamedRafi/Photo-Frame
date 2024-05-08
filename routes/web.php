<?php

use App\Http\Controllers\FrameController;
use App\Http\Controllers\FrameController2;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/frame-generator', [FrameController::class, 'index']);

Route::get('/frame-generator2', [FrameController2::class, 'index']);

Route::post('/upload-image', [ImageController::class, 'index'])->name('upload.image');