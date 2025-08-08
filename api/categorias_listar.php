<?php
// api/categorias_listar.php
header('Content-Type: application/json; charset=utf-8');

// Acesso restrito a usuários logados e administradores
require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

try {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT id, nome FROM categorias ORDER BY nome ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $categorias = [];
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }

    echo json_encode(['sucesso' => true, 'categorias' => $categorias]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao buscar categorias.']);
}