<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AlbumBelongsToUser;
use App\Http\Controllers\V1\AlbumController;
use App\Http\Controllers\V1\ImageManipulationController;
use App\Http\Middleware\ImageBelongsToUser;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function(){
    Route::prefix('v1')->group(function(){
        Route::get('album', [AlbumController::class, 'index']);
        Route::post('album', [AlbumController::class, 'store']);
        
        Route::middleware(ImageBelongsToUser::class)->group(function(){
            Route::get('image/{image}', [ImageManipulationController::class, 'show']);
            Route::delete('image/{image}', [ImageManipulationController::class, 'destroy']);
        });

        Route::get('image', [ImageManipulationController::class, 'index']);
        
        Route::middleware(AlbumBelongsToUser::class)->group(function(){
            Route::get('album/{album}', [AlbumController::class, 'show']);
            Route::put('album/{album}', [AlbumController::class, 'update']);
            Route::delete('album/{album}', [AlbumController::class, 'destroy']);
            Route::get('image/by-album/{album}', [ImageManipulationController::class, 'byAlbum']);
            Route::post('image/resize', [ImageManipulationController::class, 'resize']);
            Route::post('image/text', [ImageManipulationController::class, 'addText']);
        });
    });    
});