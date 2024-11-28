<?php
// Conectar a la base de datos
$conn = new mysqli("localhost", "root", "", "veterinaria");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consultar todos los clientes
$sql = "SELECT * FROM clientes";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // Nombre del archivo CSV
    $filename = "clientes_veterinaria_" . date('Y-m-d') . ".csv";

    // Configurar encabezados HTTP para la descarga
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    // Abrir la salida para escribir el CSV
    $output = fopen('php://output', 'w');

    // Escribir encabezados de columnas en el CSV
    fputcsv($output, ['ID', 'Nombre', 'Direccion', 'Telefono', 'DNI', 'Fecha de Registro']);

    // Recorrer todos los clientes y escribir sus datos en el archivo CSV
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [
            $row['id'],
            $row['propietario'],
            $row['direccion'],
            $row['telefono'],
            $row['dni'],
            $row['fechaSeguimientoInicio']
        ]);
    }

    fclose($output);
    exit;
} else {
    echo "No se encontraron clientes.";
}

// Cerrar conexión
$conn->close();
?>
