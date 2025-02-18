<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Para manejar la BD directamente
use App\Models\Calificacion; // Si tienes un modelo, ajústalo

class CalificacionController extends Controller
{
    public function actualizar(Request $request, $id)
    {
        try {
            // Validar que las calificaciones sean números entre 0 y 100
            $request->validate([
                'parcial1' => 'required|integer|min:0|max:100',
                'parcial2' => 'required|integer|min:0|max:100',
                'parcial3' => 'required|integer|min:0|max:100',
            ]);

            // Actualizar directamente en la BD
            DB::table('calificaciones_materia')
                ->where('id', $id)
                ->update([
                    'parcial1' => $request->parcial1,
                    'parcial2' => $request->parcial2,
                    'parcial3' => $request->parcial3
                ]);

            return response()->json(['success' => true, 'message' => 'Calificación actualizada correctamente.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al actualizar la calificación.'], 500);
        }
    }
}
