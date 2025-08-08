<?php
// painel/components/navbar.php

// Inicia a sessão se ainda não estiver ativa, para garantir que as variáveis de sessão estejam disponíveis.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_email = $_SESSION['usuario_email'] ?? 'Usuário';
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Painel Fluorita</a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Produtos
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li><a class="dropdown-item" href="produtos_listar.php">Listar Produtos</a></li>
                        <li><a class="dropdown-item" href="produtos_cadastrar.php">Cadastrar Novo</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categorias_gerenciar.php">Categorias</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="usuarios_gerenciar.php">Usuários</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                 <span class="navbar-text me-3">
                    Logado como: <?php echo htmlspecialchars($user_email); ?>
                </span>
                <a href="../logout.php" class="btn btn-outline-danger">Sair</a>
            </div>

        </div>
    </div>
</nav>