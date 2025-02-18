<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\CalificacionController;



Route::get('/alumno/{id}', [AlumnoController::class, 'perfil'])->name('alumno.perfil');

Route::put('/calificaciones/{id}', [CalificacionController::class, 'actualizarCalificacion']);
Route::post('/alumno/{id}/asignar-materia', [CalificacionController::class, 'asignarMateria']);
Route::post('/alumnos/update/{id}', [AlumnoController::class, 'update'])->name('alumnos.update');
Route::post('/actualizar-calificacion/{id}', [CalificacionController::class, 'actualizar'])->name('actualizar.calificacion');
Route::post('/eliminar-calificacion/{id}', [CalificacionController::class, 'eliminar']);
Route::post('/alumnos/store', [AlumnoController::class, 'store'])->name('alumnos.store');

Route::get('/', function () {
    return view('index');
});
Route::get('/alumno/{id}', [AlumnoController::class, 'perfil'])->name('alumno.perfil');

Route::get('/', function () {
    // Obtener alumnos con sus carreras
    $alumnos = DB::table('persona')
        ->join('carrera', 'persona.carrera_id', '=', 'carrera.id')
        ->select('persona.*', 'carrera.nombre as carrera')
        ->get();

    // Obtener todas las carreras disponibles
    $carreras = DB::table('carrera')->get();

    return view('index', compact('alumnos', 'carreras'));
});


