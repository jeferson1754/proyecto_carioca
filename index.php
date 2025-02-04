<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Juego de Carioca</title>
    <!-- Vincula Bootstrap y FontAwesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

  <div class="container d-flex justify-content-center align-items-center">
    <div class="row w-100 g-4">

      <!-- Opción Jugar Carioca -->
      <div class="col-md-6 col-lg-4">
        <div class="card text-center bg-pink p-4 shadow rounded">
          <a href="jugar.php" class="text-decoration-none text-dark">
            <i class="fas fa-gamepad fa-3x mb-3"></i>
            <h5 class="card-title">Jugar Carioca</h5>
            <p class="card-text">Iniciar una nueva partida</p>
          </a>
        </div>
      </div>

      <!-- Opción Historial de Partidas -->
      <div class="col-md-6 col-lg-4">
        <div class="card text-center bg-success p-4 shadow rounded">
          <a href="historial.php" class="text-decoration-none text-dark">
            <i class="fas fa-history fa-3x mb-3"></i>
            <h5 class="card-title">Ver partidas anteriores</h5>
            <p class="card-text">Revisar resultados anteriores</p>
          </a>
        </div>
      </div>

      <!-- Opción Ajustes -->
      <div class="col-md-6 col-lg-4">
        <div class="card text-center bg-warning p-4 shadow rounded">
          <a href="ajustes.php" class="text-decoration-none text-dark">
            <i class="fas fa-cogs fa-3x mb-3"></i>
            <h5 class="card-title">Ajustes</h5>
            <p class="card-text">Configura tu experiencia</p>
          </a>
        </div>
      </div>

    </div>
  </div>

  <!-- Vincula los scripts de Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
