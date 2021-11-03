<?php

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

Route::prefix('v1')->name('api.v1.')->middleware(['forcejson', 'auth:api'])->group(function () {
    Route::apiResource('categories', \App\Http\Controllers\Api\V1\CategoryController::class)->parameters([
        'categories' => 'blog_category'
    ]);
    Route::apiResource('posts', \App\Http\Controllers\Api\V1\PostController::class)->parameters([
        'posts' => 'blog_post'
    ]);
    Route::apiResource('tags', \App\Http\Controllers\Api\V1\TagController::class)->parameters([
        'tags' => 'blog_tag'
    ]);
});
