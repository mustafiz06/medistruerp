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
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockMovementController;
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

//product setting route
Route::prefix('productsetting')->group(function () {
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

    Route::get('/product', [ProductController::class, 'index'])->name('product.index');
    Route::get('/product/add', [ProductController::class, 'add'])->name('product.add');
    Route::post('/product/store', [ProductController::class, 'store'])->name('product.store');;
    Route::post('/product/delete/{id}/', [ProductController::class, 'delete'])->name('product.delete');
    Route::post('/product/status/{id}/', [ProductController::class, 'status'])->name('product.status');
    Route::get('/product/edit/{id}', [ProductController::class, 'edit_view'])->name('product.edit.view');
    Route::post('/product/edit/{id}/', [ProductController::class, 'update'])->name('product.update');
    Route::get('/barcode/generate/{product}', [BarcodeController::class, 'generate'])->name('product.barcode.generate');
});

//account setting route
Route::prefix('accountsetting')->group(function () {
    Route::get('/paymentmethod', [PaymentMethodController::class, 'index'])->name('paymentMethod.index');
    Route::post('/paymentmethod/store', [PaymentMethodController::class, 'store'])->name('paymentMethod.store');;
    Route::post('/paymentmethod/delete/{id}/', [PaymentMethodController::class, 'delete'])->name('paymentMethod.delete');
    Route::post('/paymentmethod/status/{id}/', [PaymentMethodController::class, 'status'])->name('paymentMethod.status');
    Route::post('/paymentmethod/edit/{id}/', [PaymentMethodController::class, 'edit'])->name('paymentMethod.edit');

    Route::get('/expensehead', [ExpenseHeadController::class, 'index'])->name('expenseHead.index');
    Route::post('/expensehead/store', [ExpenseHeadController::class, 'store'])->name('expenseHead.store');;
    Route::post('/expensehead/delete/{id}/', [ExpenseHeadController::class, 'delete'])->name('expenseHead.delete');
    Route::post('/expensehead/status/{id}/', [ExpenseHeadController::class, 'status'])->name('expenseHead.status');
    Route::post('/expensehead/edit/{id}/', [ExpenseHeadController::class, 'edit'])->name('expenseHead.edit');
});

//customer route
Route::prefix('customer')->group(function () {
    Route::get('/', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/add', [CustomerController::class, 'add'])->name('customer.add');
    Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');;
    Route::post('/delete/{id}/', [CustomerController::class, 'delete'])->name('customer.delete');
    Route::post('/status/{id}/', [CustomerController::class, 'status'])->name('customer.status');
    Route::get('/edit/{id}', [CustomerController::class, 'edit_view'])->name('customer.edit.view');
    Route::post('/edit/{id}/', [CustomerController::class, 'update'])->name('customer.update');
});

//supplier route
Route::prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/add', [SupplierController::class, 'add'])->name('supplier.add');
    Route::post('/store', [SupplierController::class, 'store'])->name('supplier.store');;
    Route::post('/delete/{id}/', [SupplierController::class, 'delete'])->name('supplier.delete');
    Route::post('/status/{id}/', [SupplierController::class, 'status'])->name('supplier.status');
    Route::get('/edit/{id}', [SupplierController::class, 'edit_view'])->name('supplier.edit.view');
    Route::post('/edit/{id}/', [SupplierController::class, 'update'])->name('supplier.update');
});

//stock route
Route::prefix('stock')->group(function () {
    Route::get('/opening', [StockController::class, 'openingStockForm'])->name('stock.openingForm');
    Route::post('/opening/save', [StockController::class, 'openingStockSave'])->name('stock.openingSave');
    Route::get('/report', [StockController::class, 'stockReport'])->name('stock.report');
});


Route::prefix('purchase-orders')->group(function () {
    Route::get('/', [PurchaseOrderController::class, 'poList'])->name('po.list');
    Route::get('/add', [PurchaseOrderController::class, 'create'])->name('po.create');
    Route::get('/products/price/{id}', [PurchaseOrderController::class, 'getProductPrice'])->name('products.price');
    Route::post('/store', [PurchaseOrderController::class, 'store'])->name('po.store');
    Route::post('/destroy/{id}', [PurchaseOrderController::class, 'destroy'])->name('po.destroy');
    Route::get('/view/{id}', [PurchaseOrderController::class, 'view'])->name('po.view');
});
