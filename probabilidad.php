<?php
// Incluir archivo de conexión a la base de datos (asegurándote de que bd.php esté configurado)
include 'bd.php';

// Consulta para obtener los datos de los jugadores
$sql = "SELECT id, nombre, partidas_jugadas, partidas_ganadas, partidas_perdidas, partidas_empatadas
        FROM jugadores";
$result = $conn->query($sql);

// Comprobar si se obtuvieron resultados
if ($result->num_rows > 0) {
    // Mostrar los resultados
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $nombre = $row['nombre'];
        $partidas_jugadas = $row['partidas_jugadas'];
        $partidas_ganadas = $row['partidas_ganadas'];

        // Calcular la probabilidad de ganar (solo si se han jugado partidas)
        if ($partidas_jugadas > 0) {
            $probabilidad_ganar = $partidas_ganadas / $partidas_jugadas;
        } else {
            $probabilidad_ganar = 0;  // Si no ha jugado partidas, la probabilidad de ganar es 0
        }

        // Mostrar los resultados
        echo "Jugador: $nombre - Probabilidad de ganar: " . number_format($probabilidad_ganar * 100, 2) . "%<br>";
    }
} else {
    echo "No se encontraron jugadores.";
}
