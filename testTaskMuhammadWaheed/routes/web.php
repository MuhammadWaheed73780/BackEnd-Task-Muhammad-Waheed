<?php

use Illuminate\Support\Facades\Route;
use App\Http\LangsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::post('/loginPost', [AuthController::class, 'login'])->name('loginPost'); // Login API route

Route::get('/{locale}', function ($locale)
{
    if(!isset($locale))
    {
        $locale = "en";
    }
    App::setLocale($locale);
    return view('Login');
    
})->where('locale', '[a-z]{2}')->name('login');

Route::get('home/{locale}', function ($locale)
{
    if(!isset($locale))
    {
        $locale = "en";
    }
    App::setLocale($locale);
    return view('home');

})->where('locale', '[a-z]{2}')->name('home');


Route::get('/manageProducts/{locale}', [ProductController::class, 'index'])->where('locale', '[a-z]{2}')->name('manage-products');


// Route to access the DeleteProduct page
Route::get('/delete-product/{locale}', [ProductController::class, 'delete'])->where('locale', '[a-z]{2}')->name('delete-product');
// Route to handle form submission for adding a new product
Route::post('/deleteProduct', [ProductController::class, 'DeleteProduct'])->name('deleteProduct');

// Route to access the UpdateProduct page
Route::get('/update-product/{locale}', [ProductController::class, 'update'])->where('locale', '[a-z]{2}')->name('update-product');
// Route to handle form submission for adding a new product
Route::post('/updateProduct', [ProductController::class, 'UpdateProduct'])->name('updateProduct');

// Route to access the AddProduct page
Route::get('/add-product/{locale}', [ProductController::class, 'create'])->where('locale', '[a-z]{2}')->name('add-product');
// Route to handle form submission for adding a new product
Route::post('/addProduct', [ProductController::class, 'AddProduct'])->name('addProduct');

Route::get('/filter-product/{locale}', [ProductController::class, 'filter'])->where('locale', '[a-z]{2}')->name('filter-product');
// Route to handle form submission for adding a new product
Route::post('/filterProduct', [ProductController::class, 'FilterProduct'])->name('filterProduct');


// =================================================================================================================================

Route::get('/manageCategory/{locale}', [CategoryController::class, 'index'])->where('locale', '[a-z]{2}')->name('manage-category');


// Route to access the AddProduct page
Route::get('/delete-category/{locale}', [CategoryController::class, 'delete'])->where('locale', '[a-z]{2}')->name('delete-category');
// Route to handle form submission for adding a new product
Route::post('/deleteCategory', [CategoryController::class, 'DeleteCategory'])->name('deleteCategory');

// Route to access the AddProduct page
Route::get('/update-category/{locale}', [CategoryController::class, 'update'])->where('locale', '[a-z]{2}')->name('update-category');
// Route to handle form submission for adding a new product
Route::post('/updateCategory', [CategoryController::class, 'UpdateCategory'])->name('updateCategory');

// Route to access the AddProduct page
Route::get('/add-category/{locale}', [CategoryController::class, 'create'])->where('locale', '[a-z]{2}')->name('add-category');
// Route to handle form submission for adding a new product
Route::post('/addCategory', [CategoryController::class, 'AddCategory'])->name('addCategory');
  

