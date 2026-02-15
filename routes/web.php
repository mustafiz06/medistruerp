<?php

use App\Http\Controllers\accountSetting\PaymentMethodController;
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
Route::get('/productsetting/category', [CategoryController::class, 'index'])->name('category.index');
Route::post('/productsetting/category/store', [CategoryController::class, 'store'])->name('category.store');;
Route::post('/productsetting/category/delete/{id}/', [CategoryController::class, 'delete'])->name('category.delete');
Route::post('/productsetting/category/status/{id}/', [CategoryController::class, 'status'])->name('category.status');
Route::post('/productsetting/category/update/{id}/', [CategoryController::class, 'update'])->name('category.update');


Route::get('/productsetting/unit', [UnitController::class, 'index'])->name('unit.index');
Route::post('/productsetting/unit/store', [UnitController::class, 'store'])->name('unit.store');;
Route::post('/productsetting/unit/delete/{id}/', [UnitController::class, 'delete'])->name('unit.delete');
Route::post('/productsetting/unit/status/{id}/', [UnitController::class, 'status'])->name('unit.status');
Route::post('/productsetting/unit/edit/{id}/', [UnitController::class, 'edit'])->name('unit.edit');


Route::get('/productsetting/brand', [BrandController::class, 'index'])->name('brand.index');
Route::post('/productsetting/brand/store', [BrandController::class, 'store'])->name('brand.store');;
Route::post('/productsetting/brand/delete/{id}/', [BrandController::class, 'delete'])->name('brand.delete');
Route::post('/productsetting/brand/status/{id}/', [BrandController::class, 'status'])->name('brand.status');
Route::post('/productsetting/brand/edit/{id}/', [BrandController::class, 'edit'])->name('brand.edit');


Route::get('/productsetting/country', [CountryController::class, 'index'])->name('country.index');
Route::post('/productsetting/country/store', [CountryController::class, 'store'])->name('country.store');;
Route::post('/productsetting/country/delete/{id}/', [CountryController::class, 'delete'])->name('country.delete');
Route::post('/productsetting/country/status/{id}/', [CountryController::class, 'status'])->name('country.status');
Route::post('/productsetting/country/edit/{id}/', [CountryController::class, 'edit'])->name('country.edit');


Route::get('/accountsetting/paymentmethod', [PaymentMethodController::class, 'index'])->name('paymentMethod.index');
Route::post('/accountsetting/paymentmethod/store', [PaymentMethodController::class, 'store'])->name('paymentMethod.store');;
Route::post('/accountsetting/paymentmethod/delete/{id}/', [PaymentMethodController::class, 'delete'])->name('paymentMethod.delete');
Route::post('/accountsetting/paymentmethod/status/{id}/', [PaymentMethodController::class, 'status'])->name('paymentMethod.status');
Route::post('/accountsetting/paymentmethod/edit/{id}/', [PaymentMethodController::class, 'edit'])->name('paymentMethod.edit');