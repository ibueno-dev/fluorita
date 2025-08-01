// js/listar_produtos.js

// Executa o script quando o conteúdo do HTML estiver completamente carregado.
document.addEventListener('DOMContentLoaded', () => {

    const listaProdutosEl = document.getElementById('lista-produtos');
    const templateEl = document.getElementById('produto-template');
    
    const modalEl = new bootstrap.Modal(document.getElementById('imagem-modal'));
    const imagemModalEl = document.getElementById('imagem-modal-grande');

    // Usa a API Fetch para buscar os dados do nosso endpoint.
    fetch('api/get_produtos.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('A resposta da rede não foi OK');
            }
            return response.json();
        })
        .then(produtos => {
            // Limpa qualquer conteúdo de exemplo que possa existir
            listaProdutosEl.innerHTML = ''; 

            // Itera sobre cada produto retornado pela API
            produtos.forEach(produto => {
                // Clona o conteúdo do template para criar um novo card
                const card = templateEl.content.cloneNode(true);

                // Preenche os dados do card com as informações do produto
                const cardTitle = card.querySelector('.card-title');
                const cardPrice = card.querySelector('.product-price');
                const cardText = card.querySelector('.card-text');
                const cardImg = card.querySelector('.card-img-top');
                const cardId = card.querySelector('.product-id');

                cardTitle.textContent = produto.nome;
                // Formata o preço para o padrão brasileiro (R$)
                cardPrice.textContent = produto.preco.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                cardText.textContent = produto.descricao;
                cardImg.src = produto.imagem_thumb;
                cardImg.alt = produto.nome;
                
                cardId.textContent = produto.id; // Armazena o ID do produto no card   
                cardId.hidden = true; // Esconde o ID do produto no card
                // Adiciona o ID como atributo data para fácil acesso
                card.querySelector('.product-card').dataset.id = produto.id;
                

                // Adiciona o evento de clique na imagem do card
                card.querySelector('.product-card').addEventListener('click', () => {
                    // Define a imagem do modal para a versão grande do produto clicado
                    imagemModalEl.src = produto.imagem_large;
                    // Abre o modal
                    modalEl.show();
                });

                
                // Adiciona o card preenchido à lista de produtos na página
                listaProdutosEl.appendChild(card);
            });
        })
        .catch(error => {
            console.error('Erro ao buscar ou processar produtos:', error);
            listaProdutosEl.innerHTML = '<p class="text-center text-danger">Não foi possível carregar os produtos. Tente novamente mais tarde.</p>';
        });
});