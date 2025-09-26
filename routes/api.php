<?php

use App\Http\Controllers\Api\Logistica\SolicitudesController;
use Illuminate\Support\Facades\Route;

Route::post('solicitudes-agrupadas', [SolicitudesController::class, 'SolicitudAgrupada']);
Route::get('solicitudes-proyectos', [SolicitudesController::class, 'SolicitudProyectos']);
Route::post('solicitudes-proyectos-pdf', [SolicitudesController::class, 'generarPDF']);
