<?php

use App\Http\Controllers\Auth\AppController;
use App\Http\Controllers\Frontend\AppController as FrontendAppController;
use App\Http\Controllers\FileController;

use Illuminate\Support\Facades\Route;

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

// client

// admin
// Route::get('/login', [AppController::class, 'login'])->name('login');
// Route::post('/login-check', [AppController::class, 'loginCheck'])->name('login-check');
// Route::get('/register', [AppController::class, 'register'])->name('register');
// Route::post('/logout', [AppController::class, 'logout'])->name('logout');


// Route::middleware(['auth:admin'])->group(function(){
//     dd('admin page');
// });

// Example of applying middleware to a group of routes

Route::get('emailtemplate', function () {
    return view('email.template');
});

// Route::get('/update-migration', function()
// {
//     Artisan::call('migrate:refresh');
//     return 'Success';

// });

// Frontend
Route::get('/', [FrontendAppController::class, 'home'])->name('home');
Route::get('/diensten', [FrontendAppController::class, 'services'])->name('services');
Route::get('/dienst/{slug}', [FrontendAppController::class, 'serviceDetail'])->name('service.detail');
Route::get('/blog', [FrontendAppController::class, 'blog'])->name('blog');
Route::get('/blog/{slug}', [FrontendAppController::class, 'blogDetail'])->name('blog.detail');
Route::get('/tarieven', [FrontendAppController::class, 'pricing'])->name('tarieven');
Route::get('/contact', [FrontendAppController::class, 'contact'])->name('contact');
Route::get('/over-ons', [FrontendAppController::class, 'about'])->name('about');
Route::get('/asbest', [FrontendAppController::class, 'about'])->name('asbest');

// Order
// Route::get('/keuring-aanvragen', [OrderController::class, 'index']);
Route::group(['prefix' => 'order'], function () {
    Route::get('/get-services/{city}', [App\Http\Controllers\Frontend\OrderController::class, 'getServices']);
    Route::get('/get-sub-services/{service}', [App\Http\Controllers\Frontend\OrderController::class, 'getSubServices']);
});

// App
// Authentication Routes
// Route::get('/', [AppController::class, 'showLoginForm'])->name('login');
Route::get('/login', [AppController::class, 'showLoginForm'])->name('login');
Route::post('login', [AppController::class, 'authenticate'])->name('login.authenticate');
Route::post('logout', [AppController::class, 'destroy'])->name('logout');

// Secure File Routes - Tüm kullanıcı tipleri için erişilebilir
Route::get('documents/{hash}', [FileController::class, 'show'])
    ->name('files.show')
    ->middleware(['auth:tenant,employee,client', 'signed']);

Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('combi_discounts', \App\Http\Controllers\CombiDiscountController::class);
});
