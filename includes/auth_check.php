<?php
// includes/auth_check.php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificação 1: O usuário está logado?
// Se não houver 'usuario_id' na sessão, ele nem está autenticado.
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

// Verificação 2 (NOVA): O usuário tem a permissão correta?
// Verificamos se o 'usuario_papel' é igual a 2 (ID de Administrador).
if ($_SESSION['usuario_papel'] != 2) {
    // Se não for um administrador, ele não tem autorização.
    // Redirecionamos para a página inicial e encerramos o script.
    header('Location: ../home.php');
    exit;
}