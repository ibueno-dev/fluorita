<?php require_once dirname(__DIR__) . '/includes/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Produtos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/painel.css">
    <link rel="stylesheet" href="../css/painel_tabela.css">
</head>
<body>
    <?php include 'components/navbar.php'; // Vamos criar um navbar reutilizável ?>
    
    <div class="container-fluid mt-4">
        <h2>Gerenciamento de Produtos</h2>
        <div class="card bg-dark text-white border-secondary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Lista de Produtos</span>
                <input type="text" id="searchInput" class="form-control form-control-sm" style="max-width: 300px;" placeholder="Buscar por nome...">
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Imagem</th>
                                <th>Nome</th>
                                <th>Categoria</th> <th>Preço</th>
                                <th>Ativo</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-corpo">
                            <tr><td colspan="6" class="text-center">Carregando produtos...</td></tr>
                        </tbody>
                    </table>
                </div>
                <nav id="paginacao-controles" aria-label="Navegação das páginas de produtos"></nav>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/gerenciar_produtos.js"></script>
</body>
</html>