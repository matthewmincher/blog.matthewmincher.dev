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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::prefix('v1')->name('api.v1.')->middleware(['forcejson'])->group(function () {
    Route::apiResource('categories', \App\Http\Controllers\Api\V1\CategoryController::class)->parameters([
        'categories' => 'blog_category'
    ]);
    Route::apiResource('tags', \App\Http\Controllers\Api\V1\TagController::class);
});
