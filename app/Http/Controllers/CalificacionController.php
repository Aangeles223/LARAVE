<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CalificacionController extends Controller
{
    /**
     * Asigna una materia a un alumno y la registra en la tabla `calificaciones_materia`
     */
    public function asignarMateria(Request $request, $id)
    {
        try {
            $request->validate([
                'materias_id' => 'required|integer|exists:materias,id',
            ]);

            // Verificar si la materia ya está asignada al alumno
            $existe = DB::table('calificaciones_materia')
                ->where('persona_id', $id)
                ->where('materias_id', $request->materias_id)
                ->exists();

            if ($existe) {
                return response()->json([
                    'success' => false,
                    'error' => 'El alumno ya está inscrito en esta materia.'
                ], 400);
            }

            // Registrar la materia en calificaciones_materia y obtener su ID
            $calificacionId = DB::table('calificaciones_materia')->insertGetId([
                'materias_id' => $request->materias_id,
                'persona_id' => $id,
                'parcial1' => null,
                'parcial2' => null,
                'parcial3' => null
            ]);

            // Obtener el nombre de la materia
            $materia = DB::table('materias')->where('id', $request->materias_id)->value('nombre');

            return response()->json([
                'success' => true,
                'message' => 'Materia asignada correctamente.',
                'materia' => $materia,
                'id' => $calificacionId
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Error en la asignación: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Actualiza las calificaciones de una materia asignada a un alumno.
     */
    public function actualizarCalificacion(Request $request, $id)
    {
        try {
            $request->validate([
                'parcial1' => 'nullable|integer|min:0|max:100',
                'parcial2' => 'nullable|integer|min:0|max:100',
                'parcial3' => 'nullable|integer|min:0|max:100',
            ]);

            $actualizado = DB::table('calificaciones_materia')
                ->where('id', $id)
                ->update([
                    'parcial1' => $request->parcial1,
                    'parcial2' => $request->parcial2,
                    'parcial3' => $request->parcial3
                ]);

            if (!$actualizado) {
                return response()->json(['success' => false, 'error' => 'No se encontró la calificación para actualizar.'], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Calificación actualizada correctamente.'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Error al actualizar la calificación: ' . $e->getMessage()], 500);
        }
    }
}
