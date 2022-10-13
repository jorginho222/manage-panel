<?php

use App\Http\Controllers\InvoiceController;
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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('admin.dashboard');
    // })->name('dashboard');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::get('/providers', function () {
    return view('admin.providers');
})->name('providers.index');

Route::get('/supplies', function () {
    return view('admin.supplies');
})->name('supplies.index');

Route::get('/buy-orders', function () {
    return view('admin.buy-orders');
})->name('buy-orders.index');

Route::get('/clients', function () {
    return view('admin.clients');
})->name('clients.index');

Route::get('/products', function () {
    return view('admin.products');
})->name('products.index');

Route::get('/sell-orders', function () {
    return view('admin.sell-orders');
})->name('sell-orders.index');

Route::get('/buy-orders/{buyOrder}/generate-invoice', [InvoiceController::class, 'generateInvoice'])->name('invoice.generate');
