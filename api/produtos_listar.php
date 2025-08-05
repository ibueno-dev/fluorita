<?php
// api/produtos_listar.php
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

try {
    $conn = getDbConnection();
    $sql = "SELECT id, nome, preco, descricao, disponivel, imagem FROM produtos ORDER BY id ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $produtos = [];
    $placeholder = 'imagens_publicas/placeholder.png';

    while ($row = $result->fetch_assoc()) {
        $nome_imagem = $row['imagem'];
        if (!empty($nome_imagem)) {
            $caminho_thumb = 'imagens_publicas/thumb/' . $nome_imagem;
            $caminho_large = 'imagens_publicas/large/' . $nome_imagem;
        } else {
            $caminho_thumb = $placeholder;
            $caminho_large = $placeholder;
        }

        $row['imagem_thumb_url'] = $caminho_thumb;
        $row['imagem_large_url'] = $caminho_large;
        $produtos[] = $row;
    }

    echo json_encode(['sucesso' => true, 'produtos' => $produtos]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao buscar produtos.']);
}