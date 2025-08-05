<?php
// api/produto_deletar.php
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die(json_encode(['sucesso' => false, 'erro' => 'Método não permitido'])); }

$id = $_POST['id'];

try {
    $conn = getDbConnection();
    // Primeiro, pegar o nome da imagem para poder apagá-la do servidor
    $stmt = $conn->prepare("SELECT imagem FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if($row = $result->fetch_assoc()){
        if(!empty($row['imagem'])){
            // Apaga os arquivos de imagem (ajuste os caminhos se necessário)
            @unlink('../imagens_publicas/thumb/' . $row['imagem']);
            @unlink('../imagens_publicas/large/' . $row['imagem']);
        }
    }

    // Agora, deleta o registro do banco de dados
    $stmt = $conn->prepare("DELETE FROM produtos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    echo json_encode(['sucesso' => true, 'mensagem' => 'Produto deletado com sucesso.']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao deletar produto.']);
}