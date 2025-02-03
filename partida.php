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
        JOIN usuarios u ON dp.jugador_id = u.id
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
    }
}

// Determinar colores según el puntaje
$puntajes = array_column($jugadores, 'puntajes');
$max_puntaje = max(array_map('max', $puntajes));
$min_puntaje = min(array_map('min', $puntajes));
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
            color: red;
        }

        .puntaje-verde {
            color: green;
        }

        .puntaje-gris {
            color: gray;
        }
    </style>
</head>

<body class="container mt-4">
    <div class="text-center" style="text-transform:capitalize">
        <h1><?php echo implode(", ", $juegos); ?></h1> <!-- Mostrar todos los juegos -->
    </div>

    <div class="d-flex justify-content-center gap-4">
        <?php foreach ($jugadores as $jugador): ?>
            <div class="border p-3 text-center">
                <h5><?php echo htmlspecialchars($jugador['nombre']); ?></h5>
                <p>??%</p> <!-- Aquí iría la probabilidad de ganar -->
            </div>
        <?php endforeach; ?>
    </div>

    <form method="POST" action="guardar_puntajes.php">
        <input type="hidden" name="id_partida" value="<?php echo htmlspecialchars($partida_id); ?>">

        <table class="table mt-4 text-center">
            <thead>
                <tr>
                    <th>Juegos</th>
                    <?php foreach ($jugadores as $jugador): ?>
                        <th><?php echo htmlspecialchars($jugador['nombre']); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($juegos as $index => $id_juego): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($id_juego); ?></td>
                        <?php foreach ($jugadores as $id_jugador => $jugador): ?>
                            <td class="<?php
                                        $puntaje = isset($jugador['puntajes'][$id_juego]) ? $jugador['puntajes'][$id_juego] : 0;
                                        echo ($puntaje == $max_puntaje) ? 'puntaje-rojo' : (($puntaje == $min_puntaje) ? 'puntaje-verde' : 'puntaje-gris');
                                        ?>">
                                <input type="number" class="form-control text-center"
                                    name="puntajes[<?php echo $id_jugador; ?>][<?php echo $id_juego; ?>]"
                                    value="<?php echo isset($jugador['puntajes'][$id_juego]) ? $jugador['puntajes'][$id_juego] : 0; ?>"
                                    <?php echo ($index !== count($juegos) - 1) ? 'disabled' : ''; ?> required>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <button type="submit" class="btn btn-primary">Siguiente Juego →</button>
        </div>
    </form>

</body>

</html>