<?php
/**
 * Arquivo de configuração e conexão com o banco de dados.
 */

// --- Credenciais do Banco de Dados ---
// Usar constantes torna o código mais seguro e fácil de manter.
define('DB_HOST', 'localhost'); // Geralmente 'localhost' ou '127.0.0.1'
define('DB_NAME', 'fluorita');   // O nome da sua base de dados
define('DB_USER', 'fluorita');   // O seu usuário do banco
define('DB_PASS', 'A1s2d3@@');  // A senha do usuário

/**
 * Obtém a conexão com o banco de dados (padrão Singleton).
 * * Esta função garante que apenas uma conexão com o banco de dados seja
 * criada por requisição, economizando recursos.
 *
 * @return mysqli|null O objeto de conexão mysqli em caso de sucesso, ou null em caso de falha.
 */
function getDbConnection() {
    // A variável estática $conn persiste durante a execução do script.
    // Ela só será nula na primeira vez que a função for chamada.
    static $conn = null;

    // Se a conexão ainda não foi estabelecida, crie-a.
    if ($conn === null) {
        try {
            // Desativa a exibição de erros do mysqli para tratar manualmente.
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            // Cria uma nova instância de conexão mysqli
            $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

            // Define o conjunto de caracteres para utf8mb4 para suportar emoticons e caracteres especiais.
            $conn->set_charset("utf8mb4");

        } catch (mysqli_sql_exception $e) {
            // Em caso de erro na conexão, interrompe o script com uma mensagem segura.
            // Em um ambiente de produção, você poderia registrar esse erro em um log.
            error_log("Erro de conexão com o banco de dados: " . $e->getMessage());
            // die("Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.");
            // LINHA DE DIAGNÓSTICO TEMPORÁRIA:
            die("ERRO REAL DA CONEXÃO MYSQL: " . $e->getMessage());;
        }
    }

    // Retorna a conexão existente ou a recém-criada.
    return $conn;
}
