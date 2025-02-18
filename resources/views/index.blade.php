<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Alumnos</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-100">

  <!-- NAVBAR -->
  <nav class="bg-white shadow p-4 flex justify-between items-center">
    <div class="text-xl font-bold text-teal-700">Sistema de Evaluación</div>
    <a href="#" class="text-gray-600 hover:text-gray-900">Home</a>
  </nav>
  
  <!-- CONTENEDOR PRINCIPAL -->
  <div class="max-w-6xl mx-auto p-8 bg-white shadow-lg mt-10 rounded-lg">
    <h1 class="text-5xl font-bold text-center text-teal-700 mb-6">Alumnos</h1>
    
    <!-- BUSCADOR Y BOTÓN DE REGISTRO -->
    <div class="flex justify-between items-center mb-6">
      <input type="text" placeholder="Buscar por Matrícula" 
        class="border p-3 rounded-lg w-2/3 shadow-sm" 
        id="searchInput">
      <button onclick="openRegisterModal()" 
        class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg shadow-md">
        ✎ Registrar Alumno
      </button>
    </div>
    
    <!-- TABLA DE ALUMNOS -->
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
            <td class="p-4 border flex gap-2">
              <!-- BOTÓN EDITAR -->
              <button 
                onclick="openEditModal(
                  {{ $alumno->id }}, 
                  '{{ $alumno->matricula }}', 
                  '{{ $alumno->nombre }}', 
                  '{{ $alumno->apellidoP }}', 
                  '{{ $alumno->apellidoM }}', 
                  '{{ $alumno->fecha_nacimiento }}', 
                  {{ $alumno->carrera_id }}, 
                  {{ $alumno->cuatrimestre }}
                )" 
                class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow-md">
                ✏️ Editar
              </button>
              <!-- PERFIL -->
              <a href="{{ route('alumno.perfil', ['id' => $alumno->id]) }}" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md">
                👤 Perfil
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <!-- MODAL REGISTRO -->
  <div id="registerModal" 
       class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
      <h2 class="text-xl font-bold mb-4">Registrar Alumno</h2>
      <form id="registroForm">
        @csrf
        <div class="flex flex-col gap-2">
          <input type="text" name="matricula" placeholder="Matrícula" class="border p-2 rounded" required>
          <input type="text" name="nombre" placeholder="Nombre(s)" class="border p-2 rounded" required>
          <input type="text" name="apellidoP" placeholder="Apellido Paterno" class="border p-2 rounded" required>
          <input type="text" name="apellidoM" placeholder="Apellido Materno" class="border p-2 rounded" required>
          <input type="date" name="fecha_nacimiento" class="border p-2 rounded" required>
          <select name="carrera_id" class="border p-2 rounded" required>
            <option value="">Seleccione una carrera</option>
            @foreach ($carreras as $carrera)
              <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
            @endforeach
          </select>
          <input type="number" name="cuatrimestre" placeholder="Cuatrimestre" class="border p-2 rounded" required>
        </div>
        <div class="flex justify-end mt-4">
          <button type="button" onclick="closeRegisterModal()" 
            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">
            Cancelar
          </button>
          <button type="submit" 
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
            Guardar
          </button>
        </div>
      </form>
    </div>
  </div>

  <!-- MODAL EDICIÓN -->
  <div id="editModal" 
       class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg shadow-lg w-1/3">
      <h2 class="text-xl font-bold mb-4">Editar Alumno</h2>
      <form id="editForm">
        @csrf
        <input type="hidden" id="editId">
        <div class="flex flex-col gap-2">
          <input type="text" id="editMatricula" class="border p-2 rounded" required>
          <input type="text" id="editNombre" class="border p-2 rounded" required>
          <input type="text" id="editApellidoP" class="border p-2 rounded" required>
          <input type="text" id="editApellidoM" class="border p-2 rounded" required>
          <input type="date" id="editFechaNacimiento" class="border p-2 rounded" required>
          <select id="editCarreraId" class="border p-2 rounded" required>
            <option value="">Seleccione una carrera</option>
            @foreach ($carreras as $carrera)
              <option value="{{ $carrera->id }}">{{ $carrera->nombre }}</option>
            @endforeach
          </select>
          <input type="number" id="editCuatrimestre" class="border p-2 rounded" required>
        </div>
        <div class="flex justify-end mt-4">
          <button type="button" onclick="closeEditModal()" 
            class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg mr-2">
            Cancelar
          </button>
          <button type="submit" 
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // ✅ Abrir/cerrar modal de registro
    function openRegisterModal() {
      document.getElementById('registerModal').classList.remove('hidden');
    }
    function closeRegisterModal() {
      document.getElementById('registerModal').classList.add('hidden');
    }

    // ✅ Abrir/cerrar modal de edición y llenarlo con datos
    function openEditModal(id, matricula, nombre, apellidoP, apellidoM, fechaNac, carreraId, cuatrimestre) {
      document.getElementById('editId').value = id;
      document.getElementById('editMatricula').value = matricula;
      document.getElementById('editNombre').value = nombre;
      document.getElementById('editApellidoP').value = apellidoP;
      document.getElementById('editApellidoM').value = apellidoM;
      document.getElementById('editFechaNacimiento').value = fechaNac;
      document.getElementById('editCarreraId').value = carreraId;
      document.getElementById('editCuatrimestre').value = cuatrimestre;
      document.getElementById('editModal').classList.remove('hidden');
    }
    function closeEditModal() {
      document.getElementById('editModal').classList.add('hidden');
    }

    // 🔍 Filtrar alumnos por matrícula
    document.getElementById('searchInput').addEventListener('keyup', function() {
      let filter = this.value.toLowerCase();
      let rows = document.querySelectorAll('.fila-alumno');
      rows.forEach(row => {
        let matricula = row.cells[0].textContent.toLowerCase();
        row.style.display = matricula.includes(filter) ? '' : 'none';
      });
    });

    // 🟢 REGISTRO DE ALUMNOS (AJAX)
    document.getElementById('registroForm').addEventListener('submit', function(event) {
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
          alert('Alumno registrado correctamente');

          // Agregar fila a la tabla sin recargar
          let newRow = document.createElement('tr');
          newRow.classList.add('border', 'fila-alumno', 'hover:bg-gray-100');
          newRow.innerHTML = `
            <td class="p-4 border">${formData.get('matricula')}</td>
            <td class="p-4 border">${formData.get('nombre')}</td>
            <td class="p-4 border">${formData.get('apellidoP')}</td>
            <td class="p-4 border">${formData.get('apellidoM')}</td>
            <td class="p-4 border">${formData.get('fecha_nacimiento')}</td>
            <td class="p-4 border">${data.carrera}</td>
            <td class="p-4 border">${formData.get('cuatrimestre')}</td>
            <td class="p-4 border flex gap-2">
              <button onclick="openEditModal(
                ${data.id}, 
                '${formData.get('matricula')}',
                '${formData.get('nombre')}',
                '${formData.get('apellidoP')}',
                '${formData.get('apellidoM')}',
                '${formData.get('fecha_nacimiento')}',
                ${formData.get('carrera_id')},
                ${formData.get('cuatrimestre')}
              )"
              class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg shadow-md">✏️ Editar</button>
              <a href="#" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg shadow-md">👤 Perfil</a>
            </td>
          `;
          document.getElementById('alumnosTable').appendChild(newRow);

          closeRegisterModal();
          this.reset();
        } else {
          alert('Error al registrar: ' + data.error);
        }
      })
      .catch(error => console.error('Error en la petición:', error));
    });

    // 🔵 EDICIÓN DE ALUMNOS (AJAX)
    document.getElementById('editForm').addEventListener('submit', function(event) {
      event.preventDefault();
      let formData = new FormData();
      
      let id = document.getElementById('editId').value;
      formData.append('matricula', document.getElementById('editMatricula').value);
      formData.append('nombre', document.getElementById('editNombre').value);
      formData.append('apellidoP', document.getElementById('editApellidoP').value);
      formData.append('apellidoM', document.getElementById('editApellidoM').value);
      formData.append('fecha_nacimiento', document.getElementById('editFechaNacimiento').value);
      formData.append('carrera_id', document.getElementById('editCarreraId').value);
      formData.append('cuatrimestre', document.getElementById('editCuatrimestre').value);

      // Token CSRF
      formData.append('_token', document.querySelector('input[name=\"_token\"]').value);

      fetch(`/alumnos/update/${id}`, {
        method: "POST",
        body: formData,
        headers: {
          "X-CSRF-TOKEN": document.querySelector('input[name=\"_token\"]').value
        }
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Alumno actualizado correctamente');

          // Actualizar la fila correspondiente sin recargar
          let rows = document.querySelectorAll('.fila-alumno');
          rows.forEach(row => {
            let matricula = row.cells[0].textContent;
            if (matricula == formData.get('matricula')) {
              // Actualizar celdas
              row.cells[1].textContent = formData.get('nombre');
              row.cells[2].textContent = formData.get('apellidoP');
              row.cells[3].textContent = formData.get('apellidoM');
              row.cells[4].textContent = formData.get('fecha_nacimiento');
              row.cells[5].textContent = data.carrera; 
              row.cells[6].textContent = formData.get('cuatrimestre');
            }
          });

          closeEditModal();
        } else {
          alert('Error al actualizar: ' + data.error);
        }
      })
      .catch(error => console.error('Error en la petición:', error));
    });
  </script>
</body>
</html>
