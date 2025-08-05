<?php
// A única inclusão necessária. Ele cuida da segurança e de iniciar a sessão.
require_once dirname(__DIR__) . '/includes/auth_check.php';

// Pegamos o e-mail da sessão para uma mensagem de boas-vindas
$email_do_usuario = htmlspecialchars($_SESSION['usuario_email']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../css/painel.css">
</head>
<body>
    
    <?php 
    // Inclui nosso componente de menu de navegação reutilizável
    include 'components/navbar.php'; 
    ?>

    <main class="container mt-4">
        <div class="p-5 mb-4 bg-dark rounded-3 border border-secondary">
            <div class="container-fluid py-5">
                <h1 class="display-5 fw-bold">Dashboard</h1>
                <p class="col-md-8 fs-4">Bem-vindo ao painel administrativo, <?php echo $email_do_usuario; ?>!</p>
                <p>Use o menu de navegação acima para gerenciar os recursos do sistema.</p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card bg-dark text-white border-secondary h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-box-seam" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-3">Gerenciar Produtos</h5>
                        <p class="card-text">Adicione, edite e remova os produtos do seu cardápio.</p>
                        <a href="produtos_listar.php" class="btn btn-primary">Ir para Produtos</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card bg-dark text-white border-secondary h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill" style="font-size: 3rem;"></i>
                        <h5 class="card-title mt-3">Gerenciar Usuários</h5>
                        <p class="card-text">Visualize e gerencie os usuários do sistema. (Em breve)</p>
                        <a href="#" class="btn btn-secondary disabled">Ir para Usuários</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>