<?php
// includes/auth_handler.php (antigo auth.php)

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'conn.php';

// Inicializa a variável de mensagem de erro para o formulário de login.
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn = getDbConnection();
    $email = $_POST['email'] ?? '';
    $senha_digitada = $_POST['senha'] ?? '';

    // Modificamos a consulta para buscar também o id_papel do usuário.
    $sql = "SELECT id, email, senha, id_papel FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            // Verifica se a senha está correta
            if (password_verify($senha_digitada, $usuario['senha'])) {
                // Login bem-sucedido!
                session_regenerate_id(true);
                
                // Armazena informações do usuário na sessão.
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_email'] = $usuario['email'];
                $_SESSION['usuario_papel'] = $usuario['id_papel']; // Armazenamos o papel!

                // --- LÓGICA DE REDIRECIONAMENTO BASEADA NO PAPEL ---
                
                // Papel ID 2 = Administrador
                if ($usuario['id_papel'] == 2) {
                    // Se for admin, redireciona para o painel administrativo.
                    header('Location: ../painel/index.php');
                    exit;
                } else {
                    // Para todos os outros papéis (ex: "Comum"), redireciona para a home.
                    header('Location: ../home.php');
                    exit;
                }
            }
        }
        
        // Se a autenticação falhou (e-mail ou senha errados), define a mensagem de erro.
        // E redireciona de volta para o login.
        $_SESSION['login_error_message'] = "E-mail ou senha incorretos.";
        header('Location: ../login.php');
        exit;
        
    } else {
        error_log('Erro ao preparar a consulta: ' . $conn->error);
        $_SESSION['login_error_message'] = "Ocorreu um erro no sistema. Tente novamente.";
        header('Location: ../login.php');
        exit;
    }
}