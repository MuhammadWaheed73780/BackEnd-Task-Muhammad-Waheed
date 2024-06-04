<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [LoginController::class, 'login'])->name('api.login');

Route::group(["prefix" => "category"], function () {
    Route::post('/createCat', [CategoryController::class, 'AddCat'])->name('api.AddCat');
    Route::post('/UpdateCat', [CategoryController::class, 'UpdateCat'])->name('api.UpdateCat');
    Route::post('/DeleteCat', [CategoryController::class, 'DeleteCat'])->name('api.DeleteCat');
    Route::post('/ReadCat', [CategoryController::class, 'ReadCat'])->name('api.ReadCat');
});

Route::group(["prefix" => "product"], function () {
    Route::post('/createProduct', [ProductController::class, 'AddProduct'])->name('api.AddProduct');
    Route::post('/UpdateProduct', [ProductController::class, 'UpdateProduct'])->name('api.UpdateProduct');
    Route::post('/DeleteProduct', [ProductController::class, 'DeleteProduct'])->name('api.DeleteProduct');
    Route::post('/ReadProduct', [ProductController::class, 'ReadProduct'])->name('api.ReadProduct');
    Route::post('/FilterProduct', [ProductController::class, 'FilterProduct'])->name('api.FilterProduct');
});
