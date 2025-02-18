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

    <!-- Modal para editar alumno -->
    <div id="modal-editar" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <h2 class="text-2xl font-bold mb-4">Editar Alumno</h2>
            <input type="hidden" id="alumno-id" value="{{ $alumno->id }}">
            <label class="block">Nombre:</label>
            <input type="text" id="edit-nombre" class="w-full border p-2 rounded mb-2" value="{{ $alumno->nombre }}">
            <label class="block">Apellido Paterno:</label>
            <input type="text" id="edit-apellidoP" class="w-full border p-2 rounded mb-2" value="{{ $alumno->apellidoP }}">
            <label class="block">Apellido Materno:</label>
            <input type="text" id="edit-apellidoM" class="w-full border p-2 rounded mb-2" value="{{ $alumno->apellidoM }}">
            <label class="block">Fecha de Nacimiento:</label>
            <input type="date" id="edit-fecha_nacimiento" class="w-full border p-2 rounded mb-2" value="{{ $alumno->fecha_nacimiento }}">
            <label class="block">Cuatrimestre:</label>
            <input type="number" id="edit-cuatrimestre" class="w-full border p-2 rounded mb-2" value="{{ $alumno->cuatrimestre }}">

            <div class="flex justify-end space-x-2 mt-4">
                <button class="bg-gray-500 text-white px-4 py-2 rounded cerrar-modal">Cancelar</button>
                <button class="bg-blue-500 text-white px-4 py-2 rounded guardar-edicion">Guardar</button>
            </div>
        </div>
    </div>

    <script>
        $(document).on("click", "#editarAlumno", function() {
            $("#modal-editar").removeClass("hidden");
        });

        $(document).on("click", ".cerrar-modal", function() {
            $("#modal-editar").addClass("hidden");
        });

        $(document).on("click", ".guardar-edicion", function() {
            let id = $("#alumno-id").val();
            let nombre = $("#edit-nombre").val();
            let apellidoP = $("#edit-apellidoP").val();
            let apellidoM = $("#edit-apellidoM").val();
            let fecha_nacimiento = $("#edit-fecha_nacimiento").val();
            let cuatrimestre = $("#edit-cuatrimestre").val();

            $.ajax({
                url: `/editar-alumno/${id}`,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    nombre, apellidoP, apellidoM, fecha_nacimiento, cuatrimestre
                },
                success: function(response) {
                    alert(response.mensaje);
                    location.reload();
                },
                error: function(xhr) {
                    alert("Error al actualizar los datos del alumno.");
                }
            });
        });
    </script>
</body>
</html>