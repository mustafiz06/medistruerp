<?php

use App\Http\Controllers\accountSetting\ExpenseHeadController;
use App\Http\Controllers\accountSetting\PaymentMethodController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\productSetting\BrandController;
use App\Http\Controllers\productSetting\CategoryController;
use App\Http\Controllers\productSetting\CountryController;
use App\Http\Controllers\productSetting\ProductController;
use App\Http\Controllers\productSetting\UnitController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
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
})->name('home');


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



Route::get('/accountsetting/expensehead', [ExpenseHeadController::class, 'index'])->name('expenseHead.index');
Route::post('/accountsetting/expensehead/store', [ExpenseHeadController::class, 'store'])->name('expenseHead.store');;
Route::post('/accountsetting/expensehead/delete/{id}/', [ExpenseHeadController::class, 'delete'])->name('expenseHead.delete');
Route::post('/accountsetting/expensehead/status/{id}/', [ExpenseHeadController::class, 'status'])->name('expenseHead.status');
Route::post('/accountsetting/expensehead/edit/{id}/', [ExpenseHeadController::class, 'edit'])->name('expenseHead.edit');


Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/customer/add', [CustomerController::class, 'add'])->name('customer.add');
Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');;
Route::post('/customer/delete/{id}/', [CustomerController::class, 'delete'])->name('customer.delete');
Route::post('/customer/status/{id}/', [CustomerController::class, 'status'])->name('customer.status');
Route::get('/customer/edit/{id}', [CustomerController::class, 'edit_view'])->name('customer.edit.view');
Route::post('/customer/edit/{id}/', [CustomerController::class, 'update'])->name('customer.update');


Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
Route::get('/supplier/add', [SupplierController::class, 'add'])->name('supplier.add');
Route::post('/supplier/store', [SupplierController::class, 'store'])->name('supplier.store');;
Route::post('/supplier/delete/{id}/', [SupplierController::class, 'delete'])->name('supplier.delete');
Route::post('/supplier/status/{id}/', [SupplierController::class, 'status'])->name('supplier.status');
Route::get('/supplier/edit/{id}', [SupplierController::class, 'edit_view'])->name('supplier.edit.view');
Route::post('/supplier/edit/{id}/', [SupplierController::class, 'update'])->name('supplier.update');



Route::get('/productsetting/product', [ProductController::class, 'index'])->name('product.index');
Route::get('/productsetting/product/add', [ProductController::class, 'add'])->name('product.add');
Route::post('/productsetting/product/store', [ProductController::class, 'store'])->name('product.store');;
Route::post('/productsetting/product/delete/{id}/', [ProductController::class, 'delete'])->name('product.delete');
Route::post('/productsetting/product/status/{id}/', [ProductController::class, 'status'])->name('product.status');
Route::get('/productsetting/product/edit/{id}', [ProductController::class, 'edit_view'])->name('product.edit.view');
Route::post('/productsetting/product/edit/{id}/', [ProductController::class, 'update'])->name('product.update');



Route::get('/productsetting/barcode/generate/{product}', [BarcodeController::class, 'generate'])->name('product.barcode.generate');




Route::prefix('stock')->group(function(){
    Route::get('/opening', [StockController::class,'openingStockForm'])->name('stock.openingForm');
    Route::post('/opening/save', [StockController::class,'openingStockSave'])->name('stock.openingSave');

});