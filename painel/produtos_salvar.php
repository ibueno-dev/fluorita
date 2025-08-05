<?php
// painel/produtos_salvar.php
require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';
require_once dirname(__DIR__) . '/includes/image_handler.php'; // Nosso novo módulo

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { die("Acesso negado."); }

$nome = $_POST['nome'];
$preco = $_POST['preco'];
$descricao = $_POST['descricao'];
$disponivel = isset($_POST['disponivel']) ? 1 : 0;
$nome_imagem_db = null;

// Verifica se uma imagem foi enviada
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
    // Usa nossa nova função para salvar a imagem
    $nome_imagem_db = salvarImagem($_FILES['imagem']);
    if ($nome_imagem_db === false) {
        die("Ocorreu um erro ao processar a imagem.");
    }
}

try {
    $conn = getDbConnection();
    $sql = "INSERT INTO produtos (nome, preco, descricao, disponivel, imagem) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdsis", $nome, $preco, $descricao, $disponivel, $nome_imagem_db);
    $stmt->execute();

    header("Location: index.php?status=sucesso");
    exit;
} catch (Exception $e) {
    error_log("Erro ao salvar produto: " . $e->getMessage());
    die("Ocorreu um erro ao salvar o produto.");
}