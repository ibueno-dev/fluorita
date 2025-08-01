<?php
// includes/auth_check.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario_id'])) {
    // O caminho precisa ser absoluto a partir da raiz do site
    // ou relativo ao arquivo que o está incluindo.
    // Se o check está em /includes e o login em / , o caminho é ../login.php
    header('Location: ../login.php');
    exit;
}