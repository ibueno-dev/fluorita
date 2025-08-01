<?php
/**
 * login.php
 *
 * Este arquivo é a PÁGINA DE VISUALIZAÇÃO (View).
 * Sua única responsabilidade é exibir o HTML do formulário de login.
 *
 * Ele primeiro inclui o arquivo de lógica que processa os dados do formulário
 * e define a variável $mensagem, se necessário.
 */
require_once __DIR__ . '/includes/auth_handler.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela de Login</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="css/login.css">
</head>
<body>

    <div class="container-fluid d-flex justify-content-center align-items-center vh-100">
        <div class="login-card">
            
            <?php if (!empty($mensagem)) echo $mensagem; ?>

            <form method="POST" action="login.php">
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" class="form-control" name="email" placeholder="E-mail" required>
                </div>

                <div class="input-group mb-4">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" name="senha" placeholder="Senha" required>
                </div>
                
                <button type="submit" class="btn btn-login w-100 mb-3">Entrar</button>

                <div class="d-flex justify-content-between align-items-center extra-options">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="" id="lembrarMe">
                        <label class="form-check-label" for="lembrarMe">Lembrar-me</label>
                    </div>
                    <a href="#" class="forgot-password">Esqueceu a senha?</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
