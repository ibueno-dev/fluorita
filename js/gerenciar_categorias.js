// js/gerenciar_categorias.js
document.addEventListener('DOMContentLoaded', () => {
    const tabelaCorpo = document.getElementById('tabela-categorias-corpo');
    const formAddCategoria = document.getElementById('form-add-categoria');
    const alertPlaceholder = document.getElementById('alert-placeholder');
    let categoriasCache = []; // Guarda os dados para evitar múltiplas chamadas

    // --- FUNÇÕES PRINCIPAIS ---

    /**
     * Busca as categorias da API e renderiza a tabela.
     */
    const carregarCategorias = async () => {
        try {
            const response = await fetch('../api/categorias_listar.php');
            const data = await response.json();
            if (data.sucesso) {
                categoriasCache = data.categorias;
                renderizarTabela(categoriasCache);
            } else {
                mostrarAlerta('danger', 'Erro ao carregar categorias.');
            }
        } catch (error) {
            mostrarAlerta('danger', 'Erro de conexão com a API.');
        }
    };

    /**
     * Limpa e preenche a tabela com os dados das categorias.
     * @param {Array} categorias - O array de objetos de categoria.
     */
    const renderizarTabela = (categorias) => {
        tabelaCorpo.innerHTML = '';
        if (categorias.length === 0) {
            tabelaCorpo.innerHTML = `<tr><td colspan="3" class="text-center">Nenhuma categoria cadastrada.</td></tr>`;
            return;
        }
        categorias.forEach(categoria => {
            const tr = document.createElement('tr');
            tr.dataset.id = categoria.id;
            tr.innerHTML = `
                <td>${categoria.id}</td>
                <td>
                    <span class="nome-categoria">${categoria.nome}</span>
                    <input type="text" class="form-control" value="${categoria.nome}" style="display: none;">
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-warning btn-editar">Editar</button>
                    <button class="btn btn-sm btn-danger btn-deletar">Excluir</button>
                    <button class="btn btn-sm btn-success btn-salvar" style="display: none;">Salvar</button>
                    <button class="btn btn-sm btn-secondary btn-cancelar" style="display: none;">Cancelar</button>
                </td>
            `;
            tabelaCorpo.appendChild(tr);
        });
    };

    /**
     * Exibe um alerta de Bootstrap na página.
     * @param {string} tipo - 'success' ou 'danger'.
     * @param {string} mensagem - A mensagem a ser exibida.
     */
    const mostrarAlerta = (tipo, mensagem) => {
        alertPlaceholder.innerHTML = `<div class="alert alert-${tipo} alert-dismissible" role="alert">
            ${mensagem}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
    };

    // --- EVENT LISTENERS ---

    // Event listener para o formulário de ADICIONAR categoria
    formAddCategoria.addEventListener('submit', (e) => {
        e.preventDefault();
        const nomeInput = document.getElementById('nome_categoria');
        const nome = nomeInput.value.trim();
        if (!nome) {
            mostrarAlerta('danger', 'O nome da categoria não pode ser vazio.');
            return;
        }

        const formData = new FormData();
        formData.append('nome_categoria', nome);

        fetch('../painel/categoria_salvar.php', { method: 'POST', body: formData })
            .then(response => {
                // Como o script PHP redireciona, verificamos se o redirecionamento ocorreu para a URL de sucesso
                if (response.redirected && response.url.includes('status=sucesso')) {
                    mostrarAlerta('success', `Categoria "${nome}" salva com sucesso!`);
                    nomeInput.value = ''; // Limpa o formulário
                    carregarCategorias(); // Recarrega a tabela
                } else {
                    // Se não, o PHP redirecionou para uma URL de erro
                    mostrarAlerta('danger', 'Erro: Esta categoria pode já existir.');
                }
            })
            .catch(() => mostrarAlerta('danger', 'Erro de conexão ao salvar.'));
    });

    // Delegação de eventos para os botões da tabela (EDITAR, DELETAR, SALVAR, CANCELAR)
    tabelaCorpo.addEventListener('click', (e) => {
        const tr = e.target.closest('tr');
        if (!tr) return;

        // O restante da lógica para editar, deletar, etc., que já tínhamos,
        // continua funcionando perfeitamente aqui.
        if (e.target.classList.contains('btn-editar')) {
            const span = tr.querySelector('.nome-categoria');
            const input = tr.querySelector('input[type="text"]');
            span.style.display = 'none';
            input.style.display = 'block';
            input.focus();
            tr.querySelector('.btn-editar').style.display = 'none';
            tr.querySelector('.btn-deletar').style.display = 'none';
            tr.querySelector('.btn-salvar').style.display = 'inline-block';
            tr.querySelector('.btn-cancelar').style.display = 'inline-block';
        }

        if (e.target.classList.contains('btn-cancelar')) {
            const span = tr.querySelector('.nome-categoria');
            const input = tr.querySelector('input[type="text"]');
            input.value = span.textContent;
            span.style.display = 'block';
            input.style.display = 'none';
            tr.querySelector('.btn-editar').style.display = 'inline-block';
            tr.querySelector('.btn-deletar').style.display = 'inline-block';
            tr.querySelector('.btn-salvar').style.display = 'none';
            tr.querySelector('.btn-cancelar').style.display = 'none';
        }

        if (e.target.classList.contains('btn-salvar')) {
            const id = tr.dataset.id;
            const input = tr.querySelector('input[type="text"]');
            const novoNome = input.value.trim();
            if (!novoNome) { mostrarAlerta('danger', 'O nome não pode ser vazio.'); return; }

            const formData = new FormData();
            formData.append('id', id);
            formData.append('nome', novoNome);

            fetch('../api/categoria_atualizar.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        mostrarAlerta('success', 'Categoria atualizada!');
                        carregarCategorias();
                    } else {
                        mostrarAlerta('danger', 'Erro: ' + data.erro);
                    }
                })
                .catch(() => mostrarAlerta('danger', 'Erro de conexão.'));
        }

        if (e.target.classList.contains('btn-deletar')) {
            const id = tr.dataset.id;
            const nome = tr.querySelector('.nome-categoria').textContent;

            if (confirm(`Tem certeza que deseja excluir a categoria "${nome}"?`)) {
                const formData = new FormData();
                formData.append('id', id);

                fetch('../api/categoria_deletar.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.sucesso) {
                            mostrarAlerta('success', 'Categoria excluída!');
                            tr.remove();
                        } else {
                            mostrarAlerta('danger', 'Erro: ' + data.erro);
                        }
                    })
                    .catch(() => mostrarAlerta('danger', 'Erro de conexão.'));
            }
        }
    });

    // --- CARREGAMENTO INICIAL ---
    carregarCategorias();
});