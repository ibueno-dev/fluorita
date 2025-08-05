<?php
// api/produto_atualizar.php
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

// Implementação básica. Em produção, adicione mais validações.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die(json_encode(['sucesso' => false, 'erro' => 'Método não permitido'])); }

$id = $_POST['id'];
$nome = $_POST['nome'];
$preco = $_POST['preco'];
$disponivel = isset($_POST['disponivel']) ? 1 : 0;
$imagem_antiga = $_POST['imagem_antiga']; // Nome do arquivo de imagem atual

$sql_parts = [];
$params = [];
$types = "";

// Adiciona os campos de texto à query
array_push($sql_parts, "nome = ?", "preco = ?", "disponivel = ?");
array_push($params, $nome, $preco, $disponivel);
$types .= "sdi"; // s = string, d = double, i = integer

// Verifica se uma nova imagem foi enviada
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    // Lógica de upload e redimensionamento (similar à de 'produtos_salvar.php')
    // ... (Esta parte pode ser longa, você pode refatorá-la para uma função em um helper)
    // Para simplificar, vamos apenas simular o salvamento do nome
    $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
    $novo_nome_imagem = uniqid('prod_', true) . '.' . $extensao . '.webp'; // Supondo que a conversão foi feita

    // Adiciona o campo de imagem à query
    array_push($sql_parts, "imagem = ?");
    array_push($params, $novo_nome_imagem);
    $types .= "s";

    // Aqui você adicionaria a lógica para mover o upload, redimensionar,
    // converter para webp e apagar a imagem antiga ($imagem_antiga) do servidor.
}

if (empty($sql_parts)) {
    die(json_encode(['sucesso' => true, 'mensagem' => 'Nenhum dado para atualizar.']));
}

$sql = "UPDATE produtos SET " . implode(", ", $sql_parts) . " WHERE id = ?";
array_push($params, $id);
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