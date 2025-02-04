<?php
include 'bd.php';

$id_partida = isset($_GET['id']) ? $_GET['id'] : null;
// Obtener los datos de la partida 5
$sql = "SELECT j.id AS jugador_id, j.nombre, SUM(dp.puntaje) AS total_puntaje 
        FROM jugadores j
        JOIN detalles_partida dp ON j.id = dp.jugador_id
        WHERE dp.partida_id = $id_partida
        GROUP BY j.id
        ORDER BY total_puntaje ASC";

$result = $conn->query($sql);

$jugadores = [];
if ($result->num_rows > 0) {
    // Obtener los datos de los jugadores
    while ($row = $result->fetch_assoc()) {
        $jugadores[] = $row;
    }
} else {
    echo "No se encontraron jugadores para la partida $id_partida.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clasificación de la Partida 5</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .gold {
            color: gold;
        }

        .silver {
            color: silver;
        }

        .bronze {
            color: #cd7f32;
        }

        .icon {
            font-size: 18px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="text-center">Clasificación de la Partida <?php echo $id_partida ?>°</h2>

        <table>
            <thead>
                <tr>
                    <th>Posición</th>
                    <th>Jugador</th>
                    <th>Puntaje</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $position = 1;
                foreach ($jugadores as $jugador) {
                    $nombre = htmlspecialchars($jugador['nombre']);
                    $total_puntaje = $jugador['total_puntaje'];

                    // Determinar el ícono para el primer, segundo y tercer lugar
                    if ($position == 1) {
                        $icon = "<i class='fas fa-trophy gold icon'></i>";  // Oro
                        $class = "gold";
                    } elseif ($position == 2) {
                        $icon = "<i class='fas fa-trophy silver icon'></i>";  // Plata
                        $class = "silver";
                    } elseif ($position == 3) {
                        $icon = "<i class='fas fa-trophy bronze icon'></i>";  // Bronce
                        $class = "bronze";
                    } else {
                        $icon = "<i class='fas fa-medal icon'></i>";  // Medalla común
                        $class = "";
                    }

                    // Imprimir cada fila de la tabla
                    echo "<tr>";
                    echo "<td class='$class'>$position °</td>";
                    echo "<td>$nombre $icon</td>";
                    echo "<td>$total_puntaje</td>";
                    echo "</tr>";

                    $position++;
                }
                ?>
            </tbody>
        </table>
        <div class="text-center mt-4">
            <a href="./historial.php">
                <button type="button" class="btn btn-secondary">Volver</button>
            </a>
        </div>

    </div>


</body>

</html>