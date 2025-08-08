<?php
// api/usuario_atualizar_papel.php
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die(json_encode(['sucesso' => false, 'erro' => 'Método não permitido']));
}

$id_usuario = $_POST['id_usuario'] ?? null;
$id_papel = $_POST['id_papel'] ?? null;

// Regra de segurança: impede que um admin altere o seu próprio papel,
// evitando que o último admin se tranque para fora do painel.
if ($id_usuario == $_SESSION['usuario_id']) {
     die(json_encode(['sucesso' => false, 'erro' => 'Você não pode alterar seu próprio papel.']));
}

if (empty($id_usuario) || empty($id_papel)) {
    die(json_encode(['sucesso' => false, 'erro' => 'Dados incompletos.']));
}

try {
    $conn = getDbConnection();
    $sql = "UPDATE usuarios SET id_papel = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_papel, $id_usuario);
    $stmt->execute();
    
    echo json_encode(['sucesso' => true, 'mensagem' => 'Papel do usuário atualizado com sucesso.']);

} catch (Exception $e) {
    http_response_code(500);
    error_log("Erro ao atualizar papel: " . $e->getMessage());
    echo json_encode(['sucesso' => false, 'erro' => 'Ocorreu um erro no servidor.']);
}