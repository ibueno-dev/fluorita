// js/gerenciar_produtos.js
document.addEventListener('DOMContentLoaded', () => {
    const tabelaCorpo = document.getElementById('tabela-corpo');
    const searchInput = document.getElementById('searchInput');
    const paginacaoControles = document.getElementById('paginacao-controles');

    /**
     * Busca produtos da API com base na página e termo de busca e renderiza tudo.
     * @param {number} page - O número da página a ser buscada.
     * @param {string} search - O termo de busca.
     */
    const carregarProdutos = async (page = 1, search = '') => {
        tabelaCorpo.innerHTML = `<tr><td colspan="7" class="text-center">Carregando...</td></tr>`;
        try {
            const response = await fetch(`../api/produtos_listar.php?page=${page}&search=${search}`);
            const data = await response.json();

            if (data.sucesso) {
                renderizarTabela(data.produtos);
                renderizarPaginacao(data.paginacao);
            } else {
                tabelaCorpo.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Erro: ${data.erro}</td></tr>`;
            }
        } catch (error) {
            tabelaCorpo.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Erro de conexão.</td></tr>`;
        }
    };

    /**
     * Preenche a tabela com os dados dos produtos.
     * @param {Array} produtos 
     */
    const renderizarTabela = (produtos) => {
        tabelaCorpo.innerHTML = '';
        if (produtos.length === 0) {
            tabelaCorpo.innerHTML = `<tr><td colspan="7" class="text-center">Nenhum produto encontrado.</td></tr>`;
            return;
        }
        produtos.forEach(produto => {
            const tr = document.createElement('tr');
            tr.dataset.id = produto.id;
            tr.classList.add('view-mode');
            tr.innerHTML = `
                <td>${produto.id}</td>
                <td><img src="../${produto.imagem_thumb_url}" alt="${produto.nome}" class="thumb-preview" title="Clique para alterar"></td>
                <td><span class="data-span">${produto.nome}</span><input type="text" class="form-control" value="${produto.nome}"></td>
                <td><span class="data-span data-categoria">${produto.nome_categoria || 'N/A'}</span></td>
                <td><span class="data-span">R$ ${parseFloat(produto.preco).toFixed(2)}</span><input type="number" step="0.01" class="form-control" value="${produto.preco}"></td>
                <td><span class="data-span">${produto.disponivel == 1 ? 'Sim' : 'Não'}</span><input type="checkbox" class="form-check-input" ${produto.disponivel == 1 ? 'checked' : ''}></td>
                <td class="text-end">
                    <button class="btn btn-primary btn-sm btn-editar">Editar</button>
                    <button class="btn btn-danger btn-sm btn-deletar">Deletar</button>
                    <button class="btn btn-success btn-sm btn-salvar" style="display: none;">Salvar</button>
                    <button class="btn btn-secondary btn-sm btn-cancelar" style="display: none;">Cancelar</button>
                </td>`;
            tabelaCorpo.appendChild(tr);
        });
    };

    /**
     * Cria os botões de navegação da paginação.
     * @param {object} paginacao - O objeto com dados da paginação da API.
     */
    const renderizarPaginacao = (paginacao) => {
        paginacaoControles.innerHTML = '';
        if (paginacao.total_paginas <= 1) return;

        let ul = document.createElement('ul');
        ul.className = 'pagination justify-content-center';

        // Botão "Anterior"
        let liPrev = `<li class="page-item ${paginacao.pagina_atual === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${paginacao.pagina_atual - 1}">Anterior</a>
                      </li>`;
        ul.innerHTML += liPrev;

        // Botões das páginas
        for (let i = 1; i <= paginacao.total_paginas; i++) {
            let li = `<li class="page-item ${i === paginacao.pagina_atual ? 'active' : ''}">
                        <a class="page-link" href="#" data-page="${i}">${i}</a>
                      </li>`;
            ul.innerHTML += li;
        }

        // Botão "Próximo"
        let liNext = `<li class="page-item ${paginacao.pagina_atual === paginacao.total_paginas ? 'disabled' : ''}">
                        <a class="page-link" href="#" data-page="${paginacao.pagina_atual + 1}">Próximo</a>
                      </li>`;
        ul.innerHTML += liNext;

        paginacaoControles.appendChild(ul);
    };
    
    // --- EVENT LISTENERS ---

    // Busca quando o usuário digita
    let searchTimeout;
    searchInput.addEventListener('keyup', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            carregarProdutos(1, searchInput.value);
        }, 300); // Espera 300ms após o usuário parar de digitar
    });

    // Cliques nos botões de paginação
    paginacaoControles.addEventListener('click', (e) => {
        e.preventDefault();
        if (e.target.tagName === 'A' && e.target.dataset.page) {
            const page = parseInt(e.target.dataset.page, 10);
            if (!isNaN(page)) {
                carregarProdutos(page, searchInput.value);
            }
        }
    });

    // Delegação de eventos para os botões e imagens
    tabelaCorpo.addEventListener('click', async (e) => {
        const tr = e.target.closest('tr');
        if (!tr) return;
        const id = tr.dataset.id;

        // Botão Editar
        if (e.target.classList.contains('btn-editar')) {
            tr.classList.replace('view-mode', 'edit-mode');
        }

        // Botão Cancelar
        if (e.target.classList.contains('btn-cancelar')) {
            tr.classList.replace('edit-mode', 'view-mode');
            // Resetar inputs para valores originais (opcional, pode recarregar a linha)
        }

        // Botão Salvar
        if (e.target.classList.contains('btn-salvar')) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('nome', tr.querySelector('.inline-edit[type="text"]').value);
            formData.append('preco', tr.querySelector('.inline-edit[type="number"]').value);
            formData.append('disponivel', tr.querySelector('.form-check-input').checked ? 1 : 0);
            formData.append('imagem_antiga', tr.querySelector('.thumb-preview').src.split('/').pop());
            
            const fileInput = tr.querySelector('input[type="file"]');
            if (fileInput.files.length > 0) {
                formData.append('imagem', fileInput.files[0]);
            }

            try {
                const response = await fetch('../api/produto_atualizar.php', {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                if (result.sucesso) {
                    alert('Produto salvo com sucesso!');
                    carregarProdutos(); // Recarrega a tabela
                } else {
                    alert('Erro ao salvar: ' + result.erro);
                }
            } catch (error) {
                alert('Erro de conexão ao salvar.');
            }
        }

        // Botão Deletar
        if (e.target.classList.contains('btn-deletar')) {
            if (confirm(`Tem certeza que deseja deletar o produto ID ${id}?`)) {
                const formData = new FormData();
                formData.append('id', id);
                try {
                    const response = await fetch('../api/produto_deletar.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();
                     if (result.sucesso) {
                        alert('Produto deletado!');
                        tr.remove(); // Remove a linha da tabela
                    } else {
                        alert('Erro ao deletar: ' + result.erro);
                    }
                } catch(error) {
                    alert('Erro de conexão ao deletar.');
                }
            }
        }
        
        // Clicar na imagem para abrir o seletor de arquivo
        if(e.target.classList.contains('thumb-preview')) {
            // Só funciona em modo de edição
            if(tr.classList.contains('edit-mode')) {
                const fileInput = tr.querySelector('input[type="file"]');
                fileInput.click(); // Aciona o clique no input de arquivo escondido

                fileInput.onchange = () => {
                    if (fileInput.files.length > 0) {
                        // Exibe preview da nova imagem
                        const reader = new FileReader();
                        reader.onload = (event) => {
                            e.target.src = event.target.result;
                        };
                        reader.readAsDataURL(fileInput.files[0]);
                    }
                };
            }
        }
    });

    // Filtro de busca
    searchInput.addEventListener('keyup', () => {
        const termo = searchInput.value.toLowerCase();
        const produtosFiltrados = todosProdutos.filter(p => p.nome.toLowerCase().includes(termo));
        renderizarTabela(produtosFiltrados);
    });

    // Carregamento inicial
    carregarProdutos();
});