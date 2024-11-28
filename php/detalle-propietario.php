<?php
$conn = new mysqli("localhost", "root", "", "veterinaria");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$mascota_id = isset($_GET['mascota_id']) ? intval($_GET['mascota_id']) : 0;

$sql = "SELECT * FROM clientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die("No se encontró el propietario.");
}

$sql_mascotas = "SELECT * FROM mascotas WHERE propietario_id = ?";
$stmt_mascotas = $conn->prepare($sql_mascotas);
$stmt_mascotas->bind_param("i", $id);
$stmt_mascotas->execute();
$result_mascotas = $stmt_mascotas->get_result();

$mascota_detalles = null;
if ($mascota_id > 0) {
    $sql_mascota = "SELECT * FROM mascotas WHERE id = ?";
    $stmt_mascota = $conn->prepare($sql_mascota);
    $stmt_mascota->bind_param("i", $mascota_id);
    $stmt_mascota->execute();
    $result_mascota = $stmt_mascota->get_result();
    if ($result_mascota->num_rows > 0) {
        $mascota_detalles = $result_mascota->fetch_assoc();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_cliente'])) {
    $nuevo_nombre = $_POST['propietario'];
    $nueva_direccion = $_POST['direccion'];
    $nuevo_telefono = $_POST['telefono'];
    $nuevo_dni = $_POST['dni'];
    $nueva_fecha_inicio = $_POST['fechaSeguimientoInicio'];

    $sql_editar = "UPDATE clientes SET propietario = ?, direccion = ?, telefono = ?, dni = ?, fechaSeguimientoInicio = ? WHERE id = ?";
    $stmt_editar = $conn->prepare($sql_editar);
    $stmt_editar->bind_param("sssssi", $nuevo_nombre, $nueva_direccion, $nuevo_telefono, $nuevo_dni, $nueva_fecha_inicio, $id);

    if ($stmt_editar->execute()) {
        $mensaje = "Datos del propietario actualizados correctamente.";
        // Volver a cargar los datos actualizados
        $row['propietario'] = $nuevo_nombre;
        $row['direccion'] = $nueva_direccion;
        $row['telefono'] = $nuevo_telefono;
        $row['dni'] = $nuevo_dni;
        $row['fechaSeguimientoInicio'] = $nueva_fecha_inicio;
    } else {
        $mensaje = "Error al actualizar los datos del propietario.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_mascota'])) {
    $nuevo_nombre = $_POST['nombre'];
    $nueva_especie = $_POST['especie'];
    $nueva_raza = $_POST['raza'];
    $nueva_fecha_nacimiento = $_POST['fechaNacimiento'];

    $sql_editar_mascota = "UPDATE mascotas SET nombre = ?, especie = ?, raza = ?, fechaNacimiento = ? WHERE id = ?";
    $stmt_editar_mascota = $conn->prepare($sql_editar_mascota);
    $stmt_editar_mascota->bind_param("ssssi", $nuevo_nombre, $nueva_especie, $nueva_raza, $nueva_fecha_nacimiento, $mascota_id);
    if ($stmt_editar_mascota->execute()) {
        echo "Actualización exitosa de la mascota."; 
        $mensaje_mascota = "Datos de la mascota actualizados correctamente.";
        $mascota_detalles['nombre'] = $nuevo_nombre;
        $mascota_detalles['especie'] = $nueva_especie;
        $mascota_detalles['raza'] = $nueva_raza;
        $mascota_detalles['fechaNacimiento'] = $nueva_fecha_nacimiento;
    } else {
        echo "Error al actualizar los datos de la mascota: " . $stmt_editar_mascota->error;
        $mensaje_mascota = "Error al actualizar los datos de la mascota.";
    }
   
    if ($stmt_editar_mascota->execute()) {
        echo "Actualización exitosa de la mascota."; 
        $mensaje_mascota = "Datos de la mascota actualizados correctamente.";
    } else {
        echo "Error al actualizar los datos de la mascota: " . $stmt_editar_mascota->error;
        $mensaje_mascota = "Error al actualizar los datos de la mascota.";
    }
    
    
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Propietario</title>
    <link rel="stylesheet" href="../css/ver-detalle.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

</head>
<body>

<div id="tarjeta" class="tarjeta">
    <h2>Detalles del Propietario</h2>
    <?php if (isset($mensaje)) { echo "<p>$mensaje</p>"; } ?>
    <p><strong>ID:</strong> <?php echo $row['id']; ?></p>
    <p><strong>Propietario:</strong> <?php echo $row['propietario']; ?></p>
    <p><strong>Dirección:</strong> <?php echo $row['direccion']; ?></p>
    <p><strong>Teléfono:</strong> <?php echo $row['telefono']; ?></p>
    <p><strong>DNI:</strong> <?php echo $row['dni']; ?></p>
    <p><strong>Fecha de registro:</strong> <?php echo $row['fechaSeguimientoInicio']; ?></p>

   

<?php if ($mascota_detalles): ?>
    <div>
        <h3>Detalles de la Mascota</h3>
        <p><strong>Nombre:</strong> <?php echo $mascota_detalles['nombre']; ?></p>
        <p><strong>Especie:</strong> <?php echo $mascota_detalles['especie']; ?></p>
        <p><strong>Raza:</strong> <?php echo $mascota_detalles['raza']; ?></p>
        <p><strong>Fecha de Nacimiento:</strong> <?php echo $mascota_detalles['fechaNacimiento']; ?></p>
        <p><strong>descripcion</strong> <?php echo $row['descripcion']; ?></p>
    </div>
<?php endif; ?>

    <button onclick="document.getElementById('form-editar').style.display = 'block';">Editar</button>
    
    <a href="descargar_pdf.php?id=<?php echo $row['id']; ?>">Descargar en PDF</a>
    <a href="descargar_excel.php?id=<?php echo $row['id']; ?>">Descargar en Excel</a>
    <button onclick="descargarTarjeta()">Descargar imagen</button>
    
    <div id="form-editar" style="display:none;">
    <h3>Editar Propietario</h3>
    <form method="POST">
    <label for="propietario">Propietario:</label>
    <input type="text" id="propietario" name="propietario" value="<?php echo isset($row['propietario']) ? $row['propietario'] : ''; ?>" required>
    <label for="direccion">Dirección:</label>
    <input type="text" id="direccion" name="direccion" value="<?php echo isset($row['direccion']) ? $row['direccion'] : ''; ?>" required>
    <label for="telefono">Teléfono:</label>
    <input type="text" id="telefono" name="telefono" value="<?php echo isset($row['telefono']) ? $row['telefono'] : ''; ?>" required>
    <label for="dni">DNI:</label>
    <input type="text" id="dni" name="dni" value="<?php echo isset($row['dni']) ? $row['dni'] : ''; ?>" required>

    <label for="fechaSeguimientoInicio">Fecha de registro:</label>
    <input type="date" id="fechaSeguimientoInicio" name="fechaSeguimientoInicio" value="<?php echo isset($row['fechaSeguimientoInicio']) ? $row['fechaSeguimientoInicio'] : ''; ?>" required>

    <button type="submit" name="editar_cliente">Guardar Cambios</button>
</form>
<h3>Editar Mascota</h3>
<form method="POST">
    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="nombre" value="<?php echo isset($mascota_detalles['nombre']) ? $mascota_detalles['nombre'] : ''; ?>" required>
    
    <label for="especie">Especie:</label>
    <select id="especie" name="especie" required>
        <option value="">Seleccionar</option>
        <option value="Canino" <?php echo (isset($mascota_detalles['especie']) && $mascota_detalles['especie'] == 'Canino') ? 'selected' : ''; ?>>Canino</option>
        <option value="Felino" <?php echo (isset($mascota_detalles['especie']) && $mascota_detalles['especie'] == 'Felino') ? 'selected' : ''; ?>>Felino</option>
        <option value="Aves" <?php echo (isset($mascota_detalles['especie']) && $mascota_detalles['especie'] == 'Aves') ? 'selected' : ''; ?>>Aves</option>
        <option value="Lagomorfos" <?php echo (isset($mascota_detalles['especie']) && $mascota_detalles['especie'] == 'Lagomorfos') ? 'selected' : ''; ?>>Lagomorfos</option>
        <option value="Otros" <?php echo (isset($mascota_detalles['especie']) && $mascota_detalles['especie'] == 'Otros') ? 'selected' : ''; ?>>Otros</option>
    </select><br>

    <label for="raza">Raza:</label>
    <input type="text" id="raza" name="raza" value="<?php echo isset($mascota_detalles['raza']) ? $mascota_detalles['raza'] : ''; ?>" required>
    
    <label for="fechaNacimiento">Fecha de Nacimiento:</label>
    <input type="date" id="fechaNacimiento" name="fechaNacimiento" value="<?php echo isset($mascota_detalles['fechaNacimiento']) ? $mascota_detalles['fechaNacimiento'] : ''; ?>" required>
    
    <button type="submit" name="editar_mascota">Guardar Cambios</button>
</form>


</div>
<script>
        function descargarTarjeta() {
            const tarjeta = document.getElementById('tarjeta');
            
            html2canvas(tarjeta).then((canvas) => {
                const imagen = canvas.toDataURL("image/png");
                const enlace = document.createElement("a");
                enlace.href = imagen;
                enlace.download = "tarjeta_presentacion.png";
                enlace.click();
            });
        }
    </script>

</div>

<div class="cuadro-mascotas">
    <h3>Otras Mascotas del Propietario</h3>
    <?php if ($result_mascotas->num_rows > 0): ?>
        <ul>
            <?php while ($mascota = $result_mascotas->fetch_assoc()): ?>
                <li>
                    <strong>Nombre:</strong> <?php echo $mascota['nombre']; ?>, 
                    <strong>Especie:</strong> <?php echo $mascota['especie']; ?>, 
                    <a href="detalle-propietario.php?id=<?php echo $id; ?>&mascota_id=<?php echo $mascota['id']; ?>"><button>Más información</button></a>
                </li>
            <?php endwhile; ?>
        </ul>
    <?php else: ?>
        <p>No hay mascotas registradas para este propietario.</p>
    <?php endif; ?>
</div>



<!--<button onclick="window.history.back();" class="btn-volver">Regresar</button>-->
<div class="volver">
    <a href="ventanas.php" class="btn-volver">volver a gestion de pacientes</a>
</div>



</body>
</html>