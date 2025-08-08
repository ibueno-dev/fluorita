<?php
// api/produtos_listar.php
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

try {
    $conn = getDbConnection();

    // --- LÓGICA DE PAGINAÇÃO E BUSCA ---
    $itens_por_pagina = 10;
    $pagina_atual = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if ($pagina_atual < 1) { $pagina_atual = 1; }

    $termo_busca = isset($_GET['search']) ? trim($_GET['search']) : '';
    
    // Calcula o offset (quantos registros pular)
    $offset = ($pagina_atual - 1) * $itens_por_pagina;

    // --- CONSULTA PARA O NÚMERO TOTAL DE PRODUTOS (com filtro de busca) ---
    $sql_total = "SELECT COUNT(*) as total FROM produtos WHERE nome LIKE ?";
    $stmt_total = $conn->prepare($sql_total);
    $termo_busca_like = "%{$termo_busca}%";
    $stmt_total->bind_param("s", $termo_busca_like);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $total_produtos = $result_total->fetch_assoc()['total'];
    $total_paginas = ceil($total_produtos / $itens_por_pagina);

    // --- CONSULTA PARA OS PRODUTOS DA PÁGINA ATUAL (com filtro de busca) ---
    $sql = "SELECT p.id, p.nome, p.preco, p.descricao, p.disponivel, p.imagem, c.nome AS nome_categoria
            FROM produtos p
            LEFT JOIN categorias c ON p.id_categoria = c.id
            WHERE p.nome LIKE ?
            ORDER BY p.id ASC
            LIMIT ? OFFSET ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sii", $termo_busca_like, $itens_por_pagina, $offset);
    $stmt->execute();
    $result = $stmt->get_result();

    $produtos = [];
    $placeholder = 'imagens_publicas/placeholder.png';

    while ($row = $result->fetch_assoc()) {
        $nome_imagem = $row['imagem'];
        if (!empty($nome_imagem)) {
            $row['imagem_thumb_url'] = 'imagens_publicas/thumb/' . $nome_imagem;
            $row['imagem_large_url'] = 'imagens_publicas/large/' . $nome_imagem;
        } else {
            $row['imagem_thumb_url'] = $placeholder;
            $row['imagem_large_url'] = $placeholder;
        }
        $produtos[] = $row;
    }

    // Retorna um objeto JSON com os produtos e as informações de paginação
    echo json_encode([
        'sucesso' => true,
        'produtos' => $produtos,
        'paginacao' => [
            'pagina_atual' => $pagina_atual,
            'total_paginas' => $total_paginas,
            'total_produtos' => $total_produtos
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao buscar produtos.']);
}