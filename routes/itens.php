<?php

use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Consulta pública de itens (RF14 a RF17)
|--------------------------------------------------------------------------
*/

Route::get('/itens', [ItemController::class, 'index'])->name('itens.index');
