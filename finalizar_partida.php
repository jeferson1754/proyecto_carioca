<?php
include 'bd.php'; // Conexión a la base de datos

$id_partida = isset($_GET['id']) ? $_GET['id'] : null;


// Consulta SQL para obtener el total de puntaje de cada jugador en la partida con id = 5
$sql = "
    SELECT jugador_id, SUM(puntaje) AS total_puntaje
    FROM detalles_partida
    WHERE partida_id = ?
    GROUP BY jugador_id
";

// Preparar la consulta
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_partida);  // Suponiendo que tienes el ID de la partida
$stmt->execute();
$result = $stmt->get_result();

// Suponiendo que ya tienes los resultados y los puntajes
while ($row = $result->fetch_assoc()) {
    $jugador_id = $row['jugador_id'];
    $total_puntaje = $row['total_puntaje'];
    $jugadores_puntajes[] = ['id' => $jugador_id, 'puntaje' => $total_puntaje];
}

// Determinar el jugador con el puntaje más alto y el más bajo
$max_puntaje = max(array_column($jugadores_puntajes, 'puntaje'));
$min_puntaje = min(array_column($jugadores_puntajes, 'puntaje'));

// Determinar al ganador y perdedor
$ganador = null;
$perdedor = null;
$empatador = null;

foreach ($jugadores_puntajes as $jugador) {
    if ($jugador['puntaje'] == $max_puntaje) {
        $perdedor = $jugador['id']; // El jugador con el puntaje más alto
    } else if ($jugador['puntaje'] == $min_puntaje) {
        $ganador = $jugador['id']; // El jugador con el puntaje más bajo
    } 
}

// Ahora, actualizamos las partidas para cada jugador
foreach ($jugadores_puntajes as $jugador) {
    $jugador_id = $jugador['id'];
    $puntaje = $jugador['puntaje'];

    // Obtener los valores actuales de las columnas para el jugador
    $sql = "SELECT partidas_jugadas, partidas_ganadas, partidas_perdidas, partidas_empatadas FROM jugadores WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $jugador_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $jugador_data = $result->fetch_assoc();

    // Incrementar partidas jugadas
    $partidas_jugadas = $jugador_data['partidas_jugadas'] + 1;

    // Determinar el resultado de la partida (ganada, perdida o empatada)
    if ($jugador_id == $ganador) {
        $partidas_ganadas = $jugador_data['partidas_ganadas'] + 1;
        $partidas_perdidas = $jugador_data['partidas_perdidas'];
        $partidas_empatadas = $jugador_data['partidas_empatadas'];
    } elseif ($jugador_id == $perdedor) {
        $partidas_ganadas = $jugador_data['partidas_ganadas'];
        $partidas_perdidas = $jugador_data['partidas_perdidas'] + 1;
        $partidas_empatadas = $jugador_data['partidas_empatadas'];
    } else {
        $partidas_ganadas = $jugador_data['partidas_ganadas'];
        $partidas_perdidas = $jugador_data['partidas_perdidas'];
        $partidas_empatadas = $jugador_data['partidas_empatadas'] + 1;
    }

    // Actualizar la tabla jugador
    $update_sql = "UPDATE jugadores SET 
                    partidas_jugadas = ?, 
                    partidas_ganadas = ?, 
                    partidas_perdidas = ?, 
                    partidas_empatadas = ? 
                    WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("iiiii", $partidas_jugadas, $partidas_ganadas, $partidas_perdidas, $partidas_empatadas, $jugador_id);
    $update_stmt->execute();

    echo $ganador . "<br>";
    echo $perdedor . "<br>";

    echo "UPDATE jugadores SET 
                    partidas_jugadas = $partidas_jugadas, 
                    partidas_ganadas = $partidas_ganadas, 
                    partidas_perdidas = $partidas_perdidas, 
                    partidas_empatadas = $partidas_empatadas 
                    WHERE id = $jugador_id";
}



// Redireccionar
header("Location: puntajes.php?id=" . urlencode($id_partida));

exit;
