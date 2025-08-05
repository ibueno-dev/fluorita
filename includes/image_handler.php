<?php
// includes/image_handler.php

/**
 * Processa um arquivo de imagem enviado, move o original, cria versões
 * otimizadas (large, thumb) em formato WebP e as salva.
 *
 * @param array $arquivo_upload A entrada do array $_FILES (ex: $_FILES['imagem']).
 * @return string|false O novo nome de arquivo único (ex: 'prod_xxxxx.webp') em caso de sucesso, ou false em caso de falha.
 */
function salvarImagem(array $arquivo_upload)
{
    if ($arquivo_upload['error'] !== UPLOAD_ERR_OK) {
        // Se houve algum erro no upload inicial, retorna falha.
        error_log("Erro de upload de arquivo: Código " . $arquivo_upload['error']);
        return false;
    }

    $nome_original = basename($arquivo_upload['name']);
    $extensao = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
    
    // Gera um nome de arquivo único e já define a extensão final como webp
    $novo_nome_base = uniqid('prod_', true);
    $novo_nome_webp = $novo_nome_base . '.webp';
    $nome_original_unico = $novo_nome_base . '.' . $extensao;

    // Caminhos absolutos para as pastas (usando a raiz do projeto)
    $raiz_projeto = dirname(__DIR__);
    $caminho_upload_original = $raiz_projeto . '/uploads_privados/' . $nome_original_unico;
    $caminho_thumb = $raiz_projeto . '/imagens_publicas/thumb/' . $novo_nome_webp;
    $caminho_large = $raiz_projeto . '/imagens_publicas/large/' . $novo_nome_webp;

    // Move o arquivo original para a pasta de uploads privados
    if (!move_uploaded_file($arquivo_upload['tmp_name'], $caminho_upload_original)) {
        error_log("Falha ao mover o arquivo de upload para: " . $caminho_upload_original);
        return false;
    }

    // Carrega a imagem original na memória com a biblioteca GD
    switch ($extensao) {
        case 'jpg':
        case 'jpeg':
            $img_original = imagecreatefromjpeg($caminho_upload_original);
            break;
        case 'png':
            $img_original = imagecreatefrompng($caminho_upload_original);
            imagepalettetotruecolor($img_original);
            imagealphablending($img_original, true);
            imagesavealpha($img_original, true);
            break;
        case 'webp':
            $img_original = imagecreatefromwebp($caminho_upload_original);
            break;
        default:
            error_log("Formato de imagem inválido: " . $extensao);
            unlink($caminho_upload_original); // Apaga o original se o formato não for suportado
            return false;
    }

    // Função interna para redimensionar e salvar
    $redimensionarESalvar = function($imagem, $largura_max, $caminho_destino) {
        $largura_original = imagesx($imagem);
        $altura_original = imagesy($imagem);
        $ratio = $largura_original / $altura_original;

        $altura_max = $largura_max / $ratio;

        $img_redimensionada = imagecreatetruecolor($largura_max, $altura_max);

        if ($imagem && imagecolortotal($imagem) == 0) { // Preserva transparência do PNG
            imagealphablending($img_redimensionada, false);
            imagesavealpha($img_redimensionada, true);
        }
        
        imagecopyresampled($img_redimensionada, $imagem, 0, 0, 0, 0, $largura_max, $altura_max, $largura_original, $altura_original);
        imagewebp($img_redimensionada, $caminho_destino, 80);
        imagedestroy($img_redimensionada);
    };

    // Cria as versões "large" (1200px de largura) e "thumb" (200px de largura)
    $redimensionarESalvar($img_original, 1200, $caminho_large);
    $redimensionarESalvar($img_original, 200, $caminho_thumb);

    imagedestroy($img_original);
    
    // Retorna o nome do arquivo gerado para ser salvo no banco de dados
    return $novo_nome_webp;
}

/**
 * Exclui todas as versões de uma imagem do servidor.
 *
 * @param string|null $nome_arquivo O nome do arquivo a ser excluído (ex: 'prod_xxxxx.webp').
 * @return void
 */
function excluirImagem($nome_arquivo)
{
    if (empty($nome_arquivo)) {
        return;
    }

    $raiz_projeto = dirname(__DIR__);
    
    // Para apagar o original, precisamos descobrir a extensão original. 
    // Por simplicidade aqui, vamos apagar apenas as versões webp.
    // Em um sistema mais complexo, você guardaria a extensão original no DB.
    $caminho_thumb = $raiz_projeto . '/imagens_publicas/thumb/' . $nome_arquivo;
    $caminho_large = $raiz_projeto . '/imagens_publicas/large/' . $nome_arquivo;

    if (file_exists($caminho_thumb)) {
        @unlink($caminho_thumb);
    }
    if (file_exists($caminho_large)) {
        @unlink($caminho_large);
    }
}