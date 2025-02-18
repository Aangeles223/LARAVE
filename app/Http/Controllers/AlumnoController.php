<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumnoController extends Controller
{
    /**
     * Muestra la lista de alumnos y carreras.
     */
    public function index()
    {
        // Obtener alumnos con sus carreras
        $alumnos = DB::table('persona')
            ->join('carrera', 'persona.carrera_id', '=', 'carrera.id')
            ->select('persona.*', 'carrera.nombre as carrera')
            ->get();

        // Obtener todas las carreras
        $carreras = DB::table('carrera')->get();

        return view('alumnos', compact('alumnos', 'carreras'));
    }

    /**
     * Guarda un nuevo alumno en la base de datos.
     */
    public function store(Request $request)
    {
        try {
            // Validar los datos
            $validated = $request->validate([
                'matricula' => 'required|unique:persona,matricula',
                'nombre' => 'required|string|max:255',
                'apellidoP' => 'required|string|max:255',
                'apellidoM' => 'required|string|max:255',
                'fecha_nacimiento' => 'required|date',
                'carrera_id' => 'required|integer|exists:carrera,id',
                'cuatrimestre' => 'required|integer|min:1'
            ]);

            // Insertar en la base de datos
            DB::table('persona')->insert([
                'matricula' => $validated['matricula'],
                'nombre' => $validated['nombre'],
                'apellidoP' => $validated['apellidoP'],
                'apellidoM' => $validated['apellidoM'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'carrera_id' => $validated['carrera_id'],
                'cuatrimestre' => $validated['cuatrimestre']
            ]);

            return response()->json(['success' => true, 'message' => 'Alumno registrado correctamente']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Muestra el perfil de un alumno con sus calificaciones.
     */
    public function perfil($id)
    {
        // Obtener datos del alumno
        $alumno = DB::table('persona')
            ->join('carrera', 'persona.carrera_id', '=', 'carrera.id')
            ->select('persona.*', 'carrera.nombre as carrera')
            ->where('persona.id', $id)
            ->first();

        if (!$alumno) {
            return redirect()->route('alumnos.index')->with('error', 'Alumno no encontrado');
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