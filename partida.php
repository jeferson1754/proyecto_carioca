<?php
include 'bd.php'; // Conexión a la base de datos

// Obtener la última partida
$sql = "SELECT * FROM partidas ORDER BY fecha DESC LIMIT 1";
$result = $conn->query($sql);
$partida = $result->fetch_assoc();
$partida_id = $partida['id'];

// Obtener detalles de la partida con los jugadores y sus puntajes
$sql = "SELECT u.id as id_jugador, u.nombre, dp.puntaje, tj.juego, tj.ID as id_juego
        FROM detalles_partida dp
        JOIN jugadores u ON dp.jugador_id = u.id
        JOIN tipo_juego tj ON dp.tipo_juego_id = tj.ID
        WHERE dp.partida_id = $partida_id";
$result = $conn->query($sql);

$jugadores = [];
$juegos = [];

while ($row = $result->fetch_assoc()) {
    // Agrupar los puntajes por jugador y juego
    $jugadores[$row['id_jugador']]['nombre'] = $row['nombre'];
    $jugadores[$row['id_jugador']]['puntajes'][$row['id_juego']] = $row['puntaje'];

    // Agregar el tipo de juego solo una vez
    if (!in_array($row['juego'], $juegos)) {
        $juegos[] = $row['juego'];
        $juego = $row['juego'];
    }
}

// Obtener la cantidad de partidas jugadas y ganadas por cada jugador
$sql = "SELECT * FROM jugadores;";
$result = $conn->query($sql);

// Calcular las probabilidades
$probabilidades = [];
while ($row = $result->fetch_assoc()) {
    $jugador_id = $row['id'];
    $partidas_jugadas = $row['partidas_jugadas'];
    $partidas_ganadas = $row['partidas_ganadas'];

    // Calcular la probabilidad de ganar (si se han jugado partidas)
    if ($partidas_jugadas > 0) {
        $probabilidades[$jugador_id] = $partidas_ganadas / $partidas_jugadas;
    } else {
        $probabilidades[$jugador_id] = 0;  // Si no ha jugado partidas, la probabilidad es 0
    }
}

// Determinar colores según el puntaje
$puntajes = array_column($jugadores, 'puntajes');
$max_puntaje = max(array_map('max', $puntajes));
$min_puntaje = min(array_map('min', $puntajes));

$cantidad_juegos = count($juegos);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego Actual</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        .puntaje-rojo {
            color: red !important;
        }

        .puntaje-verde {
            color: green !important;
        }

        .puntaje-gris {
            color: gray;
        }
    </style>
</head>

<body class="container mt-4">
    <div class="text-center" style="text-transform:capitalize">
        <h1><?php echo $juego; ?></h1> <!-- Mostrar todos los juegos -->
    </div>



    <div class="d-flex justify-content-center gap-4">
        <?php
        foreach ($jugadores as $id_jugador => $jugador): ?>
            <div class="border p-3 text-center">
                <h5><?php echo htmlspecialchars($jugador['nombre']); ?></h5>
                <!-- Mostrar la probabilidad de ganar -->
                <p><?php
                    $probabilidad = isset($probabilidades[$id_jugador]) ? $probabilidades[$id_jugador] : 0;
                    echo number_format($probabilidad * 100, 2) . '%'; // Mostrar la probabilidad como porcentaje
                    ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <form method="POST" action="guardar_puntajes.php">
        <input type="hidden" name="id_partida" value="<?php echo htmlspecialchars($partida_id); ?>">
        <input type="hidden" name="juego" value="<?php echo htmlspecialchars($juego); ?>">

        <table class="table mt-4 text-center">
            <thead>
                <tr>
                    <th>Juegos</th>
                    <?php foreach ($jugadores as $id_jugador => $jugador): ?>
                        <th><?php echo htmlspecialchars($jugador['nombre']); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($juegos as $index => $nombre_juego): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($nombre_juego); ?></td>
                        <?php foreach ($jugadores as $id_jugador => $jugador): ?>
                            <?php
                            $puntaje = isset($jugador['puntajes'][$index + 1]) ? intval($jugador['puntajes'][$index + 1]) : 0;
                            ?>
                            <td>
                                <input type="number" class="form-control text-center"
                                    name="puntajes[<?php echo $id_jugador; ?>][<?php echo $index; ?>]"
                                    value="<?php echo $puntaje; ?>"
                                    <?php echo ($index !== count($juegos) - 1) ? 'disabled' : ''; ?> required>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>

                <!-- Fila para los totales -->
                <tr>
                    <td><strong>Total:</strong></td>
                    <?php
                    // Obtener los totales de puntajes de cada jugador
                    $totales = [];
                    foreach ($jugadores as $id_jugador => $jugador) {
                        $totales[$id_jugador] = array_sum($jugador['puntajes']);
                    }

                    // Encontrar el puntaje máximo y mínimo
                    $max_puntaje = max($totales);
                    $min_puntaje = min($totales);
                    ?>
                    <?php foreach ($jugadores as $id_jugador => $jugador): ?>
                        <?php
                        // Sumar los puntajes de todos los juegos para cada jugador
                        $total_puntajes = $totales[$id_jugador];
                        // Determinar el color según el puntaje
                        if ($total_puntajes == $max_puntaje) {
                            $class = 'puntaje-rojo'; // Color rojo para el puntaje más alto
                        } elseif ($total_puntajes == $min_puntaje) {
                            $class = 'puntaje-verde'; // Color verde para el puntaje más bajo
                        } else {
                            $class = 'puntaje-gris'; // Color gris para los puntajes intermedios
                        }
                        ?>
                        <td class="<?php echo $class; ?>"><strong>???</strong></td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
        <br>

        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-secondary mx-2" name="omitir">Omitir</button>
            <div class="d-flex justify-content-center w-100">
                <button type="submit" class="btn btn-primary mx-2">Siguiente Juego →</button>
            </div>
            <?php if ($cantidad_juegos >= 5): ?>
                <a href="finalizar_partida.php?id=<?php echo urlencode($partida_id); ?>">
                    <button type="button" class="btn btn-success mx-2">Finalizar</button>
                </a>
            <?php endif; ?>
        </div>
        <br>
    </form>

</body>

</html>