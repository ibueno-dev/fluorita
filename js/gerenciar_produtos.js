document.addEventListener('DOMContentLoaded', () => {
    // --- ELEMENTOS DO DOM ---
    const tabelaCorpo = document.getElementById('tabela-corpo');
    const searchInput = document.getElementById('searchInput');
    const paginacaoControles = document.getElementById('paginacao-controles');

    // --- CACHE DE DADOS ---
    let todasCategorias = []; // Armazena a lista de categorias para não buscar toda hora

    /**
     * Função inicial que busca dados essenciais (como categorias) e depois carrega os produtos.
     */
    const carregarDadosIniciais = async () => {
        try {
            const categoriasResponse = await fetch('../api/categorias_listar.php');
            const categoriasData = await categoriasResponse.json();
            if (categoriasData.sucesso) {
                todasCategorias = categoriasData.categorias;
            } else {
                throw new Error('Falha ao carregar categorias');
            }
            // Após carregar as categorias, carrega a primeira página de produtos
            await carregarProdutos(1, '');
        } catch (error) {
            tabelaCorpo.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Erro crítico ao carregar dados iniciais. Verifique a API de categorias.</td></tr>`;
        }
    };

    /**
     * Busca uma página de produtos da API e renderiza a tabela e a paginação.
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
            tabelaCorpo.innerHTML = `<tr><td colspan="7" class="text-center text-danger">Erro de conexão com a API de produtos.</td></tr>`;
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

        const optionsHTML = todasCategorias.map(cat => `<option value="${cat.id}">${cat.nome}</option>`).join('');

        produtos.forEach(produto => {
            const tr = document.createElement('tr');
            tr.dataset.id = produto.id;
            tr.classList.add('view-mode');
            tr.innerHTML = `
                <td>${produto.id}</td>
                <td>
                    <img src="../${produto.imagem_thumb_url}" alt="${produto.nome}" class="thumb-preview" title="Clique para alterar a imagem">
                    <input type="file" class="form-control form-control-sm" style="display: none;" accept="image/*">
                </td>
                <td>
                    <span class="data-span data-nome">${produto.nome}</span>
                    <input type="text" class="form-control form-control-sm" value="${produto.nome}">
                </td>
                <td>
                    <span class="data-span data-categoria">${produto.nome_categoria || 'N/A'}</span>
                    <select class="form-select form-select-sm">${optionsHTML}</select>
                </td>
                <td>
                    <span class="data-span data-preco">R$ ${parseFloat(produto.preco).toFixed(2)}</span>
                    <input type="number" step="0.01" class="form-control form-control-sm" value="${produto.preco}">
                </td>
                <td>
                    <span class="data-span data-ativo">${produto.disponivel == 1 ? 'Sim' : 'Não'}</span>
                    <input type="checkbox" class="form-check-input" ${produto.disponivel == 1 ? 'checked' : ''}>
                </td>
                <td class="text-end">
                    <button class="btn btn-primary btn-sm btn-editar">Editar</button>
                    <button class="btn btn-danger btn-sm btn-deletar">Deletar</button>
                    <button class="btn btn-success btn-sm btn-salvar" style="display: none;">Salvar</button>
                    <button class="btn btn-secondary btn-sm btn-cancelar" style="display: none;">Cancelar</button>
                </td>`;
            
            tr.querySelector('select').value = produto.id_categoria;
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

        const criarLink = (texto, pagina, desabilitado = false, ativo = false) => {
            const liClass = `page-item ${desabilitado ? 'disabled' : ''} ${ativo ? 'active' : ''}`;
            return `<li class="${liClass}">
                        <a class="page-link" href="#" data-page="${pagina}">${texto}</a>
                      </li>`;
        };

        ul.innerHTML += criarLink('Anterior', paginacao.pagina_atual - 1, paginacao.pagina_atual === 1);
        for (let i = 1; i <= paginacao.total_paginas; i++) {
            ul.innerHTML += criarLink(i, i, false, i === paginacao.pagina_atual);
        }
        ul.innerHTML += criarLink('Próximo', paginacao.pagina_atual + 1, paginacao.pagina_atual === paginacao.total_paginas);
        
        paginacaoControles.appendChild(ul);
    };

    // --- EVENT LISTENERS ---

    // Busca quando o usuário digita
    let searchTimeout;
    searchInput.addEventListener('keyup', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            carregarProdutos(1, searchInput.value);
        }, 300);
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

    // Delegação de eventos para a tabela inteira
    tabelaCorpo.addEventListener('click', async (e) => {
        const tr = e.target.closest('tr');
        if (!tr) return;

        const id = tr.dataset.id;
        const spans = tr.querySelectorAll('.data-span');
        const inputs = tr.querySelectorAll('.form-control, .form-select, .form-check-input');
        
        // Botão Editar
        if (e.target.classList.contains('btn-editar')) {
            tr.classList.replace('view-mode', 'edit-mode');
        }

        // Botão Cancelar
        if (e.target.classList.contains('btn-cancelar')) {
            tr.classList.replace('edit-mode', 'view-mode');
        }

        // Botão Salvar
        if (e.target.classList.contains('btn-salvar')) {
            const formData = new FormData();
            formData.append('id', id);
            formData.append('nome', tr.querySelector('input[type="text"]').value);
            formData.append('preco', tr.querySelector('input[type="number"]').value);
            formData.append('id_categoria', tr.querySelector('select').value);
            formData.append('disponivel', tr.querySelector('input[type="checkbox"]').checked ? 1 : 0);
            formData.append('imagem_antiga', tr.querySelector('.thumb-preview').src.split('/').pop());
            
            const fileInput = tr.querySelector('input[type="file"]');
            if (fileInput.files.length > 0) {
                formData.append('imagem', fileInput.files[0]);
            }

            try {
                const response = await fetch('../api/produto_atualizar.php', { method: 'POST', body: formData });
                const result = await response.json();
                if (result.sucesso) {
                    alert('Produto salvo com sucesso!');
                    carregarProdutos(parseInt(document.querySelector('.pagination .active a')?.dataset.page || 1, 10), searchInput.value);
                } else {
                    alert('Erro ao salvar: ' + result.erro);
                }
            } catch (error) {
                alert('Erro de conexão ao salvar.');
            }
        }

        // Botão Deletar
        if (e.target.classList.contains('btn-deletar')) {
            const nome = tr.querySelector('.data-nome').textContent;
            if (confirm(`Tem certeza que deseja deletar o produto "${nome}"?`)) {
                const formData = new FormData();
                formData.append('id', id);
                try {
                    const response = await fetch('../api/produto_deletar.php', { method: 'POST', body: formData });
                    const result = await response.json();
                    if (result.sucesso) {
                        alert('Produto deletado!');
                        tr.remove();
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
            if(tr.classList.contains('edit-mode')) {
                const fileInput = tr.querySelector('input[type="file"]');
                fileInput.click();
                fileInput.onchange = () => {
                    if (fileInput.files.length > 0) {
                        const reader = new FileReader();
                        reader.onload = (event) => { e.target.src = event.target.result; };
                        reader.readAsDataURL(fileInput.files[0]);
                    }
                };
            }
        }
    });

    // --- CARREGAMENTO INICIAL ---
    carregarDadosIniciais();
});