<?php
$conn = new mysqli("localhost", "root", "", "veterinaria");


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


$propietario = $_POST['propietario'];
$direccion = $_POST['direccion'];
$telefono = $_POST['telefono'];
$paciente = $_POST['paciente'];
$fechaNacimiento = $_POST['fechaNacimiento'];
$dni = $_POST['dni'];
$especie = $_POST['especie'];
$raza = $_POST['raza'];
$sexo = $_POST['sexo'];
$color = $_POST['color'];
$fechaSeguimientoInicio = $_POST['fechaSeguimientoInicio'];
$descripcion = $_POST['descripcion'];

if ($sexo != 'masculino' && $sexo != 'femenino') {
    echo "Valor de sexo no válido. Solo se permiten 'masculino' o 'femenino'.";
    exit;
}

// Verificar si el cliente ya existe por el DNI
$sql_verificar_cliente = "SELECT id FROM clientes WHERE dni = '$dni'";
$resultado_cliente = $conn->query($sql_verificar_cliente);

if ($resultado_cliente->num_rows > 0) {
    $cliente_existente = $resultado_cliente->fetch_assoc();
    $cliente_id = $cliente_existente['id'];

    $sql_mascota = "INSERT INTO mascotas (nombre, especie, raza, sexo, color, fechaNacimiento, propietario_id)
                    VALUES ('$paciente', '$especie', '$raza', '$sexo', '$color', '$fechaNacimiento', '$cliente_id')";

    if ($conn->query($sql_mascota) === TRUE) {
        echo "Mascota registrada con éxito para el cliente existente.";
    } else {
        echo "Error al registrar la mascota: " . $conn->error;
    }

} else {
    $sql_cliente = "INSERT INTO clientes (propietario, direccion, telefono, dni, paciente, fechaNacimiento, especie, raza, sexo, color, fechaSeguimientoInicio, descripcion)
                    VALUES ('$propietario', '$direccion', '$telefono', '$dni', '$paciente', '$fechaNacimiento', '$especie', '$raza', '$sexo', '$color', '$fechaSeguimientoInicio', '$descripcion')";

    if ($conn->query($sql_cliente) === TRUE) {
        $cliente_id = $conn->insert_id;
        $sql_mascota = "INSERT INTO mascotas (nombre, especie, raza, sexo, color, fechaNacimiento, propietario_id)
                        VALUES ('$paciente', '$especie', '$raza', '$sexo', '$color', '$fechaNacimiento', '$cliente_id')";

        if ($conn->query($sql_mascota) === TRUE) {
            echo "Cliente y mascota registrados con éxito.";
        } else {
            echo "Error al registrar la mascota: " . $conn->error;
        }
    } else {
        echo "Error al registrar el cliente: " . $conn->error;
    }
}
$conn->close();
?>

<div class="volver">
    <a href="ventanas.php" class="btn-volver">Volver a la Página Principal</a>
</div>
