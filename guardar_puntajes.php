<?php
include 'bd.php'; // Conexión a la base de datos

$id_partida = $_POST['id_partida'];
$nombre_juego = $_POST['juego'];
$puntajes = $_POST['puntajes'];


$sql = "SELECT ID FROM tipo_juego WHERE juego = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nombre_juego);
$stmt->execute();
$result = $stmt->get_result();
$ultimo_juego = $result->fetch_assoc();
$id_ultimo_juego = $ultimo_juego['ID'];

if (!$id_ultimo_juego) {
    die("Error: No se encontró el ID del juego '$nombre_juego'");
}

var_dump($puntajes);
$id_array = $id_ultimo_juego - 1;


foreach ($puntajes as $id_jugador => $juegos) {
    // Acceder al puntaje del jugador para el juego con ID 3 (o el juego que corresponda)
    // Se asume que el juego ID 3 es el único en este caso, pero si hay varios juegos, debes ajustarlo según la lógica.

    $puntaje_jugador = isset($juegos[$id_array]) ? intval($juegos[$id_array]) : 0;

    $sql = "UPDATE detalles_partida 
            SET puntaje = ? 
            WHERE partida_id = ? 
            AND jugador_id = ? 
            AND tipo_juego_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiii", $puntaje_jugador, $id_partida, $id_jugador, $id_ultimo_juego);
    $stmt->execute();
}

if (isset($_POST['omitir'])) {
    // Obtener el siguiente ID de juego basado en el actual
    $sql = "SELECT ID FROM tipo_juego WHERE ID > ? ORDER BY ID ASC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_ultimo_juego);
    $stmt->execute();
    $result = $stmt->get_result();
    $siguiente_juego = $result->fetch_assoc();
    $id_siguiente_juego = $siguiente_juego['ID'] ?? null;

    if ($id_siguiente_juego) {
        // Recorrer cada jugador para insertar el siguiente juego solo si no existe ya una entrada
        foreach ($puntajes as $id_jugador => $juegos) {
            // Verificar si ya existe una entrada en la tabla 'detalles_partida'
            $sql_check = "SELECT 1 FROM detalles_partida WHERE partida_id = ? AND jugador_id = ? AND tipo_juego_id = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("iii", $id_partida, $id_jugador, $id_siguiente_juego);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            // Si no existe, insertar el nuevo puntaje con valor inicial
            if ($result_check->num_rows == 0) {
                $puntaje_inicial = 0; // Puntaje predeterminado

                $sql_insert = "INSERT INTO detalles_partida (puntaje, partida_id, jugador_id, tipo_juego_id) 
                        VALUES (?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("iiii", $puntaje_inicial, $id_partida, $id_jugador, $id_siguiente_juego);
                $stmt_insert->execute();
            }
        }
    }
} else {
    // Obtener el siguiente ID de juego basado en el actual
    $sql = "SELECT ID FROM tipo_juego WHERE ID > ? ORDER BY ID ASC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_ultimo_juego);
    $stmt->execute();
    $result = $stmt->get_result();
    $siguiente_juego = $result->fetch_assoc();
    $id_siguiente_juego = $siguiente_juego['ID'] ?? null;

    if ($id_siguiente_juego) {
        // Recorrer cada jugador para insertar el siguiente juego solo si no existe ya una entrada
        foreach ($puntajes as $id_jugador => $juegos) {
            // Verificar si ya existe una entrada en la tabla 'detalles_partida'
            $sql_check = "SELECT 1 FROM detalles_partida WHERE partida_id = ? AND jugador_id = ? AND tipo_juego_id = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("iii", $id_partida, $id_jugador, $id_siguiente_juego);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            // Si no existe, insertar el nuevo puntaje con valor inicial
            if ($result_check->num_rows == 0) {
                $puntaje_inicial = 0; // Puntaje predeterminado

                $sql_insert = "INSERT INTO detalles_partida (puntaje, partida_id, jugador_id, tipo_juego_id) 
                        VALUES (?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("iiii", $puntaje_inicial, $id_partida, $id_jugador, $id_siguiente_juego);
                $stmt_insert->execute();
            }
        }
    }
}
// Redireccionar
header("Location: partida.php");
exit;
