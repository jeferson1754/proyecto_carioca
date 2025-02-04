<?php
include('bd.php');
// Obtener los usuarios de la base de datos
$sql = "SELECT id, nombre FROM jugadores";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleccionar Jugadores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>

    <div class="container mt-5">
        <h2>Seleccionar Jugadores para la Partida</h2>
        <form action="iniciar_partida.php" method="POST">
            <div class="form-group">
                <?php if ($result->num_rows > 0): ?>
                    <div class="list-group">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="jugadores[]" value="<?= $row['id'] ?>" id="usuario<?= $row['id'] ?>">
                                <label class="form-check-label" for="usuario<?= $row['id'] ?>">
                                    <?= $row['nombre'] ?>
                                </label>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p>No hay usuarios registrados.</p>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Iniciar Partida</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>