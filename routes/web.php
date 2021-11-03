<?php

use Auth0\Login\Auth0Controller;
use App\Http\Controllers\Auth\Auth0IndexController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Models\BlogPost;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/auth0/callback', [Auth0Controller::class, 'callback'])->name('auth0-callback');
Route::get('/login', [Auth0IndexController::class, 'login'])->name('login');
Route::get('/logout', [Auth0IndexController::class, 'logout'])->name('logout');


Route::resource('categories', CategoryController::class)->except('show')->parameters([
    'categories' => 'blog_category'
]);
Route::get('categories/{blog_category:combined_slug}', [CategoryController::class, 'show'])->name('categories.show');

Route::resource('tags', TagController::class)->except('show')->parameters([
    'tags' => 'blog_tag'
]);
Route::get('tags/{blog_tag:combined_slug}', [TagController::class, 'show'])->name('tags.show');

Route::resource('posts', PostController::class)->except('show')->parameters([
    'posts' => 'blog_post'
]);
Route::get('posts/{blog_post:combined_slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/', function () {
    return redirect()->route('posts.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

