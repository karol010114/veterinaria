<?php
if (isset($_POST['busqueda'])) {
    $busqueda = $_POST['busqueda'];
    
    // Conexión a la base de datos
    $conn = new mysqli('localhost', 'root', '', 'veterinaria');
    if ($conn->connect_error) {
        die('Error de conexión: ' . $conn->connect_error);
    }

    // Consulta para buscar por ID o nombre
    $sql = "SELECT * FROM clientes WHERE id = ? OR propietario LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $busqueda, $busqueda);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<p><strong>ID:</strong> {$row['id']}</p>";
            echo "<p><strong>Propietario:</strong> {$row['propietario']}</p>";
            echo "<p><strong>Dirección:</strong> {$row['direccion']}</p>";
            echo "<p><strong>Teléfono:</strong> {$row['telefono']}</p>";
            echo "<p><strong>DNI:</strong> {$row['dni']}</p>";
            echo "<p><strong>Paciente:</strong> {$row['paciente']}</p>";
            echo "<p><strong>Fecha de Nacimiento:</strong> {$row['fechaNacimiento']}</p>";
            echo "<p><strong>Especie:</strong> {$row['especie']}</p>";
            echo "<p><strong>Raza:</strong> {$row['raza']}</p>";
            echo "<p><strong>Sexo:</strong> {$row['sexo']}</p>";
            echo "<p><strong>Color:</strong> {$row['color']}</p>";
            echo "<p><strong>Fecha de Seguimiento Inicio:</strong> {$row['fechaSeguimientoInicio']}</p>";
            echo "<p><strong>Descripción:</strong> {$row['descripcion']}</p>";
        }
    } else {
        echo "<p style='color: red;'>No se encontraron resultados.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>


