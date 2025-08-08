<?php
// includes/usuario_salvar.php
session_start();
require_once 'conn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../cadastrar.php');
    exit;
}

// 1. Coletar e validar dados
$nome = trim($_POST['nome']);
$celular = trim($_POST['celular']);
$email = trim($_POST['email']);
$senha = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];

if (empty($nome) || empty($celular) || empty($email) || empty($senha)) {
    $_SESSION['error_message'] = "Todos os campos são obrigatórios.";
    header('Location: ../cadastrar.php');
    exit;
}

if ($senha !== $confirmar_senha) {
    $_SESSION['error_message'] = "As senhas não conferem.";
    header('Location: ../cadastrar.php');
    exit;
}

// 2. Verificar se o e-mail já existe
try {
    $conn = getDbConnection();
    $sql_check = "SELECT id FROM usuarios WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "Este e-mail já está cadastrado.";
        header('Location: ../cadastrar.php');
        exit;
    }
    
    // 3. Criptografar a senha
    $hash_da_senha = password_hash($senha, PASSWORD_DEFAULT);

    // 4. Inserir no banco de dados com papel de usuário "Comum" (id_papel = 1)
    $sql_insert = "INSERT INTO usuarios (nome, celular, email, senha, id_papel) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $id_papel_comum = 1; // ID do papel "Comum"
    $stmt_insert->bind_param("ssssi", $nome, $celular, $email, $hash_da_senha, $id_papel_comum);
    
    if ($stmt_insert->execute()) {
        // Sucesso! Redireciona para a home com uma mensagem de sucesso
        header('Location: ../home.php?status=cadastrado');
        exit;
    } else {
        $_SESSION['error_message'] = "Ocorreu um erro ao criar a conta. Tente novamente.";
        header('Location: ../cadastrar.php');
        exit;
    }

} catch (Exception $e) {
    error_log("Erro no cadastro de usuário: " . $e->getMessage());
    $_SESSION['error_message'] = "Ocorreu um erro no servidor. Tente novamente mais tarde.";
    header('Location: ../cadastrar.php');
    exit;
}