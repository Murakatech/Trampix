// public/js/dashboard.js

document.addEventListener('DOMContentLoaded', () => {
    const API_BASE_URL = 'http://localhost/api'; // URL base da sua API Laravel (sem .php)

    // Função auxiliar para exibir mensagens
    function displayMessage(elementId, message, type = 'info') {
        const messageDiv = document.getElementById(elementId);
        messageDiv.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>`;
    }

    // Função para renderizar uma tabela
    function renderTable(data, tableBodySelector, tableHeadSelector) {
        const tableBody = document.querySelector(tableBodySelector);
        const tableHead = document.querySelector(tableHeadSelector);
        tableBody.innerHTML = '';
        tableHead.innerHTML = '';

        if (!data || data.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="100%" class="text-center text-muted">Nenhum registro encontrado.</td></tr>`;
            return;
        }

        // Renderiza cabeçalhos
        const headers = Object.keys(data[0]);
        let headerRow = '<tr>';
        headers.forEach(header => {
            headerRow += `<th>${header.replace(/_/g, ' ').toUpperCase()}</th>`;
        });
        headerRow += '</tr>';
        tableHead.innerHTML = headerRow;

        // Renderiza linhas
        data.forEach(item => {
            let row = '<tr>';
            headers.forEach(header => {
                let value = item[header];
                // Tratamento básico para objetos/arrays aninhados
                if (typeof value === 'object' && value !== null) {
                    value = JSON.stringify(value); // Converte objeto para string JSON
                }
                row += `<td>${value !== null ? String(value) : 'N/A'}</td>`;
            });
            row += '</tr>';
            tableBody.innerHTML += row;
        });
    }

    // Função para buscar e exibir dados de um recurso
    async function fetchData(resource) {
        const tableBodySelector = `.card-body table[data-resource="${resource}"] tbody`;
        const tableHeadSelector = `.card-body table[data-resource="${resource}"] thead`;
        const cardBody = document.querySelector(`.card-body table[data-resource="${resource}"]`).closest('.card-body');
        cardBody.querySelector('.table-responsive').innerHTML = '<div class="text-center text-info">Carregando...</div>';

        try {
            const response = await fetch(`${API_BASE_URL}/${resource}`);
            const data = await response.json();

            if (response.ok) {
                renderTable(data, tableBodySelector, tableHeadSelector);
            } else {
                displayMessage('listMessage', `Erro ao carregar ${resource}: ${data.message || 'Erro desconhecido'}`, 'danger');
                tableBody.innerHTML = `<tr><td colspan="100%" class="text-center text-danger">Erro ao carregar dados.</td></tr>`;
            }
        } catch (error) {
            console.error(`Erro ao buscar ${resource}:`, error);
            displayMessage('listMessage', `Erro de conexão com a API para ${resource}. Verifique o backend.`, 'danger');
            tableBody.innerHTML = `<tr><td colspan="100%" class="text-center text-danger">Erro de conexão.</td></tr>`;
        }
    }

    // Carregar todas as tabelas ao iniciar
    const resourcesToLoad = ['users', 'job_vacancies', 'freelancers', 'companies', 'skills', 'applications', 'logs'];
    resourcesToLoad.forEach(resource => fetchData(resource));

    // Event listeners para botões de atualizar
    document.querySelectorAll('.card-body button[data-resource]').forEach(button => {
        button.addEventListener('click', (e) => {
            const resource = e.target.dataset.resource;
            fetchData(resource);
        });
    });

    // Lógica para o formulário de Criar Usuário (POST)
    const createUserForm = document.getElementById('createUserForm');
    createUserForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;
        const userType = document.getElementById('userType').value;
        const userStatus = document.getElementById('userStatus').value;

        try {
            const response = await fetch(`${API_BASE_URL}/users`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email, password, user_type: userType, status: userStatus })
            });

            const result = await response.json();

            if (response.ok) {
                displayMessage('createUserMessage', `Usuário criado com sucesso! ID: ${result.user.user_id || 'N/A'}`, 'success');
                createUserForm.reset();
                fetchData('users'); // Atualiza a lista de usuários
            } else {
                displayMessage('createUserMessage', `Erro ao criar usuário: ${result.message || 'Erro desconhecido'}`, 'danger');
            }
        } catch (error) {
            console.error('Erro ao criar usuário:', error);
            displayMessage('createUserMessage', 'Erro de conexão ao criar usuário.', 'danger');
        }
    });

    // Adicione aqui a lógica para botões de Editar/Excluir (PUT/DELETE)
    // Isso envolverá adicionar event listeners às tabelas e chamar os endpoints PUT/DELETE da API
    // Exemplo (apenas estrutura):
    // document.querySelectorAll('.card-body table tbody').forEach(tbody => {
    //     tbody.addEventListener('click', async (e) => {
    //         if (e.target.tagName === 'BUTTON') {
    //             const action = e.target.dataset.action;
    //             const id = e.target.dataset.id;
    //             const resource = e.target.closest('.card-body').querySelector('button[data-resource]').dataset.resource; // Pega o recurso da tabela

    //             if (action === 'delete') {
    //                 if (confirm(`Tem certeza que deseja deletar o ${resource} com ID ${id}?`)) {
    //                     try {
    //                         const response = await fetch(`${API_BASE_URL}/${resource}/${id}`, { method: 'DELETE' });
    //                         if (response.ok) {
    //                             displayMessage('listMessage', `${resource} deletado com sucesso!`, 'success');
    //                             fetchData(resource); // Atualiza a lista
    //                         } else {
    //                             const result = await response.json();
    //                             displayMessage('listMessage', `Erro ao deletar ${resource}: ${result.message || 'Erro desconhecido'}`, 'danger');
    //                         }
    //                     } catch (error) {
    //                         displayMessage('listMessage', `Erro de conexão ao deletar ${resource}.`, 'danger');
    //                     }
    //                 }
    //             } else if (action === 'edit') {
    //                 // Lógica para preencher formulário de edição
    //                 displayMessage('listMessage', `Funcionalidade de edição para ${resource} ID ${id} em desenvolvimento.`, 'info');
    //             }
    //         }
    //     });
    // });
});
