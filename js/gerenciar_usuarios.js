// js/gerenciar_usuarios.js
document.addEventListener('DOMContentLoaded', () => {
    const tabelaCorpo = document.getElementById('tabela-usuarios-corpo');
    let todosPapeis = [];

    const carregarDadosIniciais = async () => {
        try {
            const [usuariosResponse, papeisResponse] = await Promise.all([
                fetch('../api/usuarios_listar.php'),
                fetch('../api/papeis_listar.php')
            ]);
            const usuariosData = await usuariosResponse.json();
            const papeisData = await papeisResponse.json();

            if (papeisData.sucesso) todosPapeis = papeisData.papeis;
            
            if (usuariosData.sucesso) {
                renderizarTabela(usuariosData.usuarios);
            } else {
                tabelaCorpo.innerHTML = `<tr><td colspan="6" class="text-center text-danger">${usuariosData.erro}</td></tr>`;
            }
        } catch (error) {
            tabelaCorpo.innerHTML = `<tr><td colspan="6" class="text-center text-danger">Erro de conexão ao carregar dados.</td></tr>`;
        }
    };

    const renderizarTabela = (usuarios) => {
        tabelaCorpo.innerHTML = '';
        const optionsHTML = todosPapeis.map(papel => `<option value="${papel.id}">${papel.nome}</option>`).join('');

        usuarios.forEach(usuario => {
            const tr = document.createElement('tr');
            tr.dataset.id = usuario.id;
            tr.innerHTML = `
                <td>${usuario.id}</td>
                <td>${usuario.nome}</td>
                <td>${usuario.email}</td>
                <td>${usuario.celular}</td>
                <td>
                    <span class="data-span">${usuario.nome_papel}</span>
                    <select class="form-select form-select-sm" style="display: none;">${optionsHTML}</select>
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-warning btn-editar">Alterar Papel</button>
                    <button class="btn btn-sm btn-success btn-salvar" style="display: none;">Salvar</button>
                    <button class="btn btn-sm btn-secondary btn-cancelar" style="display: none;">Cancelar</button>
                </td>
            `;
            tr.querySelector('select').value = usuario.id_papel;
            tabelaCorpo.appendChild(tr);
        });
    };

    tabelaCorpo.addEventListener('click', (e) => {
        const tr = e.target.closest('tr');
        if (!tr) return;

        const span = tr.querySelector('.data-span');
        const select = tr.querySelector('select');
        const btnEditar = tr.querySelector('.btn-editar');
        const btnSalvar = tr.querySelector('.btn-salvar');
        const btnCancelar = tr.querySelector('.btn-cancelar');

        if (e.target === btnEditar) {
            span.style.display = 'none';
            select.style.display = 'block';
            btnEditar.style.display = 'none';
            btnSalvar.style.display = 'inline-block';
            btnCancelar.style.display = 'inline-block';
        }

        if (e.target === btnCancelar) {
            span.style.display = 'block';
            select.style.display = 'none';
            select.value = [...span.textContent].join('') === 'Administrador' ? 2 : 1; // Reseta o select
            btnEditar.style.display = 'inline-block';
            btnSalvar.style.display = 'none';
            btnCancelar.style.display = 'none';
        }

        if (e.target === btnSalvar) {
            const idUsuario = tr.dataset.id;
            const idPapel = select.value;

            const formData = new FormData();
            formData.append('id_usuario', idUsuario);
            formData.append('id_papel', idPapel);

            fetch('../api/usuario_atualizar_papel.php', { method: 'POST', body: formData })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        span.textContent = select.options[select.selectedIndex].text;
                        btnCancelar.click(); // Volta para o modo de visualização
                    } else {
                        alert('Erro: ' + data.erro);
                    }
                })
                .catch(() => alert('Erro de conexão.'));
        }
    });

    carregarDadosIniciais();
});