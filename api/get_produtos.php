<?php
// api/get_produtos.php

header('Content-Type: application/json; charset=utf-8');

// Correção: Usando o caminho absoluto a partir do diretório pai.
require_once dirname(__DIR__) . '/includes/conn.php';

try {
    $conn = getDbConnection();

    // Seleciona apenas os produtos que estão marcados como disponíveis.
    $sql = "SELECT id, nome, preco, descricao, imagem FROM produtos WHERE disponivel = 1 ORDER BY nome ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    $produtos = [];

    // Define o caminho para a imagem padrão
    $placeholder = 'imagens_publicas/placeholder.png';

    while ($row = $result->fetch_assoc()) {
        $nome_imagem = $row['imagem'];
        // Lógica de verificação: Se o nome da imagem não estiver vazio, monte os caminhos.
        // Senão, use o caminho do placeholder para tudo.
        if (!empty($nome_imagem)) {
            $caminho_thumb = 'imagens_publicas/thumb/' . $nome_imagem;
            $caminho_large = 'imagens_publicas/large/' . $nome_imagem;
        } else {
            $caminho_thumb = $placeholder;
            $caminho_large = $placeholder;
        }
        // Para cada produto, montamos um array com os caminhos completos das imagens.
        // Isso simplifica o trabalho do JavaScript no frontend.
        $produtos[] = [
            'id' => (int)$row['id'],
            'nome' => $row['nome'],
            'preco' => (float)$row['preco'],
            'descricao' => $row['descricao'],
            'imagem_thumb' => $caminho_thumb,
            'imagem_large' => $caminho_large
        ];
    }

    // Codifica o array de produtos em formato JSON e o envia como resposta.
    echo json_encode($produtos);

} catch (Exception $e) {
    // Em caso de erro, retorna um JSON com a mensagem de erro.
    http_response_code(500); // Define o código de status HTTP para Erro Interno do Servidor.
    echo json_encode(['erro' => 'Ocorreu um erro ao buscar os produtos.', 'detalhes' => $e->getMessage()]);
}