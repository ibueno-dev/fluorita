<?php require_once dirname(__DIR__) . '/includes/auth_check.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/painel.css">
    <link rel="stylesheet" href="../css/painel_tabela.css">
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="container-fluid mt-4">
        <h2>Gerenciamento de Usuários</h2>
        <div class="card bg-dark text-white border-secondary">
            <div class="card-header">
                <span>Lista de Usuários</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Celular</th>
                                <th>Papel</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-usuarios-corpo">
                            <tr><td colspan="6" class="text-center">Carregando usuários...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/gerenciar_usuarios.js"></script>
</body>
</html>