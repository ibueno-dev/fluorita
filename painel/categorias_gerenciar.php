<?php
// painel/categorias_gerenciar.php
// A única lógica PHP necessária é o nosso guardião de segurança.
require_once dirname(__DIR__) . '/includes/auth_check.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Categorias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/painel.css">
    <link rel="stylesheet" href="../css/painel_tabela.css"> </head>
<body>
    <?php include 'components/navbar.php'; ?>
    
    <main class="container mt-4">
        <h2>Gerenciar Categorias de Produtos</h2>
        
        <div id="alert-placeholder"></div> <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white border-secondary">
                    <div class="card-header">Adicionar Nova Categoria</div>
                    <div class="card-body">
                        <form id="form-add-categoria">
                            <div class="mb-3">
                                <label for="nome_categoria" class="form-label">Nome da Categoria</label>
                                <input type="text" class="form-control" id="nome_categoria" name="nome_categoria" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Salvar Categoria</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card bg-dark text-white border-secondary">
                    <div class="card-header">Categorias Existentes</div>
                    <div class="card-body">
                        <table class="table table-dark table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th class="text-end">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="tabela-categorias-corpo">
                                <tr>
                                    <td colspan="3" class="text-center">Carregando...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/gerenciar_categorias.js"></script>
</body>
</html>