<?php
// painel/index.php

// Correção: Usando dirname(__DIR__) para subir um nível e encontrar a pasta includes.
require_once dirname(__DIR__) . '/includes/auth_check.php';

session_start();

if (!isset($_SESSION['usuario_id'])) {
    // Redireciona para o login na pasta raiz
    header('Location: ../login.php');
    exit;
}

$email_do_usuario = htmlspecialchars($_SESSION['usuario_email']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">    
    <link rel="stylesheet" href="../css/painel.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-glass">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Bem-vindo ao seu Painel!</span>
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3">
                    Logado como: <?php echo $email_do_usuario; ?>
                </span>
                <a href="../logout.php" class="btn btn-outline-danger">Sair</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <a href="produtos_cadastrar.php" class="btn btn-success">Cadastrar Novo Produto</a>
    </div>

</body>
</html>
