document.addEventListener('DOMContentLoaded', () => {

    // --- CONFIGURAÇÃO ---
    // Adicione os nomes das suas imagens de fundo aqui.
    // Elas devem estar na pasta 'imagens_publicas/backgrounds/'.
    const imagens = [
        'bg1.webp',
        'bg2.webp',
        'bg3.webp',
        'bg4.webp',
        'bg5.webp',
        'bg6.webp',
        'bg7.webp'
    ];

    // Tempo em milissegundos para cada imagem ficar na tela. (5000ms = 5 segundos)
    const tempoDeTroca = 5000;
    // -------------------


    const bg1 = document.getElementById('bg-1');
    const bg2 = document.getElementById('bg-2');
    // Constrói o caminho completo para as imagens
    // Usa a variável global BASE_URL que definimos no PHP
    const caminhosCompletos = imagens.map(img => `${BASE_URL}imagens_publicas/backgrounds/${img}`);

    
    let imagemAtualIndex = 0;
    let qualDivEstaAtiva = 1; // 1 ou 2

    // Pré-carrega as imagens para evitar piscar na primeira vez
    caminhosCompletos.forEach(caminho => {
        new Image().src = caminho;
    });

    // Define a primeira imagem
    bg1.style.backgroundImage = `url('${caminhosCompletos[0]}')`;
    bg2.style.backgroundImage = `url('${caminhosCompletos[1]}')`;
    bg2.style.opacity = '0'; // A segunda div começa invisível

    // Função para trocar as imagens
    function trocarImagem() {
        imagemAtualIndex = (imagemAtualIndex + 1) % caminhosCompletos.length;
        
        const proximaImagemIndex = (imagemAtualIndex + 1) % caminhosCompletos.length;

        if (qualDivEstaAtiva === 1) {
            // A div 1 está visível, então a div 2 vai aparecer.
            // A div 2 já tem a próxima imagem carregada.
            bg2.style.opacity = '1'; // Fade in
            bg1.style.opacity = '0'; // Fade out
            // Prepara a div 1 com a imagem seguinte para a próxima transição
            setTimeout(() => {
                bg1.style.backgroundImage = `url('${caminhosCompletos[proximaImagemIndex]}')`;
            }, 1500); // 1.5s (duração da transição)
            qualDivEstaAtiva = 2;
        } else {
            // A div 2 está visível, então a div 1 vai aparecer.
            bg1.style.opacity = '1';
            bg2.style.opacity = '0';
            setTimeout(() => {
                bg2.style.backgroundImage = `url('${caminhosCompletos[proximaImagemIndex]}')`;
            }, 1500);
            qualDivEstaAtiva = 1;
        }
    }

    // Inicia o intervalo para trocar as imagens automaticamente
    setInterval(trocarImagem, tempoDeTroca);
});