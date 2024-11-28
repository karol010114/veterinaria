<?php
$conn = new mysqli("localhost", "root", "", "veterinaria");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$mensajeEliminacion = "";
/*eliminar cliente*/
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $mensajeEliminacion = "Cliente eliminado con éxito.";
    } else {
        $mensajeEliminacion = "Error al eliminar el cliente.";
    }
}

$sql = "SELECT id, propietario FROM clientes";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Propietarios</title>
    <link rel="stylesheet" href="../css/ver-detalle.css"> 
    <script>
        function eliminarCliente(id) {
            if (confirm("¿Seguro que deseas eliminar este cliente?")) {
                document.getElementById("form-eliminar-" + id).submit();
            }
        }
    </script>
</head>
<body>

<div class="propietario-lista">
    <h2>Lista de Propietarios</h2>

    <!-- Agregar enlace de descarga CSV aquí -->
  <!--  <a href="descargar_csv.php" class="btn-download">Descargar CSV</a>-->

    <?php if ($mensajeEliminacion): ?>
        <div class="mensaje-eliminacion">
            <?php echo $mensajeEliminacion; ?>
        </div>
    <?php endif; ?>

    <table class="lista">
        <tr>
            <th>ID</th>
            <th>Propietario</th>
            <th></th>
        </tr>

        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr id='fila-" . $row["id"] . "'>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . $row["propietario"] . "</td>";
                echo "<td>
                        <a href='detalle-propietario.php?id=" . $row["id"] . "' class='btn-info'>MÁS INFORMACIÓN</a>
                        <form id='form-eliminar-" . $row["id"] . "' action='' method='POST' style='display:inline-block;'>
                            <input type='hidden' name='id' value='" . $row["id"] . "'>
                            <button type='button' onclick='eliminarCliente(" . $row["id"] . ")'>Eliminar cliente</button>
                        </form>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No hay propietarios registrados</td></tr>";
        }
        $conn->close();
        ?>
    </table>
</div>

<div class="volver">
    <a href="ventanas.php" class="btn-volver">Volver a la Página Principal</a>
</div>

<form method="POST" action="exportar_clientes.php">
    <button type="submit">Descargar datos de los clientes</button>
</form>

</body>
</html>
