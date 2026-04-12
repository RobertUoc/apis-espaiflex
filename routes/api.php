<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EdificiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ComplementController;
use App\Http\Controllers\ProvinciaController;
use App\Http\Controllers\ComentariController;
use App\Http\Controllers\SalaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UserController;

// Rutas protegidas 
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // Edificis
    Route::post('/edificis', [EdificiController::class, 'store']);
    Route::put('/edificis/{id}', [EdificiController::class, 'update']);      
    // Complements
    Route::get('/complements', [ComplementController::class, 'index']);
    Route::post('/complements', [ComplementController::class, 'store']);
    Route::put('/complements/{id}', [ComplementController::class, 'update']);
    // Provincies
    Route::apiResource('provincies', ProvinciaController::class)->only(['index', 'show']);
    // Sales
    Route::get('/sales', [SalaController::class, 'index']);
    Route::post('/sales', [SalaController::class, 'store']);
    Route::put('/sales/{id}', [SalaController::class, 'update']);    
    // Comentaris
    Route::get('/comentaris', [ComentariController::class, 'index']);        
    // Factures
    Route::get('/factures', [FacturaController::class, 'index']);    
    Route::get('factures/anios', [FacturaController::class, 'anios']);
    Route::get('factures/meses/{anio}', [FacturaController::class, 'porMes']);
    Route::get('factures/dias/{anio}/{mes}', [FacturaController::class, 'porDia']);    
    Route::get('factures/{id}', [FacturaController::class, 'show']);
    Route::post('factures/email/{id}', [FacturaController::class, 'enviarEmail']);
    Route::post('factures/{id}/email', [FacturaController::class, 'enviarEmail']);    
});

// Sense Token
// Login Part administrativa
Route::post('/login', [AuthController::class, 'login']);
// Llegir Edificis 
Route::get('/edificis', [EdificiController::class, 'index']);
// Crear Usuaris Plataforma
Route::post('/users', [UserController::class, 'store']); 
// Calendari
// llegir tot el calendari
Route::get('/reserves/any/{any}/edifici/{edifici}', [ReservaController::class, 'lecturaReserves']);
// Veure estats d'un dia
Route::get('/reserves/dia/{dia}/edifici/{edifici}', [ReservaController::class, 'lecturaDia']);

// Veure reserva per id
Route::get('/reserves/reserva/{id}', [ReservaController::class, 'lecturaReserva']);

// Mirar Reserva
Route::get('/reserves/dia/{dia}/sala/{sala}/reserva/{reserva}', [ReservaController::class, 'miraReserva']);

// Comentaris
Route::post('/comentaris', [ComentariController::class, 'store']);
// Llegir a l'entrar al calendari i veure sales tothom
Route::get('/getsales/edifici/{edifici}', [SalaController::class, 'salasEdifici']);    
Route::get('/getsales/versala/{id_sala}', [SalaController::class, 'verSala']); 

Route::middleware('auth:sanctum')->group(function () {
    // Update User
    Route::put('/users/{id}', [UserController::class, 'update']);
    // Llegir Sales
    
       
    Route::get('/getsales/vercomplements/{id_sala}', [SalaController::class, 'verComplements']);    
    // Esborro reserva
    Route::get('/reserves/delete_event/{id_event}', [ReservaController::class, 'deleteEvent']);           
    // Crear sheuleder para pedir reserva
    Route::get('/reserves/dia/{dia}/sala/{sala}', [ReservaController::class, 'lecturaEvent']);
    // Crear sheuleder para pedir reserva
    Route::get('/reserves/sala/{sala}/reserva/{id_reserva}', [ReservaController::class, 'lecturaHoras']);
    // Insert Dia
    Route::post('/reserves', [ReservaController::class, 'store']);
    // Buscar conflicte
    Route::post('/reserves/disponibilidad', [ReservaController::class, 'comprobarDisponibilidad']);

});






