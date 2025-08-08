<?php
// painel/categorias_gerenciar.php

require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';

// Busca todas as categorias existentes para listar na tabela
$categorias = [];
try {
    $conn = getDbConnection();
    $stmt = $conn->prepare("SELECT id, nome FROM categorias ORDER BY nome ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
} catch (Exception $e) {
    // Em caso de erro, podemos registrar no log
    error_log("Erro ao buscar categorias: " . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Categorias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/painel.css">
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    
    <main class="container mt-4">
        <h2>Gerenciar Categorias de Produtos</h2>

        <?php
            // Exibe mensagens de sucesso ou erro vindas do script de salvamento
            if (isset($_GET['status'])) {
                $status = $_GET['status'];
                $msg = $_GET['msg'] ?? '';
                if ($status === 'sucesso') {
                    echo '<div class="alert alert-success">Categoria salva com sucesso!</div>';
                } else if ($status === 'erro') {
                    $mensagem_erro = 'Ocorreu um erro.';
                    if ($msg === 'duplicado') {
                        $mensagem_erro = 'Erro: Esta categoria já existe.';
                    } elseif ($msg === 'vazio') {
                         $mensagem_erro = 'Erro: O nome da categoria não pode ser vazio.';
                    }
                    echo '<div class="alert alert-danger">' . $mensagem_erro . '</div>';
                }
            }
        ?>

        <div class="row">
            <div class="col-md-4">
                <div class="card bg-dark text-white border-secondary">
                    <div class="card-header">Adicionar Nova Categoria</div>
                    <div class="card-body">
                        <form action="categoria_salvar.php" method="POST">
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
                            <tbody>
                                <?php if (empty($categorias)): ?>
                                    <tr><td colspan="3" class="text-center">Nenhuma categoria cadastrada.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($categorias as $categoria): ?>
                                    <tr>
                                        <td><?php echo $categoria['id']; ?></td>
                                        <td><?php echo htmlspecialchars($categoria['nome']); ?></td>
                                        <td class="text-end">
                                            <a href="#" class="btn btn-sm btn-warning disabled">Editar</a>
                                            <a href="#" class="btn btn-sm btn-danger disabled">Excluir</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>