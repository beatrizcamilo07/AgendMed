document.addEventListener("DOMContentLoaded", () => {

    carregarHoje();
    carregarFuturas();
    carregarHorarios();

    document.getElementById("formHorario").addEventListener("submit", e => {
        e.preventDefault();
        adicionarHorario();
    });
});

function carregarHoje() {
    fetch("../../controllers/medicoController.php?action=consultasHoje")
        .then(r => r.json())
        .then(data => {
            const tbody = document.querySelector("#tabelaHoje tbody");
            tbody.innerHTML = "";

            data.forEach(c => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${c.paciente}</td>
                    <td>${c.hora}</td>
                    <td>${c.status}</td>
                `;
                tbody.appendChild(tr);
            });
        });
}

function carregarFuturas() {
    fetch("../../controllers/medicoController.php?action=consultasFuturas")
        .then(r => r.json())
        .then(data => {
            const tbody = document.querySelector("#tabelaFuturas tbody");
            tbody.innerHTML = "";

            data.forEach(c => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${c.paciente}</td>
                    <td>${c.data}</td>
                    <td>${c.hora}</td>
                `;
                tbody.appendChild(tr);
            });
        });
}

function carregarHorarios() {
    fetch("../../controllers/medicoController.php?action=listarHorarios")
        .then(r => r.json())
        .then(data => {
            const tbody = document.querySelector("#tabelaHorarios tbody");
            tbody.innerHTML = "";

            data.forEach(h => {
                const tr = document.createElement("tr");
                tr.innerHTML = `
                    <td>${h.dia_semana}</td>
                    <td>${h.hora_inicio}</td>
                    <td>${h.hora_fim}</td>
                `;
                tbody.appendChild(tr);
            });
        });
}

function adicionarHorario() {
    const formData = new FormData(document.getElementById("formHorario"));

    fetch("../../controllers/medicoController.php?action=adicionarHorario", {
        method: "POST",
        body: formData
    })
        .then(r => r.json())
        .then(data => {
            alert(data.mensagem);
            carregarHorarios();
        });
}
