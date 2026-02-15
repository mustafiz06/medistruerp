<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\UnitController;
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


Route::get('/unit', [UnitController::class, 'index'])->name('unit.index');
Route::post('/unit/store', [UnitController::class, 'store'])->name('unit.store');;
Route::post('/unit/delete/{id}/', [UnitController::class, 'delete'])->name('unit.delete');
Route::post('/unit/status/{id}/', [UnitController::class, 'status'])->name('unit.status');
Route::post('/unit/edit/{id}/', [UnitController::class, 'edit'])->name('unit.edit');


Route::get('/brand', [BrandController::class, 'index'])->name('brand.index');
Route::post('/brand/store', [BrandController::class, 'store'])->name('brand.store');;
Route::post('/brand/delete/{id}/', [BrandController::class, 'delete'])->name('brand.delete');
Route::post('/brand/status/{id}/', [BrandController::class, 'status'])->name('brand.status');
Route::post('/brand/edit/{id}/', [BrandController::class, 'edit'])->name('brand.edit');


Route::get('/country', [CountryController::class, 'index'])->name('country.index');
Route::post('/country/store', [CountryController::class, 'store'])->name('country.store');;
Route::post('/country/delete/{id}/', [CountryController::class, 'delete'])->name('country.delete');
Route::post('/country/status/{id}/', [CountryController::class, 'status'])->name('country.status');
Route::post('/country/edit/{id}/', [CountryController::class, 'edit'])->name('country.edit');