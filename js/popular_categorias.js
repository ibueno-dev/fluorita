// js/popular_categorias.js
document.addEventListener('DOMContentLoaded', () => {
    const categoriaSelect = document.getElementById('categoria-select');

    // Se não encontrar o elemento, não faz nada.
    if (!categoriaSelect) return;

    // Faz a chamada para nossa nova API
    fetch('../api/categorias_listar.php')
        .then(response => response.json())
        .then(data => {
            if (data.sucesso && data.categorias.length > 0) {
                // Limpa o "Carregando..."
                categoriaSelect.innerHTML = '<option value="" disabled selected>Selecione uma categoria</option>';
                
                // Para cada categoria retornada, cria um <option>
                data.categorias.forEach(categoria => {
                    const option = document.createElement('option');
                    option.value = categoria.id;
                    option.textContent = categoria.nome;
                    categoriaSelect.appendChild(option);
                });
            } else {
                categoriaSelect.innerHTML = '<option value="" disabled>Nenhuma categoria encontrada</option>';
            }
        })
        .catch(error => {
            console.error('Erro ao buscar categorias:', error);
            categoriaSelect.innerHTML = '<option value="" disabled>Erro ao carregar</option>';
        });
});
