<?php

use App\Http\Controllers\AdminController;
use App\Http\Middleware\AdminCheck;
use Illuminate\Support\Facades\Route;

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
Route::prefix('app')->middleware([AdminCheck::class])->group(function(){
    Route::post('/create_tag',[AdminController::class, 'addTag']);
    Route::get('/get_tags',[AdminController::class, 'getTag']);
    Route::post('/edit_tag',[AdminController::class, 'editTag']);
    Route::post('/delete_tag',[AdminController::class, 'deleteTag']);
    Route::post('/upload',[AdminController::class, 'upload']);
    Route::post('/delete_image',[AdminController::class, 'deleteImage']);
    Route::post('/create_category',[AdminController::class, 'addCategory']);
    Route::get('/get_category',[AdminController::class, 'getCategory']);
    Route::post('/edit_category',[AdminController::class, 'editCategory']);
    Route::post('/delete_category',[AdminController::class, 'deleteCategory']);
    Route::post('/create_user',[AdminController::class, 'createUser']);
    Route::get('/get_users',[AdminController::class, 'getUsers']);
    Route::post('/edit_user',[AdminController::class, 'editUser']);
    Route::post('/admin_login',[AdminController::class, 'adminLogin']);
});

Route::get('/logout', [AdminController::class, 'logout']);
Route::get('/', [AdminController::class, 'index']);
Route::get('{slug}', [AdminController::class, 'index']);

// Route::get('/', function () {
//     return view('home');
// });

// Auth::routes();

// Route::any('{slug}', function () {
//     return view('home');
// });
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
