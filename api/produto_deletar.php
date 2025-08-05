<?php
// api/produto_deletar.php
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';
require_once dirname(__DIR__) . '/includes/image_handler.php'; // Nosso novo módulo

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die(json_encode(['sucesso' => false, 'erro' => 'Método não permitido'])); }

$id = $_POST['id'];

try {
    $conn = getDbConnection();
    // Pega o nome da imagem antes de apagar o registro
    $stmt = $conn->prepare("SELECT imagem FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()){
        // Usa nossa nova função para apagar os arquivos de imagem
        excluirImagem($row['imagem']);
    }

    // Deleta o registro do banco
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(['sucesso' => true, 'mensagem' => 'Produto deletado com sucesso.']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao deletar produto.']);
}