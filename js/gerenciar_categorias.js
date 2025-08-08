// js/gerenciar_categorias.js
document.addEventListener('DOMContentLoaded', () => {
    const tabelaCorpo = document.querySelector('table tbody');

    // Usamos delegação de eventos para ouvir cliques na tabela inteira
    tabelaCorpo.addEventListener('click', (e) => {
        const tr = e.target.closest('tr');
        if (!tr) return;

        // -- MODO DE EDIÇÃO --
        if (e.target.classList.contains('btn-editar')) {
            const span = tr.querySelector('.nome-categoria');
            const input = tr.querySelector('input[type="text"]');
            
            // Alterna a visibilidade dos elementos
            span.style.display = 'none';
            input.style.display = 'block';
            input.focus();

            tr.querySelector('.btn-editar').style.display = 'none';
            tr.querySelector('.btn-deletar').style.display = 'none';
            tr.querySelector('.btn-salvar').style.display = 'inline-block';
            tr.querySelector('.btn-cancelar').style.display = 'inline-block';
        }

        // -- CANCELAR EDIÇÃO --
        if (e.target.classList.contains('btn-cancelar')) {
            const span = tr.querySelector('.nome-categoria');
            const input = tr.querySelector('input[type="text"]');

            // Restaura o valor original do input e alterna a visibilidade
            input.value = span.textContent;
            span.style.display = 'block';
            input.style.display = 'none';

            tr.querySelector('.btn-editar').style.display = 'inline-block';
            tr.querySelector('.btn-deletar').style.display = 'inline-block';
            tr.querySelector('.btn-salvar').style.display = 'none';
            tr.querySelector('.btn-cancelar').style.display = 'none';
        }

        // -- SALVAR ALTERAÇÕES --
        if (e.target.classList.contains('btn-salvar')) {
            const id = tr.dataset.id;
            const input = tr.querySelector('input[type="text"]');
            const novoNome = input.value.trim();

            if (!novoNome) {
                alert('O nome da categoria não pode ser vazio.');
                return;
            }

            const formData = new FormData();
            formData.append('id', id);
            formData.append('nome', novoNome);

            fetch('../api/categoria_atualizar.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        const span = tr.querySelector('.nome-categoria');
                        span.textContent = novoNome;
                        // Dispara o evento de clique no botão cancelar para voltar ao modo de visualização
                        tr.querySelector('.btn-cancelar').click();
                    } else {
                        alert('Erro: ' + data.erro);
                    }
                })
                .catch(() => alert('Erro de conexão ao salvar.'));
        }

        // -- DELETAR CATEGORIA --
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
                            tr.remove(); // Remove a linha da tabela
                        } else {
                            alert('Erro: ' + data.erro);
                        }
                    })
                    .catch(() => alert('Erro de conexão ao deletar.'));
            }
        }
    });
});