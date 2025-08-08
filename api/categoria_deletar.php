<?php
// api/categoria_deletar.php
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['sucesso' => false, 'erro' => 'Método não permitido']));
}

$id = $_POST['id'] ?? null;

if (empty($id)) {
    die(json_encode(['sucesso' => false, 'erro' => 'ID da categoria é obrigatório.']));
}

try {
    $conn = getDbConnection();
    
    // 1. VERIFICAR SE A CATEGORIA ESTÁ EM USO
    $stmt_check = $conn->prepare("SELECT COUNT(*) as total FROM produtos WHERE id_categoria = ?");
    $stmt_check->bind_param("i", $id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $row = $result->fetch_assoc();

    if ($row['total'] > 0) {
        // Se a contagem for maior que 0, a categoria está em uso e não pode ser deletada.
        http_response_code(400); // Bad Request
        die(json_encode(['sucesso' => false, 'erro' => 'Não é possível excluir: esta categoria está sendo usada por ' . $row['total'] . ' produto(s).']));
    }

    // 2. SE NÃO ESTIVER EM USO, DELETAR
    $stmt_delete = $conn->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt_delete->bind_param("i", $id);

    if ($stmt_delete->execute()) {
        echo json_encode(['sucesso' => true, 'mensagem' => 'Categoria deletada com sucesso.']);
    } else {
        throw new Exception("Falha ao executar a exclusão.");
    }

} catch (Exception $e) {
    http_response_code(500);
    error_log("Erro ao deletar categoria: " . $e->getMessage());
    echo json_encode(['sucesso' => false, 'erro' => 'Ocorreu um erro no servidor.']);
}