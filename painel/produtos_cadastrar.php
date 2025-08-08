<?php
// painel/produtos_cadastrar.php
require_once dirname(__DIR__) . '/includes/auth_check.php';

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Novo Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/painel.css">
</head>
<body>
    <?php include 'components/navbar.php'; ?>

    <div class="container mt-4">
        <div class="card bg-dark text-white border-secondary">
            <div class="card-header">
                <h3>Cadastro de Novo Produto</h3>
            </div>
            <div class="card-body">
                <form action="produtos_salvar.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Produto</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>

                    <div class="mb-3">
                        <label for="id_categoria" class="form-label">Categoria</label>
                        <select class="form-select" id="categoria-select" name="id_categoria" required>
                            <option value="">Carregando categorias...</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço (ex: 99.90)</label>
                        <input type="number" step="0.01" class="form-control" id="preco" name="preco" required>
                    </div>
                    <div class="mb-3">
                        <label for="descricao" class="form-label">Descrição</label>
                        <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Imagem do Produto</label>
                        <input type="file" class="form-control" id="imagem" name="imagem" accept="image/jpeg, image/png, image/webp">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="disponivel" name="disponivel" value="1" checked>
                        <label class="form-check-label" for="disponivel">Produto Disponível</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
                    <a href="produtos_listar.php" class="btn btn-secondary">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/popular_categorias.js"></script>
</body>
</html>