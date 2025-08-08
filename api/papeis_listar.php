<?php
// api/papeis_listar.php
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

try {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT id, nome FROM papeis ORDER BY id ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    $papeis = [];
    while ($row = $result->fetch_assoc()) {
        $papeis[] = $row;
    }
    echo json_encode(['sucesso' => true, 'papeis' => $papeis]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao buscar papéis.']);
}