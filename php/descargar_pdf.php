<?php
require('../libs/fpdf/fpdf.php'); // Asegúrate de incluir la librería FPDF

$conn = new mysqli("localhost", "root", "", "veterinaria");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta del propietario
$sql = "SELECT * FROM clientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("Propietario no encontrado.");
}

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Encabezado
$pdf->SetTextColor(0, 51, 102); // Color azul oscuro
$pdf->Cell(0, 10, 'Detalles del Propietario', 0, 1, 'C');
$pdf->Ln(10); // Espaciado

// Separador decorativo
$pdf->SetDrawColor(0, 51, 102);
$pdf->SetLineWidth(0.5);
$pdf->Line(10, 30, 200, 30); // Línea horizontal
$pdf->Ln(5);

// Estilo general
$pdf->SetFont('Arial', '', 12);
$pdf->SetTextColor(0, 0, 0); // Color negro

// Contenido del propietario
$pdf->SetFillColor(240, 240, 240); // Color gris claro para fondo

// Orden correcto de las celdas
$pdf->Cell(50, 10, 'ID:', 1, 0, 'L', true);
$pdf->Cell(0, 10, $row['id'], 1, 1, 'L');

$pdf->Cell(50, 10, 'Propietario:', 1, 0, 'L', true);
$pdf->Cell(0, 10, $row['propietario'], 1, 1, 'L');

$pdf->Cell(50, 10, 'Direccion:', 1, 0, 'L', true);
$pdf->Cell(0, 10, $row['direccion'], 1, 1, 'L');

$pdf->Cell(50, 10, 'Telefono:', 1, 0, 'L', true);
$pdf->Cell(0, 10, $row['telefono'], 1, 1, 'L');

$pdf->Cell(50, 10, 'DNI:', 1, 0, 'L', true);
$pdf->Cell(0, 10, $row['dni'], 1, 1, 'L');

// Asegurarse de que la fecha está bien formateada
$fecha_registro = date("d/m/Y", strtotime($row['fechaSeguimientoInicio']));
$pdf->Cell(50, 10, 'Fecha de Registro:', 1, 0, 'L', true);
$pdf->Cell(0, 10, $fecha_registro, 1, 1, 'L');

// Consulta de mascotas asociadas
$sql_mascotas = "SELECT * FROM mascotas WHERE propietario_id = ?";
$stmt_mascotas = $conn->prepare($sql_mascotas);
$stmt_mascotas->bind_param("i", $id);
$stmt_mascotas->execute();
$result_mascotas = $stmt_mascotas->get_result();

$pdf->Ln(10); // Espaciado

// Encabezado de mascotas
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetTextColor(0, 51, 102);
$pdf->Cell(0, 10, 'Mascotas del Propietario:', 0, 1, 'L');
$pdf->Ln(5);

// Tabla de mascotas con encabezados
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(200, 200, 200); // Fondo gris claro para encabezados
$pdf->SetTextColor(0, 0, 0); // Texto negro
$pdf->SetDrawColor(200, 200, 200); // Bordes gris claro

$pdf->Cell(50, 10, 'Nombre', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Especie', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Raza', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Fecha de Nacimiento', 1, 1, 'C', true);

// Datos de las mascotas
$pdf->SetFont('Arial', '', 12);
$pdf->SetFillColor(240, 240, 240); // Fondo gris claro
$pdf->SetTextColor(0, 0, 0); // Texto negro

if ($result_mascotas->num_rows > 0) {
    while ($mascota = $result_mascotas->fetch_assoc()) {
        $pdf->Cell(50, 10, $mascota['nombre'], 1, 0, 'L', true);
        $pdf->Cell(50, 10, $mascota['especie'], 1, 0, 'L', true);
        $pdf->Cell(50, 10, $mascota['raza'], 1, 0, 'L', true);
        $pdf->Cell(50, 10, date("d/m/Y", strtotime($mascota['fechaNacimiento'])), 1, 1, 'L', true);
    }
} else {
    $pdf->Cell(0, 10, 'No hay mascotas registradas.', 1, 1, 'L', true);
}

$pdf->Ln(10); // Espaciado

// Agregar cuadro de Descripción (nuevo cuadro)
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetTextColor(0, 51, 102);
$pdf->Cell(0, 10, 'ANAMESIS:', 0, 1, 'L');
$pdf->Ln(5);

// Caja de descripción
$pdf->SetFont('Arial', '', 12);
$pdf->SetFillColor(240, 240, 240); // Fondo gris claro
$pdf->SetTextColor(0, 0, 0); // Texto negro

// Agregar el contenido de la descripción, asegúrate de que este campo exista en tu base de datos
$descripcion = isset($row['descripcion']) ? $row['descripcion'] : 'No se proporcionó descripción.';
$pdf->MultiCell(0, 10, $descripcion, 1, 'L', true); // MultiCell permite que el texto se ajuste

$conn->close();

// Salida del PDF
$pdf->Output('D', 'propietario_' . $id . '.pdf');
?>
