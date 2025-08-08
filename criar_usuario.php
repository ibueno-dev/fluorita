<?php
/**
 * criar_usuario.php
 *
 * Este script cria um usuário padrão para testes.
 * Ele verifica se o usuário já existe antes de tentar criá-lo.
 * É seguro executar este script várias vezes.
 */

// Define as credenciais do usuário padrão
$email_padrao = 'email@email.com';
$senha_pura_padrao = '1234';

// Usa uma formatação mais legível para a saída no navegador
header('Content-Type: text/plain; charset=utf-8');

echo "--- Script de Criação de Usuário de Teste ---\n\n";

// Inclui a conexão com o banco de dados
require_once 'includes/conn.php';
$conn = getDbConnection();
echo "[OK] Conexão com o banco de dados estabelecida.\n";

try {
    // --- PASSO 1: VERIFICAR SE O USUÁRIO JÁ EXISTE ---
    $sql_check = "SELECT id FROM usuarios WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email_padrao);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Se encontrou, não faz nada
        echo "[INFO] O usuário '$email_padrao' já existe no banco de dados.\n";
        echo "\nNenhuma ação foi necessária.\n";
    } else {
        // --- PASSO 2: SE NÃO EXISTIR, CRIAR O USUÁRIO ---
        echo "[AÇÃO] Usuário '$email_padrao' não encontrado. Criando agora...\n";
        
        // Criptografa a senha com o método mais seguro do PHP
        $hash_da_senha = password_hash($senha_pura_padrao, PASSWORD_DEFAULT);
        echo "[OK] Senha '1234' criptografada com sucesso.\n";

        // Prepara o comando SQL para inserir o novo usuário
        $sql_insert = "INSERT INTO usuarios (email, senha) VALUES (?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ss", $email_padrao, $hash_da_senha);
        
        // Executa a inserção e verifica o resultado
        if ($stmt_insert->execute()) {
            echo "\n[SUCESSO] Usuário '$email_padrao' criado com sucesso!\n";
            echo "Você já pode fazer login com e-mail: $email_padrao e senha: $senha_pura_padrao\n";
        } else {
            echo "\n[ERRO] Falha ao criar o usuário.\n";
        }
        $stmt_insert->close();
    }
    $stmt_check->close();

} catch (Exception $e) {
    echo "\n[ERRO GERAL] Ocorreu uma exceção: " . $e->getMessage() . "\n";
} finally {
    $conn->close();
    echo "\n--- Script finalizado ---\n";
}
