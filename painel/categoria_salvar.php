<?php
// painel/categoria_salvar.php

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

// 1. Verifica se o método de requisição é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Se não for, redireciona para a página de gerenciamento
    header('Location: categorias_gerenciar.php');
    exit;
}

// 2. Valida os dados recebidos
$nome_categoria = trim($_POST['nome_categoria'] ?? '');

if (empty($nome_categoria)) {
    // Se o nome estiver vazio, redireciona com uma mensagem de erro
    header('Location: categorias_gerenciar.php?status=erro&msg=vazio');
    exit;
}

// 3. Tenta inserir no banco de dados
try {
    $conn = getDbConnection();
    $sql = "INSERT INTO categorias (nome) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nome_categoria);
    
    if ($stmt->execute()) {
        // Sucesso! Redireciona com uma mensagem de sucesso.
        header('Location: categorias_gerenciar.php?status=sucesso');
    } else {
        // Se execute() retornar false, pode ser um erro genérico
        header('Location: categorias_gerenciar.php?status=erro');
    }
    exit;

} catch (Exception $e) {
    // A exceção provavelmente será por entrada duplicada (UNIQUE KEY)
    if ($e->getCode() == 1062) { // 1062 é o código de erro do MySQL/MariaDB para 'Duplicate entry'
        header('Location: categorias_gerenciar.php?status=erro&msg=duplicado');
    } else {
        // Para qualquer outro erro de banco de dados
        error_log("Erro ao salvar categoria: " . $e->getMessage());
        header('Location: categorias_gerenciar.php?status=erro&msg=generico');
    }
    exit;
}