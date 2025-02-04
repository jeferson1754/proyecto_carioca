<?php
include 'bd.php';

// Consulta para obtener los datos de las partidas con la cantidad de juegos distintos
$sql = "SELECT p.id, p.fecha, j.nombre AS ganador, SUM(d.puntaje) AS puntaje_total, 
               (SELECT COUNT(DISTINCT tipo_juego_id) FROM detalles_partida WHERE partida_id = p.id) AS total_juegos
        FROM partidas p
        LEFT JOIN jugadores j ON p.ganador_id = j.id
        INNER JOIN detalles_partida d ON p.id = d.partida_id
        GROUP BY p.id
        ORDER BY p.fecha DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Partidas Anteriores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Resultados de Partidas Anteriores</h2>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Cantidad de Juegos</th>
                    <th>Ganador</th>
                    <th>Puntaje Partida</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('d-m-Y h:i A', strtotime($row['fecha'])); ?></td>
                            <td><?php echo $row['total_juegos']; ?></td>
                            <td><?php echo $row['ganador']; ?></td>
                            <td><?php echo htmlspecialchars($row['puntaje_total']); ?></td>
                            <td>
                                <a href="./puntajes_historial.php?id=<?php echo urlencode($row['id']) ?>">
                                    <button type="button" class="btn btn-primary">Detalles</button>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No hay partidas anteriores.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <a href="index.php">
                <button type="button" class="btn btn-secondary">Volver</button>
            </a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>