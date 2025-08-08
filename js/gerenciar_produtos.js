// js/gerenciar_produtos.js
document.addEventListener('DOMContentLoaded', () => {
    const tabelaCorpo = document.getElementById('tabela-corpo');
    const searchInput = document.getElementById('searchInput');
    let todosProdutos = []; // Cache dos produtos para busca no lado do cliente

    const carregarProdutos = async () => {
        try {
            const response = await fetch('../api/produtos_listar.php');
            const data = await response.json();

            if (data.sucesso) {
                todosProdutos = data.produtos;
                renderizarTabela(todosProdutos);
            } else {
                tabelaCorpo.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erro ao carregar produtos: ${data.erro}</td></tr>`;
            }
        } catch (error) {
            tabelaCorpo.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erro de conexão com a API.</td></tr>`;
        }
    };

    const renderizarTabela = (produtos) => {
        tabelaCorpo.innerHTML = '';
        if (produtos.length === 0) {
            tabelaCorpo.innerHTML = `<tr><td colspan="6" class="text-center">Nenhum produto encontrado.</td></tr>`;
            return;
        }

        produtos.forEach(produto => {
            const tr = document.createElement('tr');
            tr.setAttribute('data-id', produto.id);
            tr.classList.add('view-mode'); // Inicia em modo de visualização

            // Atualize o innerHTML para incluir a nova célula de Categoria
            tr.innerHTML = `
                <td>${produto.id}</td>
                <td>
                    <img src="../${produto.imagem_thumb_url}" alt="${produto.nome}" class="thumb-preview" title="Clique para alterar a imagem">
                    <input type="file" class="form-control form-control-sm" style="display: none;" accept="image/*">
                </td>
                <td>
                    <span class="data-span data-nome">${produto.nome}</span>
                    <input type="text" class="form-control form-control-sm inline-edit" value="${produto.nome}">
                </td>
                <td>
                    <span class="data-span data-categoria">${produto.nome_categoria || 'N/A'}</span>
                    </td>
                <td>
                    <span class="data-span data-preco">R$ ${parseFloat(produto.preco).toFixed(2)}</span>
                    <input type="number" step="0.01" class="form-control form-control-sm inline-edit" value="${produto.preco}">
                </td>
                <td>
                    <span class="data-span data-ativo">${produto.disponivel == 1 ? 'Sim' : 'Não'}</span>
                    <input type="checkbox" class="form-check-input" ${produto.disponivel == 1 ? 'checked' : ''}>
                </td>
                <td class="text-end">
                    </td>
            `;
            tabelaCorpo.appendChild(tr);
        });
    };

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