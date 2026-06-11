<?php

use Illuminate\Support\Facades\Route;

/** Mis controladores **/
// use App\Http\Controllers\AbonosController;
// use App\Http\Controllers\UsuariosController;




/** Rutas que ejecutan controllers + action **/

// landing
// Route::get('/',[AbonosController::class, 'index'])->name('abonos.index');
// Route::get('/abonos/index',[AbonosController::class, 'index'])->name('abonos.index');


// ---- Muestra formulario de compra
// Route::get('/',[AbonosController::class, 'compra'])->name('abonos.compra');
// Route::get('/abonos/compra',[AbonosController::class, 'compra'])->name('abonos.compra');
// ruta:  '/' ó '/compra'
// accion: compra() de AbonosController
// nombre para la ruta: abonos.compra 
// ---- Realiza registro de compra en BD -- tabla abonos
// Route::post('/abonos/insert',[AbonosController::class, 'insert'])->name('abonos.insert');


// ---- Muestra ticket de compra
// Route::get('/abonos/ticket{id}',[AbonosController::class, 'ticket'])->name('abonos.ticket');
// {id}: el id del abono recien creado en BD

/* URL firmada (control de acceso a ticket de una persona que no lo compró, sin Auth::id()):
Route::get('/abonos/ticket{id}',[AbonosController::class, 'ticket'])->name('abonos.ticket')->middleware('signed');
*/


// ---- Muestra listado de abonos para los admin logueados
// Route::get('/abonos/listado',[AbonosController::class, 'listado'])->name('abonos.listado');

// ---- Muestra página de aviso (contenido protegido)
// Route::get('/abonos/prohibido',[AbonosController::class, 'prohibido'])->name('abonos.prohibido');


// ---- Muestra formulario de login
// Route::get('/usuarios/login',[UsuariosController::class, 'login'])->name('usuarios.login');
// ---- Procesa login
// Route::post('/usuarios/authenticate',[UsuariosController::class, 'authenticate'])->name('usuarios.authenticate');


// ---- Procesa logout
// Route::get('/usuarios/logout',[UsuariosController::class, 'logout'])->name('usuarios.logout');


// Route::get('/usuarios/forgot',[UsuariosController::class, 'forgot'])->name('usuarios.forgot');


// ---- Extra. Generar y descargar ticket forzado (sin almacenar en disco)
// Route::get('/abonos/downloadTicket/{id}',[AbonosController::class, 'downloadTicket'])->name('abonos.downloadTicket');
