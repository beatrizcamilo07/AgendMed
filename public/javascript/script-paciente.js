document.addEventListener("DOMContentLoaded", () => {
    carregarMedicos();
    carregarAgendamentos();
    carregarHistorico();

    const form = document.getElementById("form-agendamento");
    form.addEventListener("submit", function (e) {
        e.preventDefault();
        agendarConsulta();
    });
});

// Carregar médicos no select
function carregarMedicos() {
    fetch("../../controllers/medicoController.php?action=listar")
        .then(res => res.json())
        .then(data => {
            const select = document.getElementById("especialidade");
            select.innerHTML = '<option value="">Selecione...</option>';

            data.forEach(medico => {
                const opt = document.createElement("option");
                opt.value = medico.id;
                opt.textContent = `${medico.especialidade} - Dr(a). ${medico.nome}`;
                select.appendChild(opt);
            });
        })
        .catch(() => {
            alert("Erro ao carregar médicos.");
        });
}

// Agendar consulta
function agendarConsulta() {
    const formData = new FormData(document.getElementById("form-agendamento"));

    fetch("../../controllers/consultaController.php?action=agendar", {
        method: "POST",
        body: formData
    })
        .then(res => res.json())
        .then(data => {
            alert(data.mensagem);
            carregarAgendamentos();
            carregarHistorico();
        })
        .catch(() => alert("Erro ao agendar consulta."));
}

// Listar consultas marcadas
function carregarAgendamentos() {
    fetch("../../controllers/consultaController.php?action=listar")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#tabelaAgendamentos tbody");
            tbody.innerHTML = "";

            data.forEach(consulta => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${consulta.medico}</td>
                    <td>${consulta.data}</td>
                    <td>${consulta.hora}</td>
                    <td>${consulta.especialidade}</td>
                    <td>${consulta.status}</td>
                `;
                tbody.appendChild(tr);
            });
        });
}

// Histórico (consultas já realizadas/canceladas)
function carregarHistorico() {
    fetch("../../controllers/consultaController.php?action=historico")
        .then(res => res.json())
        .then(data => {
            const tbody = document.querySelector("#tabelaHistorico tbody");
            tbody.innerHTML = "";

            data.forEach(consulta => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${consulta.medico}</td>
                    <td>${consulta.data}</td>
                    <td>${consulta.hora}</td>
                    <td>${consulta.especialidade}</td>
                    <td>${consulta.status}</td>
                `;
                tbody.appendChild(tr);
            });
        });
}
