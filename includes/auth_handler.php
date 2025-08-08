<?php
/**
 * includes/auth_handler.php
 *
 * Este arquivo contém toda a lógica para processar a tentativa de login.
 * Ele não produz nenhuma saída HTML.
 */

// Inicia a sessão. Essencial para manter o usuário logado entre as páginas.
// Deve ser uma das primeiras coisas a serem executadas.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Correção: Usando __DIR__ para garantir que o caminho seja sempre correto.
require_once __DIR__ . '/conn.php';

// Inicializa a variável de mensagem que será usada no arquivo de visualização (login.php).
$mensagem = '';

// Verifica se o formulário foi enviado via método POST.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém a conexão com o banco de dados.
    $conn = getDbConnection();

    // Obtém os dados do formulário de forma segura.
    $email = $_POST['email'] ?? '';
    $senha_digitada = $_POST['senha'] ?? '';

    // Prepara a consulta para buscar o usuário pelo e-mail (previne SQL Injection).
    $sql = "SELECT id, email, senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Associa o e-mail ao parâmetro da consulta.
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Se um usuário com o e-mail foi encontrado...
        if ($result->num_rows === 1) {
            $usuario = $result->fetch_assoc();

            // Verifica se a senha digitada corresponde ao hash salvo no banco.
            if (password_verify($senha_digitada, $usuario['senha'])) {
                // Login bem-sucedido!
                // Regenera o ID da sessão para maior segurança.
                session_regenerate_id(true);
                
                // Armazena informações do usuário na sessão.
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_email'] = $usuario['email'];
                
                // Redireciona para a página principal do sistema (ex: painel.php).
                header('Location: painel/index.php');
                exit; // Encerra o script para garantir que o redirecionamento ocorra.
            }
        }
        
        // Se a autenticação falhou (e-mail ou senha errados), define a mensagem de erro.
        $mensagem = '<div class="alert alert-danger" role="alert">E-mail ou senha incorretos.</div>';

        $stmt->close();
    } else {
        // Falha ao preparar a consulta (erro de SQL, etc.).
        error_log('Erro ao preparar a consulta: ' . $conn->error);
        $mensagem = '<div class="alert alert-danger" role="alert">Ocorreu um erro no sistema. Tente novamente.</div>';
    }

    $conn->close();
}
