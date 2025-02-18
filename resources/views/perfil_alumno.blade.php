<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Alumno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-300">
    <div class="max-w-6xl mx-auto p-6 bg-white shadow-lg mt-10 rounded-lg">
        <h1 class="text-5xl font-bold text-center text-teal-700">Perfil Alumno</h1>

        <div class="flex mt-4">
            <div class="w-1/3 p-4 bg-gray-100 rounded-lg">
                <img src="https://cdn-icons-png.flaticon.com/512/147/147144.png" class="w-32 mx-auto">
                <p><strong>Matrícula:</strong> <span id="matricula">{{ $alumno->matricula }}</span></p>
                <p><strong>Nombre:</strong> <span id="nombre">{{ $alumno->nombre }} {{ $alumno->apellidoP }} {{ $alumno->apellidoM }}</span></p>
                <p><strong>Fecha de Nacimiento:</strong> <span id="fecha_nacimiento">{{ $alumno->fecha_nacimiento }}</span></p>
                <p><strong>Carrera:</strong> <span id="carrera">{{ $alumno->carrera }}</span></p>
                <p><strong>Cuatrimestre:</strong> <span id="cuatrimestre">{{ $alumno->cuatrimestre }}</span></p>
                <button id="editarAlumno" class="mt-4 bg-green-500 text-white px-6 py-2 rounded">Editar Alumno</button>
            </div>

            <div class="w-2/3 bg-white p-4 rounded-lg">
                <h2 class="text-xl font-bold text-center">Calificaciones</h2>
                
                <table class="w-full mt-4 border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border p-2">Asignatura</th>
                            <th class="border p-2">Primer Parcial</th>
                            <th class="border p-2">Segundo Parcial</th>
                            <th class="border p-2">Tercer Parcial</th>
                            <th class="border p-2">Probabilidad de Pasar</th>
                            <th class="border p-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tabla-calificaciones">
                        @foreach ($calificaciones as $cal)
                        <tr class="border text-center fila-calificacion" data-id="{{ $cal->id }}">
                            <td class="border p-2">{{ $cal->materia }}</td>
                            <td class="border p-2"><input type="number" value="0" class="w-16 border parcial-input" min="0" max="100"></td>
                            <td class="border p-2"><input type="number" value="0" class="w-16 border parcial-input" min="0" max="100"></td>
                            <td class="border p-2"><input type="number" value="0" class="w-16 border parcial-input" min="0" max="100"></td>
                            <td class="border p-2 probabilidad">0.00%</td>
                            <td class="border p-2 flex space-x-4">
                                <button class="bg-blue-500 text-white px-8 py-2 rounded actualizar">Actualizar</button>
                                <button class="bg-red-500 text-white px-8 py-2 rounded eliminar">Eliminar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100">
                            <td colspan="4" class="border p-2 text-right"><strong>Probabilidad de pasar el cuatrimestre:</strong></td>
                            <td class="border p-2 text-center" id="probabilidad-cuatrimestre">0.00%</td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).on("click", ".actualizar", function() {
            let row = $(this).closest(".fila-calificacion");
            let id = row.data("id");
            let parcial1 = parseFloat(row.find(".parcial-input").eq(0).val()) || 0;
            let parcial2 = parseFloat(row.find(".parcial-input").eq(1).val()) || 0;
            let parcial3 = parseFloat(row.find(".parcial-input").eq(2).val()) || 0;

            let promedio = (parcial1 + parcial2 + parcial3) / 3;
            let probabilidad = (promedio >= 70) ? ((promedio / 100) * 100).toFixed(2) : "0.00";

            row.find(".probabilidad").text(probabilidad + "%");
            calcularProbabilidadCuatrimestre();
        });

        function calcularProbabilidadCuatrimestre() {
            let filas = $(".fila-calificacion");
            let totalMaterias = filas.length;
            let materiasAprobadas = 0;

            filas.each(function() {
                let probabilidadTexto = $(this).find(".probabilidad").text().replace("%", "");
                let probabilidad = parseFloat(probabilidadTexto) || 0;
                if (probabilidad > 0) {
                    materiasAprobadas++;
                }
            });

            let probabilidadCuatrimestre = (materiasAprobadas / totalMaterias) * 100;
            $("#probabilidad-cuatrimestre").text(probabilidadCuatrimestre.toFixed(2) + "%");
        }
    </script>
</body>
</html>
