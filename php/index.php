<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historia Clínica Veterinaria</title>
    <link rel="stylesheet" type="text/css" href="../css/index.css">
</head>
<body>
    <form action="conexion.php" method="POST">
 

        <div class="cliente-container">
            <!-- Columna 1 -->
            <div class="column">
                <div class="pacios-letra">
                    <label>Propietario:
                    <input type="text" name="propietario" placeholder="Escriba su Nombre" required></label>
                </div>
                <div class="pacios-letra">
                    <label>Dirección:
                    <input type="text" name="direccion" placeholder="Escriba su dirección"></label>
                </div>
                <div class="pacios-letra">
                    <label>Teléfono:
                    <input type="text" name="telefono" placeholder="Escriba su Teléfono" required></label>
                </div>
            </div>
            <!-- Columna 2 -->
            <div class="column">
                <div class="pacios-letra">
                    <label>Nombre de mascota:
                    <input type="text" name="paciente" placeholder="Escriba Nombre" required></label>
                </div>
                <div class="pacios-letra">
                    <label>Fecha de nacimiento:
                    <input type="date" id="fechaNacimiento" name="fechaNacimiento" required></label>
                </div>
                <div class="pacios-letra">
                    <label>DNI:
                    <input type="text" name="dni" placeholder="Escriba el DNI del cliente" required></label>
                </div>
            </div>
        </div>


    <!-- Contenedor para especies (izquierda) -->
    <div class="especies-container">
    <label for="Sexo">Especie:</label>
        <label for="Canino">Canino</label>
        <input type="radio" id="Canino" name="especie" value="Canino">
        <label for="Felino">Felino</label>
        <input type="radio" id="Felino" name="especie" value="Felino">
        <label for="Aves">Aves</label>
        <input type="radio" id="Aves" name="especie" value="Aves">
        <label for="Lagomorfos">Lagomorfos</label>
        <input type="radio" id="Lagomorfos" name="especie" value="Lagomorfos">
        <label for="otros">Otros</label>
        <input type="radio" id="otros" name="especie" value="otros">
    </div>

    <!-- Contenedor para sexos (derecha) -->
    <div class="sexos-container">
        <label for="Sexo">Sexo:</label>
        <input type="radio" name="sexo" id="masculino" value="masculino"> macho
        <input type="radio" name="sexo" id="femenino" value="femenino"> hembra
    </div>

<!-- Contenedor para Raza y Color -->
<div class="raza-color-container">
    <!-- Raza a la izquierda -->
    <div class="raza-container">
        <label>Raza: <input type="text" name="raza" placeholder="Qué raza es" required></label>
    </div>
    
    <!-- Color a la derecha -->
    <div class="color-container">
        <label>Color: <input type="text" name="color" placeholder="Color de la mascota" required></label>
    </div>
</div>


<!-- Contenedor para fechas de seguimiento -->
<div class="fechas-container">
    <!-- Fecha de inicio a la izquierda -->
    <div class="fecha-inicio-container">
        <label for="fechaSeguimientoInicio">Fecha de inicio:</label>
        <input type="date" id="fechaSeguimientoInicio" name="fechaSeguimientoInicio">
    </div>
</div>


        <button type="button" id="btndescripcion">Descripción</button>
        <div id="notas"></div> 
        <div class="submit-group">
            <button type="submit">Enviar datos</button>
            <input type="hidden" name="descripcion" id="descripcion">
        </div>
    </form>
    <script src="../js/index.js"></script>
</body>
</html>
