<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/ventanas.css">
    <link rel="stylesheet" href="../css/a.css">
    <title>Gestión de Pacientes</title>
</head>
<body>

<h1>Gestión de Pacientes</h1>

<!-- Botones -->
<button onclick="window.location.href='index.php'">Registrar Paciente</button>
<button onclick="mostrarBusqueda()">Buscar Paciente por nombre o ID</button>
<button onclick="window.location.href='ver-clientes.php'">Mostrar Datos de Paciente</button>

<!-- Campo de búsqueda -->
<div class="search" id="searchSection" style="display: none;">
    <input type="text" id="busqueda" placeholder="Buscar paciente por ID o nombre">
    <button type="button" onclick="buscarPaciente()">Buscar</button>
</div>

<!-- Modal y su overlay -->
<div id="modal-overlay"></div>
<div id="modal">
    <span class="close-btn" onclick="cerrarModal()">×</span>
    <div id="modal-content"></div> <!-- El contenido se actualizará dinámicamente aquí -->
</div>

<script>
    // Mostrar el campo de búsqueda
    function mostrarBusqueda() {
        document.getElementById('searchSection').style.display = 'block';
    }

    // Buscar paciente y mostrar modal
    function buscarPaciente() {
        const busqueda = document.getElementById('busqueda').value;
        const modal = document.getElementById('modal');
        const modalContent = document.getElementById('modal-content');
        const overlay = document.getElementById('modal-overlay');

        if (busqueda.trim() === '') {
            alert('Por favor, ingresa un ID o nombre para buscar.');
            return;
        }

        // Enviar datos al servidor con AJAX
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'buscar-paciente.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                modalContent.innerHTML = xhr.responseText; // Mostrar los datos del cliente
                modal.style.display = 'block'; // Mostrar modal
                overlay.style.display = 'block'; // Mostrar overlay
            } else {
                modalContent.innerHTML = '<p style="color: red;">Error al buscar el paciente.</p>';
            }
        };
        xhr.send('busqueda=' + encodeURIComponent(busqueda));
    }

    // Cerrar el modal
    function cerrarModal() {
        document.getElementById('modal').style.display = 'none';
        document.getElementById('modal-overlay').style.display = 'none';
    }
</script>

</body>
</html>
