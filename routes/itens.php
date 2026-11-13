<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Consulta pública de itens (RF14 a RF17) e página de detalhe do item (RF16)
|--------------------------------------------------------------------------
*/

Route::get('/itens', [ItemController::class, 'index'])->name('itens.index');
Route::get('/itens/{item}', [ItemController::class, 'show'])->whereNumber('item')->name('itens.show');
