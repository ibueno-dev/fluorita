<?php
// painel/produtos_salvar.php


// Correção: Usando dirname(__DIR__) para subir um nível e encontrar a pasta includes.
require_once dirname(__DIR__) . '/includes/auth_check.php';
require_once dirname(__DIR__) . '/includes/conn.php';     // Conexão com o banco


// ---- INÍCIO DO BLOCO DE DIAGNÓSTICO TEMPORÁRIO ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {

        echo "<h1>Ocorreu um erro com o upload!</h1>";
        $errorCode = $_FILES['imagem']['error'] ?? 'Nenhum arquivo enviado';

        echo "<h2>Código do Erro: " . $errorCode . "</h2>";

        // Explicações dos códigos de erro comuns:
        $errorMessages = [
            UPLOAD_ERR_INI_SIZE   => 'O arquivo enviado excede o limite definido em upload_max_filesize no php.ini.',
            UPLOAD_ERR_FORM_SIZE  => 'O arquivo enviado excede o limite definido na diretiva MAX_FILE_SIZE do formulário HTML.',
            UPLOAD_ERR_PARTIAL    => 'O upload do arquivo foi feito apenas parcialmente.',
            UPLOAD_ERR_NO_FILE    => 'Nenhum arquivo foi enviado.',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta uma pasta temporária.',
            UPLOAD_ERR_CANT_WRITE => 'Falha ao escrever o arquivo no disco. (PROBLEMA DE PERMISSÃO!)',
            UPLOAD_ERR_EXTENSION  => 'Uma extensão do PHP interrompeu o upload do arquivo.',
        ];

        echo "<p><strong>Significado:</strong> " . ($errorMessages[$errorCode] ?? 'Erro desconhecido.') . "</p>";

        echo "<h3>Informações completas do upload:</h3>";
        echo "<pre>";
        var_dump($_FILES);
        echo "</pre>";

        die(); // Interrompe a execução para vermos o erro.
    }
}
// ---- FIM DO BLOCO DE DIAGNÓSTICO ----


// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acesso negado.");
}

// --- 1. Obter os dados do formulário ---
$nome = $_POST['nome'];
$preco = $_POST['preco'];
$descricao = $_POST['descricao'];
$disponivel = isset($_POST['disponivel']) ? 1 : 0;
$imagem_final_nome = null; // Começa como nulo

// --- 2. Processamento do Upload da Imagem ---
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {

    $imagem_temp = $_FILES['imagem']['tmp_name'];
    $nome_original = basename($_FILES['imagem']['name']);

    // Gera um nome de arquivo único para evitar sobreposições
    $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
    $imagem_final_nome = uniqid('prod_', true) . '.' . $extensao;

    // Caminhos para salvar as imagens (privado e públicos)
    $caminho_upload_original = '../uploads_privados/' . $imagem_final_nome;
    $caminho_thumb = '../imagens_publicas/thumb/' . pathinfo($imagem_final_nome, PATHINFO_FILENAME) . '.webp';
    $caminho_large = '../imagens_publicas/large/' . pathinfo($imagem_final_nome, PATHINFO_FILENAME) . '.webp';

    // Move o arquivo original para a pasta de uploads privados
    if (move_uploaded_file($imagem_temp, $caminho_upload_original)) {

        // --- 3. Criação das Miniaturas e Conversão para WebP com a biblioteca GD ---
        
        // Carrega a imagem original na memória
        switch ($extensao) {
            case 'jpg':
            case 'jpeg':
                $img_original = imagecreatefromjpeg($caminho_upload_original);
                break;
            case 'png':
                $img_original = imagecreatefrompng($caminho_upload_original);
                // Mantém a transparência do PNG
                imagepalettetotruecolor($img_original);
                imagealphablending($img_original, true);
                imagesavealpha($img_original, true);
                break;
            case 'webp':
                 $img_original = imagecreatefromwebp($caminho_upload_original);
                 break;
            default:
                die('Formato de imagem inválido.');
        }

        // Função para redimensionar e salvar
        function redimensionarESalvar($imagem, $largura_max, $altura_max, $caminho_destino) {
            $largura_original = imagesx($imagem);
            $altura_original = imagesy($imagem);
            $ratio = $largura_original / $altura_original;

            if ($largura_max / $altura_max > $ratio) {
                $largura_max = $altura_max * $ratio;
            } else {
                $altura_max = $largura_max / $ratio;
            }

            $img_redimensionada = imagecreatetruecolor($largura_max, $altura_max);

            // Se for PNG, preserva a transparência no redimensionamento
             if (imageistruecolor($imagem) && imagecolorstotal($imagem) == 0) {
                imagealphablending($img_redimensionada, false);
                imagesavealpha($img_redimensionada, true);
            }
            
            imagecopyresampled($img_redimensionada, $imagem, 0, 0, 0, 0, $largura_max, $altura_max, $largura_original, $altura_original);
            
            // Salva a imagem como WebP (qualidade 80)
            imagewebp($img_redimensionada, $caminho_destino, 80);
            imagedestroy($img_redimensionada);
        }

        // Cria a versão "large" (1200px de largura) e "thumb" (200px de largura)
        redimensionarESalvar($img_original, 1200, 1200, $caminho_large);
        redimensionarESalvar($img_original, 200, 200, $caminho_thumb);

        imagedestroy($img_original);

    } else {
        die("Erro ao mover o arquivo de upload.");
    }
}

// --- 4. Inserção no Banco de Dados ---
try {
    $conn = getDbConnection();
    $sql = "INSERT INTO produtos (nome, preco, descricao, disponivel, imagem) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    // O nome da imagem a ser salvo é apenas o NOME do arquivo, não o caminho completo
    $nome_imagem_db = pathinfo($caminho_large, PATHINFO_BASENAME); // ex: prod_xxxx.webp
    
    $stmt->bind_param("sdsis", $nome, $preco, $descricao, $disponivel, $nome_imagem_db);
    
    $stmt->execute();

    // Redireciona para uma página de sucesso ou de volta para a lista de produtos
    header("Location: index.php?status=sucesso");
    exit;

} catch (Exception $e) {
    // Em caso de erro, você pode redirecionar com uma mensagem de erro
    // ou registrar o erro em um log para depuração.
    error_log("Erro ao salvar produto: " . $e->getMessage());
    die("Ocorreu um erro ao salvar o produto.");
}