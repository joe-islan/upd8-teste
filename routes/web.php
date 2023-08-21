<?php

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

Route::get('/', function()
{
    return redirect()->route('clientes.index');
});

Route::prefix('clients')->group(function () {
    Route::get('/', [\App\Http\Controllers\ClientFrontController::class, 'index'])->name('clientes.index');
    Route::get('create', [\App\Http\Controllers\ClientFrontController::class, 'create'])->name('clientes.create');
    Route::get('edit/{id}', [\App\Http\Controllers\ClientFrontController::class, 'edit'])->name('clientes.edit');
    Route::get('{id}', [\App\Http\Controllers\ClientFrontController::class, 'show'])->name('clientes.show');
});