<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale-1.0">
    <title>Cadastre-se - Fluorita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/painel.css"> 
</head>
<body>
    <?php include 'components/public_navbar.php'; ?>

    <div class="container" style="padding-top: 120px;">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card bg-dark text-white border-secondary">
                    <div class="card-header">
                        <h3>Crie sua Conta</h3>
                    </div>
                    <div class="card-body">
                        <?php 
                            // Exibe mensagens de erro, se houver
                            if (isset($_SESSION['error_message'])) {
                                echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                                unset($_SESSION['error_message']); // Limpa a mensagem
                            }
                        ?>
                        <form action="includes/usuario_salvar.php" method="POST" id="cadastroForm">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="celular" class="form-label">Celular (somente números)</label>
                                <input type="text" class="form-control" id="celular" name="celular" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                            </div>
                             <div class="mb-3">
                                <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
                                <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
                                <div id="passwordHelp" class="form-text text-danger d-none">As senhas não conferem.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">Criar Conta</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Pequeno script para validar a confirmação de senha em tempo real
        const form = document.getElementById('cadastroForm');
        const senha = document.getElementById('senha');
        const confirmarSenha = document.getElementById('confirmar_senha');
        const passwordHelp = document.getElementById('passwordHelp');

        form.addEventListener('submit', function(event) {
            if (senha.value !== confirmarSenha.value) {
                event.preventDefault(); // Impede o envio do formulário
                passwordHelp.classList.remove('d-none'); // Mostra a mensagem de erro
            } else {
                passwordHelp.classList.add('d-none'); // Esconde a mensagem
            }
        });
    </script>
</body>
</html>