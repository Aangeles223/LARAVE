<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100">
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <div class="text-xl font-bold text-teal-700">Sistema de Evaluación</div>
        <a href="#" class="text-gray-600 hover:text-gray-900">Home</a>
    </nav>
    
    <div class="max-w-6xl mx-auto p-8 bg-white shadow-lg mt-10 rounded-lg">
        <h1 class="text-5xl font-bold text-center text-teal-700 mb-6">Alumnos</h1>
        
        <div class="flex justify-between items-center mb-6">
            <input type="text" placeholder="Buscar por Matrícula" class="border p-3 rounded-lg w-2/3 shadow-sm" id="searchInput">
            <button onclick="openModal()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg shadow-md">
                ✎ Registrar Alumno
            </button>
        </div>
        
        <div class="bg-blue-50 p-4 rounded-lg border border-gray-200 flex flex-wrap gap-3">
            <strong class="block w-full">Leyenda:</strong>
            <span class="bg-green-500 px-3 py-1 rounded-lg text-white">Probabilidad de pasar arriba del 70%</span>
            <span class="bg-blue-500 px-3 py-1 rounded-lg text-white">Probabilidad de pasar entre el 70% y el 30%</span>
            <span class="bg-red-500 px-3 py-1 rounded-lg text-white">Probabilidad de pasar abajo del 30%</span>
            <span class="bg-yellow-500 px-3 py-1 rounded-lg text-white">Menos de 7 materias inscritas</span>
        </div>

        <div class="overflow-x-auto mt-6">
            <table class="w-full text-center border-collapse shadow-lg rounded-lg overflow-hidden">
                <thead class="bg-gray-200 text-gray-700 uppercase text-sm">
                    <tr>
                        <th class="p-4 border">Matrícula</th>
                        <th class="p-4 border">Nombre(s)</th>
                        <th class="p-4 border">Apellido Paterno</th>
                        <th class="p-4 border">Apellido Materno</th>
                        <th class="p-4 border">Fecha de Nacimiento</th>
                        <th class="p-4 border">Carrera</th>
                        <th class="p-4 border">Cuatrimestre</th>
                        <th class="p-4 border">Acciones</th>
                    </tr>
                </thead>
                <tbody id="alumnosTable" class="bg-white">
                    @foreach ($alumnos as $alumno)
                    <tr class="border fila-alumno @if($alumno->cuatrimestre < 7) bg-yellow-100 @endif hover:bg-gray-100">
                        <td class="p-4 border">{{ $alumno->matricula }}</td>
                        <td class="p-4 border">{{ $alumno->nombre }}</td>
                        <td class="p-4 border">{{ $alumno->apellidoP }}</td>
                        <td class="p-4 border">{{ $alumno->apellidoM }}</td>
                        <td class="p-4 border">{{ $alumno->fecha_nacimiento }}</td>
                        <td class="p-4 border">{{ $alumno->carrera }}</td>
                        <td class="p-4 border">{{ $alumno->cuatrimestre }}</td>
                        <td class="p-4 border">
                            <a href="{{ route('alumno.perfil', ['id' => $alumno->id]) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg shadow-md inline-block">👤 Perfil</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
            <h2 class="text-xl font-bold mb-4">Registrar Alumno</h2>
            <form id="registroForm">
                @csrf
                <input type="text" name="matricula" placeholder="Matrícula" class="border p-2 rounded w-full mb-2" required>
                <input type="text" name="nombre" placeholder="Nombre(s)" class="border p-2 rounded w-full mb-2" required>
                <input type="text" name="apellidoP" placeholder="Apellido Paterno" class="border p-2 rounded w-full mb-2" required>
                <input type="text" name="apellidoM" placeholder="Apellido Materno" class="border p-2 rounded w-full mb-2" required>
                <input type="date" name="fecha_nacimiento" class="border p-2 rounded w-full mb-2" required>
                <select name="carrera_id" class="border p-2 rounded w-full mb-2" required>
                    <option value="">Seleccione una carrera</option>
                    @foreach ($carreras as $carrera)
                        <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
                    @endforeach
                </select>
                <input type="number" name="cuatrimestre" placeholder="Cuatrimestre" class="border p-2 rounded w-full mb-2" required>
                <div class="flex justify-end">
                    <button type="button" onclick="closeModal()" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">Cancelar</button>
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        document.getElementById('searchInput').addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('.fila-alumno');

            rows.forEach(row => {
                let matricula = row.cells[0].textContent.toLowerCase();
                row.style.display = matricula.includes(filter) ? '' : 'none';
            });
        });

        document.getElementById('registroForm').addEventListener('submit', function (event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('alumnos.store') }}", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name=\"_token\"]').value
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Alumno registrado correctamente");

                    let newRow = document.createElement('tr');
                    newRow.classList.add('border', 'fila-alumno', 'hover:bg-gray-100');

                    newRow.innerHTML = `
                        <td class="p-4 border">${formData.get('matricula')}</td>
                        <td class="p-4 border">${formData.get('nombre')}</td>
                        <td class="p-4 border">${formData.get('apellidoP')}</td>
                        <td class="p-4 border">${formData.get('apellidoM')}</td>
                        <td class="p-4 border">${formData.get('fecha_nacimiento')}</td>
                        <td class="p-4 border">${document.querySelector('select[name="carrera_id"] option:checked').textContent}</td>
                        <td class="p-4 border">${formData.get('cuatrimestre')}</td>
                    `;

                    document.getElementById('alumnosTable').appendChild(newRow);
                    closeModal();
                    document.getElementById('registroForm').reset();
                } else {
                    alert("Error al registrar: " + data.error);
                }
            })
            .catch(error => console.error("Error en la petición:", error));
        });
    </script>
</body>
</html>