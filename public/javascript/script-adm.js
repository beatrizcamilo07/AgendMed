// --- 1. CONFIGURAÇÃO DE ENDPOINTS ---
const API_BASE_URL = 'http://localhost/seu_projeto/Controllers/'; // AJUSTE O CAMINHO BASE!
const ESPECIALIDADE_URL = API_BASE_URL + 'EspecialidadeController.php';
const CONSULTA_URL = API_BASE_URL + 'ConsultaController.php';

// --- 2. FUNÇÕES GERAIS DE UTILIDADE ---

/**
 * Envia uma requisição Fetch para a API.
 * @param {string} url - O endpoint completo.
 * @param {string} method - Método HTTP (GET, POST, PUT, DELETE).
 * @param {object} data - Dados a serem enviados no corpo (para POST/PUT/DELETE).
 */
async function apiFetch(url, method = 'GET', data = null) {
    const options = {
        method: method,
        headers: {
            'Content-Type': 'application/json',
        },
    };

    if (data) {
        options.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(url, options);
        const result = await response.json();

        if (response.ok) {
            return { success: true, data: result };
        } else {
            // Se o Controller retornar um erro (4xx, 5xx) com JSON
            return { success: false, error: result.mensagem || "Erro desconhecido na API." };
        }
    } catch (e) {
        console.error("Erro na comunicação de rede:", e);
        return { success: false, error: "Erro de conexão com o servidor." };
    }
}

// --- 3. LÓGICA DE ESPECIALIDADES ---

/**
 * Lista e popula a tabela de especialidades.
 */
async function listarEspecialidades() {
    const resultado = await apiFetch(ESPECIALIDADE_URL);
    const tabelaBody = document.getElementById('especialidade-tabela-body'); // ID do tbody da sua tabela

    if (!tabelaBody) return; // Garante que o elemento existe

    tabelaBody.innerHTML = ''; // Limpa a tabela

    if (resultado.success) {
        resultado.data.forEach(esp => {
            const row = tabelaBody.insertRow();
            row.innerHTML = `
                <td>${esp.id}</td>
                <td id="nome-${esp.id}">${esp.nome}</td>
                <td>
                    <button onclick="prepararEdicaoEspecialidade(${esp.id}, '${esp.nome}')" class="btn-sm btn-info">Editar</button>
                    <button onclick="deletarEspecialidade(${esp.id})" class="btn-sm btn-danger">Excluir</button>
                </td>
            `;
        });
    } else {
        tabelaBody.innerHTML = `<tr><td colspan="3">${resultado.error}</td></tr>`;
    }
}

/**
 * Cadastra uma nova especialidade (POST).
 */
async function cadastrarEspecialidade(event) {
    event.preventDefault();
    const nomeInput = document.getElementById('input-nome-especialidade');
    const nome = nomeInput ? nomeInput.value : '';

    if (!nome) {
        alert("O nome da especialidade não pode ser vazio.");
        return;
    }

    const resultado = await apiFetch(ESPECIALIDADE_URL, 'POST', { nome: nome });

    if (resultado.success) {
        alert("✅ " + resultado.data.mensagem);
        nomeInput.value = ''; // Limpa o campo
        listarEspecialidades(); // Recarrega a lista
    } else {
        alert("❌ Falha: " + resultado.error);
    }
}

/**
 * Atualiza o nome de uma especialidade (PUT).
 */
async function atualizarEspecialidade(id, novoNome) {
    const resultado = await apiFetch(ESPECIALIDADE_URL, 'PUT', { id: id, nome: novoNome });

    if (resultado.success) {
        alert("✅ " + resultado.data.mensagem);
        listarEspecialidades();
    } else {
        alert("❌ Falha: " + resultado.error);
    }
}

/**
 * Deleta uma especialidade (DELETE).
 */
async function deletarEspecialidade(id) {
    if (!confirm(`Tem certeza que deseja excluir a especialidade ID ${id}? (Isto pode falhar se houverem médicos vinculados)`)) {
        return;
    }
    
    const resultado = await apiFetch(ESPECIALIDADE_URL, 'DELETE', { id: id });

    if (resultado.success) {
        alert("✅ " + resultado.data.mensagem);
        listarEspecialidades();
    } else {
        alert("❌ Falha: " + resultado.error); // Captura o erro da Foreign Key
    }
}

// Funções para manipulação de formulário (Ex: Abrir Modal de Edição)
function prepararEdicaoEspecialidade(id, nome) {
    // Implemente aqui a lógica para abrir um modal/pop-up
    const novoNome = prompt(`Editando Especialidade ID ${id}. Novo nome:`, nome);
    if (novoNome && novoNome !== nome) {
        atualizarEspecialidade(id, novoNome);
    }
}


// --- 4. LÓGICA DE CONSULTAS (ADMIN) ---

/**
 * Lista e popula a tabela de consultas para o Admin.
 */
async function listarConsultasAdmin() {
    const resultado = await apiFetch(CONSULTA_URL);
    const tabelaBody = document.getElementById('consulta-tabela-body'); // ID do tbody da tabela de consultas

    if (!tabelaBody) return;

    tabelaBody.innerHTML = ''; 

    if (resultado.success) {
        resultado.data.forEach(c => {
            const row = tabelaBody.insertRow();
            row.innerHTML = `
                <td>${c.id}</td>
                <td>${c.paciente_nome_completo}</td>
                <td>${c.medico_id} (${c.especialidade})</td>
                <td>${c.data} ${c.hora}</td>
                <td><span class="status-${c.status}">${c.status}</span></td>
                <td>
                    <select onchange="mudarStatusConsulta(${c.id}, this.value)">
                        <option value="${c.status}" selected>${c.status}</option>
                        <option value="Confirmada">Confirmar</option>
                        <option value="Concluida">Concluir</option>
                        <option value="Cancelada">Cancelar</option>
                    </select>
                </td>
            `;
        });
    } else {
        tabelaBody.innerHTML = `<tr><td colspan="6">${resultado.error}</td></tr>`;
    }
}

/**
 * Atualiza o status de uma consulta (PUT).
 */
async function mudarStatusConsulta(idConsulta, novoStatus) {
    if (!confirm(`Confirma a mudança de status da consulta ID ${idConsulta} para "${novoStatus}"?`)) {
        return;
    }
    
    const dados = {
        id: idConsulta,
        status: novoStatus,
        action: 'update_status' // Identificador da ação no ConsultaController.php
    };
    
    const resultado = await apiFetch(CONSULTA_URL, 'PUT', dados);

    if (resultado.success) {
        alert("✅ Status atualizado com sucesso.");
        listarConsultasAdmin(); // Recarrega a tabela
    } else {
        alert("❌ Falha ao atualizar status: " + resultado.error);
    }
}


// --- 5. INICIALIZAÇÃO ---

document.addEventListener('DOMContentLoaded', () => {
    // Tenta listar especialidades se a tabela existir
    if (document.getElementById('especialidade-tabela-body')) {
        listarEspecialidades();
    }
    
    // Tenta listar consultas se a tabela existir
    if (document.getElementById('consulta-tabela-body')) {
        listarConsultasAdmin();
    }

    // Vincula o evento de submissão do formulário de cadastro de especialidade
    const formEspecialidade = document.getElementById('form-cadastro-especialidade');
    if (formEspecialidade) {
        formEspecialidade.addEventListener('submit', cadastrarEspecialidade);
    }
});