<?php

use App\Http\Controllers\CategoryController;
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

Route::get('/', function () {
    return view('welcome');
});


// Category  Route
Route::get('/category', [CategoryController::class, 'index'])->name('category.index');
Route::post('/category/store', [CategoryController::class, 'store'])->name('category.store');;
Route::post('/category/delete/{id}/', [CategoryController::class, 'delete'])->name('category.delete');
Route::post('/category/status/{id}/', [CategoryController::class, 'status'])->name('category.status');
Route::post('/category/update/{id}/', [CategoryController::class, 'update'])->name('category.update');
