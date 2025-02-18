<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    public function perfil($id)
    {
        // Obtener datos del alumno
        $alumno = DB::table('persona')
            ->join('carrera', 'persona.carrera_id', '=', 'carrera.id')
            ->select('persona.*', 'carrera.nombre as carrera')
            ->where('persona.id', $id)
            ->first();
    
        if (!$alumno) {
            return abort(404, 'Alumno no encontrado.');
        }
    
        // Obtener calificaciones del alumno
        $calificaciones = DB::table('calificaciones_materia')
            ->join('materias', 'calificaciones_materia.materias_id', '=', 'materias.id')
            ->select('materias.nombre as materia', 'calificaciones_materia.*')
            ->where('calificaciones_materia.persona_id', $id)
            ->get();
    
        return view('perfil_alumno', compact('alumno', 'calificaciones'));
    }
}
