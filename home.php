<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluorita - Lanches & Doces</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/home.css">
</head>
<body>

    <div class="background-slider">
        <div id="bg-1" class="background-image"></div>
        <div id="bg-2" class="background-image"></div>
    </div>

    <main class="hero-content">
        <div class="container text-center">
            <h2 class="hero-title-cursive">Fluorita</h2>
            <h1 class="hero-title-main">LANCHES & Doces</h1>
        </div>
    </main>
    
    <script>
        // Esta variável global informa ao JS qual é o caminho raiz do projeto
        const BASE_URL = '<?php echo rtrim(dirname($_SERVER['PHP_SELF']), '/') . '/'; ?>';
    </script>
    <script src="js/background_slider.js"></script>

</body>
</html>