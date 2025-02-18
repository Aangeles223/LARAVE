<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CalificacionController;



Route::get('/alumno/{id}', [AlumnoController::class, 'perfil'])->name('alumno.perfil');


Route::post('/editar-alumno/{id}', [AlumnoController::class, 'editar'])->name('editar.alumno');
Route::post('/actualizar-calificacion/{id}', [CalificacionController::class, 'actualizar'])->name('actualizar.calificacion');
Route::post('/eliminar-calificacion/{id}', [CalificacionController::class, 'eliminar']);
Route::post('/alumnos/store', [AlumnoController::class, 'store'])->name('alumnos.store');

Route::get('/', function () {
    return view('index');
});
Route::get('/alumno/{id}', [AlumnoController::class, 'perfil'])->name('alumno.perfil');

Route::get('/', function () {
    $alumnos = DB::select("
        SELECT persona.*, carrera.nombre AS carrera 
        FROM persona
        INNER JOIN carrera ON persona.carrera_id = carrera.id
    ");

    return view('index', compact('alumnos'));
});

