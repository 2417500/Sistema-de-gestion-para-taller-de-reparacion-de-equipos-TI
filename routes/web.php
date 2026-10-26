<?php

use Illuminate\Support\Facades\Route;

// Portal Público (Landing + Rastreador de Órdenes + Acceso)
Route::get('/', function () {
    return view('public.portal');
})->name('portal.publico');

// Consola Operativa Privada Multi-Rol (Prussian Precision - Modo Claro)
Route::get('/dashboard', function () {
    return view('private.dashboard');
})->name('dashboard');

// Consola de Diagnóstico Técnico (Technical Noir Precision - Modo Oscuro)
Route::get('/dashboard/noir', function () {
    return view('private.dashboard-noir');
})->name('dashboard.noir');
