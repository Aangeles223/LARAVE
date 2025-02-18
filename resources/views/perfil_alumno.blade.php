<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil Alumno</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .contenedor {
            max-width: 1500px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .tabla-container {
            overflow-x: auto;
            margin-top: 15px;
        }
        .tabla {
            min-width: 600px;
        }
    </style>
</head>
<body class="bg-gray-300">

<!-- NAVBAR -->
<nav class="bg-white shadow p-4 flex justify-between items-center">
    <div class="text-xl font-bold text-teal-700">Sistema de Evaluación</div>
    <a href="{{ url('/') }}" class="text-gray-600 hover:text-gray-900">Home</a>
</nav>

<div class="contenedor mt-20">
    <h1 class="text-4xl font-bold text-center text-teal-700 mb-4">Perfil del Alumno</h1>

    <div class="flex flex-col md:flex-row">
        <div class="w-full md:w-1/5 p-4 bg-gray-100 rounded-lg">
            <img src="https://cdn-icons-png.flaticon.com/512/147/147144.png" class="w-32 mx-auto mb-4">
            <p><strong>Matrícula:</strong> <span id="matricula">{{ $alumno->matricula }}</span></p>
            <p><strong>Nombre:</strong> <span id="nombre">{{ $alumno->nombre }} {{ $alumno->apellidoP }} {{ $alumno->apellidoM }}</span></p>
            <p><strong>Fecha de Nacimiento:</strong> <span id="fecha_nacimiento">{{ $alumno->fecha_nacimiento }}</span></p>
            <p><strong>Carrera:</strong> <span id="carrera">{{ $alumno->carrera }}</span></p>
            <p><strong>Cuatrimestre:</strong> <span id="cuatrimestre">{{ $alumno->cuatrimestre }}</span></p>
        </div>

        <div class="w-full md:w-4/5 bg-white p-4 rounded-lg">
            <h2 class="text-xl font-bold text-center">Calificaciones</h2>
 <!-- FORMULARIO PARA ASIGNAR MATERIAS -->
 <div class="mt-4 mb-4">
        <h3 class="text-lg font-semibold mb-2">Asignar Materia</h3>
        <select id="materiaSelect" class="border p-2 rounded w-1/2">
            <option value="">Seleccione una materia</option>
            @foreach ($materias as $materia)
                <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
            @endforeach
        </select>
        <button onclick="asignarMateria({{ $alumno->id }})" 
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
            Asignar
        </button>
    </div>
            <div class="tabla-container">
                <table class="tabla w-full border-collapse border border-gray-300">
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
                            <td class="border p-2"><input type="number" value="{{ $cal->parcial1 }}" class="w-16 border parcial-input text-center" min="0" max="100"></td>
                            <td class="border p-2"><input type="number" value="{{ $cal->parcial2 }}" class="w-16 border parcial-input text-center" min="0" max="100"></td>
                            <td class="border p-2"><input type="number" value="{{ $cal->parcial3 }}" class="w-16 border parcial-input text-center" min="0" max="100"></td>
                            <td class="border p-2 probabilidad"></td>
                            <td class="border p-2">
                                <button class="bg-blue-500 text-white px-4 py-1 rounded actualizar">Actualizar</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-100">
                            <td colspan="4" class="border p-2 text-right"><strong>Probabilidad de pasar el cuatrimestre:</strong></td>
                            <td class="border p-2 text-center" id="probabilidad-cuatrimestre"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    function calcularProbabilidad(row) {
        let inputs = row.querySelectorAll(".parcial-input");
        let parcial1 = parseFloat(inputs[0].value) || 0;
        let parcial2 = parseFloat(inputs[1].value) || 0;
        let parcial3 = parseFloat(inputs[2].value) || 0;

        let finales = 0;
        [parcial1, parcial2, parcial3].forEach(nota => {
            if (nota < 70) finales++;
        });

        if (finales === 3) {
            row.querySelector(".probabilidad").innerText = "Reprobado (0%)";
        } else if (finales > 0) {
            let probabilidad = ((3 - finales) / 3) * 100;
            row.querySelector(".probabilidad").innerText = `Debe presentar ${finales} final(es) (${probabilidad.toFixed(2)}%)`;
        } else {
            row.querySelector(".probabilidad").innerText = "100%";
        }

        calcularProbabilidadCuatrimestre();
    }

    function calcularProbabilidadCuatrimestre() {
        let filas = document.querySelectorAll(".fila-calificacion");
        let totalMaterias = filas.length;
        let materiasAprobadas = 0;

        filas.forEach(row => {
            let probabilidadTexto = row.querySelector(".probabilidad").innerText;

            if (!probabilidadTexto.includes("Reprobado") && !probabilidadTexto.includes("Debe presentar") && probabilidadTexto !== "") {
                materiasAprobadas++;
            }
        });

        let requeridasParaAprobar = Math.floor(totalMaterias / 2) + 1;
        let probabilidadCuatrimestre = (materiasAprobadas >= requeridasParaAprobar)
            ? "100%"
            : ((materiasAprobadas / totalMaterias) * 100).toFixed(2) + "%";

        document.getElementById("probabilidad-cuatrimestre").innerText = probabilidadCuatrimestre;
    }

    function actualizarCalificacion(row) {
        let id = row.dataset.id;
        let inputs = row.querySelectorAll(".parcial-input");

        let data = {
            _method: "PUT",
            _token: "{{ csrf_token() }}",
            parcial1: inputs[0].value,
            parcial2: inputs[1].value,
            parcial3: inputs[2].value
        };

        $.ajax({
            url: `/calificaciones/${id}`,
            type: "POST",
            data: data,
            success: function(response) {
                alert("Calificación actualizada correctamente.");
            },
            error: function() {
                alert("Error al actualizar la calificación.");
            }
        });
    }

    $(document).on("click", ".actualizar", function() {
        let row = $(this).closest(".fila-calificacion")[0];
        actualizarCalificacion(row);
        calcularProbabilidad(row);
    });

    $(".parcial-input").on("input", function() {
        let row = $(this).closest(".fila-calificacion")[0];
        calcularProbabilidad(row);
    });

    $(".fila-calificacion").each(function() {
        calcularProbabilidad(this);
    });
</script>
<script>
    function asignarMateria(alumnoId) {
        let materiaId = document.getElementById("materiaSelect").value;

        if (materiaId === "") {
            alert("Por favor, seleccione una materia.");
            return;
        }

        $.ajax({
            url: `/alumno/${alumnoId}/asignar-materia`,
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                materias_id: materiaId
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);

                    let newRow = document.createElement('tr');
                    newRow.classList.add('border', 'text-center', 'fila-calificacion');
                    newRow.dataset.id = response.id;
                    newRow.innerHTML = `
                        <td class="border p-2">${response.materia}</td>
                        <td class="border p-2"><input type="number" value="0" class="w-16 border parcial-input text-center" min="0" max="100"></td>
                        <td class="border p-2"><input type="number" value="0" class="w-16 border parcial-input text-center" min="0" max="100"></td>
                        <td class="border p-2"><input type="number" value="0" class="w-16 border parcial-input text-center" min="0" max="100"></td>
                        <td class="border p-2">
                            <button class="bg-blue-500 text-white px-4 py-1 rounded actualizar">Actualizar</button>
                        </td>
                    `;

                    document.getElementById('tabla-calificaciones').appendChild(newRow);
                } else {
                    alert(response.error);
                }
            },
            error: function(xhr) {
                alert("Error al asignar la materia: " + xhr.responseJSON.error);
            }
        });
    }
</script>

</body>
</html>
