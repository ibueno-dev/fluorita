<?php
// Passo 1: Inicia a sessão no topo da página.
// Isso precisa ser a primeira coisa no arquivo para lermos as variáveis de sessão.
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluorita - Nutritious & Tasty</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>

    <div class="background-slider">
        <div id="bg-1" class="background-image"></div>
        <div id="bg-2" class="background-image"></div>
    </div>
    
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark navbar-glass fixed-top">
            <div class="container">
                <a class="navbar-brand hero-title-cursive fs-2" href="#">Fluorita</a>
                
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="produtos.php">Menu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Pedidos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Sobre</a>
                        </li>

                        <?php if (isset($_SESSION['usuario_id'])): ?>
                            
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i>
                                    <?php echo htmlspecialchars($_SESSION['usuario_email']); ?>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="painel/index.php">Meu Painel</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                                </ul>
                            </li>

                        <?php else: ?>

                            <li class="nav-item ms-lg-3">
                                <a class="btn btn-login" href="login.php">Logar</a>
                            </li>

                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <main class="hero-content">
        <div class="container text-center">
            <h2 class="hero-title-cursive">Fluorita</h2>
            <h1 class="hero-title-main">NUTRITIOUS & TASTY</h1>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/background_slider.js"></script>

</body>
</html>