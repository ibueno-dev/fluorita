<?php
// api/usuarios_listar.php
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

try {
    $conn = getDbConnection();
    $sql = "SELECT u.id, u.nome, u.email, u.celular, p.id AS id_papel, p.nome AS nome_papel
            FROM usuarios u
            JOIN papeis p ON u.id_papel = p.id
            ORDER BY u.id ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuarios = [];
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    echo json_encode(['sucesso' => true, 'usuarios' => $usuarios]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao buscar usuários.']);
}