<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Juego de Carioca</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <style>
    :root {
      --primary-color: #ff6b6b;
      --secondary-color: #4ecdc4;
      --background-gradient: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    body {
      min-height: 100vh;
      background: var(--background-gradient);
      font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
    }

    .game-container {
      padding-top: 5vh;
      padding-bottom: 5vh;
    }

    .card {
      border: none;
      transition: all 0.3s ease;
      border-radius: 1rem;
      overflow: hidden;
    }

    .card:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .card-play {
      background: linear-gradient(45deg, var(--primary-color), #ff8e8e);
    }

    .card-history {
      background: linear-gradient(45deg, var(--secondary-color), #6fe7df);
    }

    .card-content {
      padding: 2.5rem 1.5rem;
      color: white;
    }

    .icon-container {
      width: 80px;
      height: 80px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
    }

    .card-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin-bottom: 1rem;
    }

    .card-text {
      opacity: 0.9;
      font-size: 1.1rem;
    }

    .btn-action {
      background: rgba(255, 255, 255, 0.2);
      border: 2px solid white;
      color: white;
      padding: 0.75rem 2rem;
      border-radius: 2rem;
      transition: all 0.3s ease;
      margin-top: 1.5rem;
    }

    .btn-action:hover {
      background: white;
      color: var(--primary-color);
    }

    @media (max-width: 768px) {
      .game-container {
        padding-top: 3vh;
      }

      .card {
        margin-bottom: 2rem;
      }

      .card-content {
        padding: 2rem 1rem;
      }
    }
  </style>
</head>

<body>
  <div class="container game-container">
    <h1 class="text-center mb-5 text-dark">Carioca Game</h1>

    <div class="row justify-content-center g-4">
      <div class="col-12 col-md-6 col-lg-5">
        <div class="card card-play h-100">
          <div class="card-content text-center">
            <div class="icon-container">
              <i class="fas fa-gamepad fa-2x"></i>
            </div>
            <h2 class="card-title">Jugar Carioca</h2>
            <p class="card-text">Inicia una nueva partida y disfruta del juego con tus amigos</p>
            <a href="jugar.php" class="btn btn-action d-inline-block">
              Comenzar Juego
              <i class="fas fa-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 col-lg-5">
        <div class="card card-history h-100">
          <div class="card-content text-center">
            <div class="icon-container">
              <i class="fas fa-history fa-2x"></i>
            </div>
            <h2 class="card-title">Historial</h2>
            <p class="card-text">Revisa el historial de partidas y estadísticas de juego</p>
            <a href="historial.php" class="btn btn-action d-inline-block">
              Ver Historial
              <i class="fas fa-chart-line ms-2"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>