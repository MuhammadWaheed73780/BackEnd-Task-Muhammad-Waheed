<?php

use Illuminate\Support\Facades\Route;
use App\Http\LangsController;
use App\Http\Controllers\AuthController;
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

Route::view('/', 'home')->name('home');
Route::view('/login', 'login')->name('login'); // Login page route
Route::view('/category', 'Category')->name('Category'); // Category page route
Route::view('/product', 'Product')->name('Product'); // Product page route


Route::post('/login', [LoginController::class, 'login'])->name('login.post'); // Login API route

Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');