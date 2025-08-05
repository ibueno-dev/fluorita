<?php
// api/produto_atualizar.php
header('Content-Type: application/json; charset=utf-8');

// Inclui todos os nossos helpers
require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';
require_once dirname(__DIR__) . '/includes/image_handler.php'; // Nosso novo módulo

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die(json_encode(['sucesso' => false, 'erro' => 'Método não permitido'])); }

$id = $_POST['id'];
$nome = $_POST['nome'];
$preco = $_POST['preco'];
$disponivel = isset($_POST['disponivel']) ? 1 : 0;
$imagem_antiga = $_POST['imagem_antiga'];

$sql_parts = ["nome = ?", "preco = ?", "disponivel = ?"];
$params = [$nome, $preco, $disponivel];
$types = "sdi";

// Verifica se uma nova imagem foi enviada
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    // Apaga a imagem antiga primeiro
    excluirImagem($imagem_antiga);

    // Tenta salvar a nova imagem
    $novo_nome_imagem = salvarImagem($_FILES['imagem']);
    
    if ($novo_nome_imagem) {
        $sql_parts[] = "imagem = ?";
        $params[] = $novo_nome_imagem;
        $types .= "s";
    }
}

$sql = "UPDATE produtos SET " . implode(", ", $sql_parts) . " WHERE id = ?";
$params[] = $id;
$types .= "i";

try {
    $conn = getDbConnection();
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    
    echo json_encode(['sucesso' => true, 'mensagem' => 'Produto atualizado com sucesso!']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao atualizar produto.', 'detalhes' => $e->getMessage()]);
}