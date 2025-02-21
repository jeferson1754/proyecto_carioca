<?php
include 'bd.php';

// Consulta para obtener los datos de las partidas con la cantidad de juegos distintos
$sql = "SELECT p.id, p.fecha, j.nombre AS ganador, SUM(d.puntaje) AS puntaje_total, 
               (SELECT COUNT(DISTINCT tipo_juego_id) FROM detalles_partida WHERE partida_id = p.id) AS total_juegos
        FROM partidas p
        LEFT JOIN jugadores j ON p.ganador_id = j.id
        INNER JOIN detalles_partida d ON p.id = d.partida_id
        GROUP BY p.id
        ORDER BY p.fecha DESC
        LIMIT 30";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Partidas - Carioca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            padding: 2rem 0;
        }

        .history-container {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .game-card {
            background: #f8f9fa;
            border-radius: 0.8rem;
            padding: 1.5rem;
            margin-bottom: 1rem;
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .game-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-box {
            background: rgba(78, 205, 196, 0.1);
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            color: #4ecdc4;
            font-weight: 600;
        }

        .winner-badge {
            background: linear-gradient(45deg, #ffd700, #faf0be);
            border-radius: 2rem;
            padding: 0.4rem 1rem;
            font-weight: 600;
            color: #856404;
        }

        .details-btn {
            background: #4ecdc4;
            color: white;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }

        .details-btn:hover {
            background: #45b7af;
            transform: translateY(-2px);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container">


        <div class="history-container">
            <div class="text-start mt-0">
                <a href="index.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Volver al Inicio
                </a>
            </div>
            <h2 class="text-center mb-4">
                <i class="fas fa-history me-2"></i>
                Historial de Partidas
            </h2>

            <?php if ($result->num_rows > 0): ?>
                <div class="game-list">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="game-card">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="d-flex flex-column mb-2">
                                        <small class="text-muted">Fecha</small>
                                        <strong>
                                            <i class="far fa-calendar-alt me-2"></i>
                                            <?php echo date('d/m/Y', strtotime($row['fecha'])); ?>
                                        </strong>
                                        <small class="text-muted">
                                            <?php echo date('h:i A', strtotime($row['fecha'])); ?>
                                        </small>
                                    </div>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <div class="stat-box text-center">
                                        <div><i class="fas fa-dice me-1"></i></div>
                                        <?php echo $row['total_juegos']; ?> Juegos
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="winner-badge">
                                        <i class="fas fa-trophy me-2"></i>
                                        <?php echo htmlspecialchars($row['ganador']); ?>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center mb-2">
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">Puntaje Total</small>
                                        <strong><?php echo number_format($row['puntaje_total']); ?></strong>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center">
                                    <a href="./puntajes_historial.php?id=<?php echo urlencode($row['id']); ?>"
                                        class="details-btn" style="text-decoration: none;">
                                        <i class="fas fa-chart-bar me-2"></i>
                                        Detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                    <h4>No hay partidas registradas</h4>
                    <p>Las partidas aparecerán aquí cuando comiences a jugar.</p>
                </div>
            <?php endif; ?>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>