<?php
// api/categoria_atualizar.php
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['sucesso' => false, 'erro' => 'Método não permitido']));
}

$id = $_POST['id'] ?? null;
$nome = trim($_POST['nome'] ?? '');

if (empty($id) || empty($nome)) {
    die(json_encode(['sucesso' => false, 'erro' => 'ID e nome da categoria são obrigatórios.']));
}

try {
    $conn = getDbConnection();
    $sql = "UPDATE categorias SET nome = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $nome, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Categoria atualizada com sucesso.']);
    } else {
        throw new Exception("Falha ao executar a atualização.");
    }

} catch (Exception $e) {
    http_response_code(500);
    // Verifica se o erro é de entrada duplicada
    if ($e->getCode() == 1062) {
        echo json_encode(['sucesso' => false, 'erro' => 'Já existe uma categoria com este nome.']);
    } else {
        error_log("Erro ao atualizar categoria: " . $e->getMessage());
        echo json_encode(['sucesso' => false, 'erro' => 'Ocorreu um erro no servidor.']);
    }
}