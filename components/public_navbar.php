<header>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-glass fixed-top">
        <div class="container">
            <a class="navbar-brand hero-title-cursive fs-2" href="home.php">Fluorita</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link" href="home.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="produtos.php">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Pedidos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Sobre</a></li>

                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                <?php echo htmlspecialchars($_SESSION['usuario_email']); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <?php
                                // ----- NOVA VERIFICAÇÃO AQUI -----
                                // Mostra o link do painel APENAS se o papel for Administrador (ID = 2)
                                if (isset($_SESSION['usuario_papel']) && $_SESSION['usuario_papel'] == 2):
                                ?>
                                    <li><a class="dropdown-item" href="painel/index.php">Meu Painel</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item" href="logout.php">Sair</a></li>
                            </ul>
                        </li>

                    <?php else: ?>

                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-outline-light" href="cadastrar.php">Cadastrar</a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-login" href="login.php">Logar</a>
                        </li>

                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>