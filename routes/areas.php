<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Áreas restritas por perfil (RF05)
|--------------------------------------------------------------------------
*/

// Somente administrador.
Route::middleware(['auth', 'perfil:administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::view('/', 'admin.painel')->name('painel');
    });

// Servidor da DDI e administrador.
Route::middleware(['auth', 'perfil:servidor_ddi,administrador'])
    ->prefix('ddi')
    ->name('ddi.')
    ->group(function () {
        Route::view('/', 'ddi.painel')->name('painel');
    });
