const medicoEl = document.getElementById('id_medico');
const dataEl   = document.getElementById('data');
const horaEl   = document.getElementById('hora');

async function buscarHorarios() {
    if (!medicoEl.value || !dataEl.value) return;

    const resp = await fetch(
        `index.php?url=paciente/horariosDisponiveis&id_med=${medicoEl.value}&data=${dataEl.value}`
    );
    const horarios = await resp.json();

    horaEl.innerHTML = '<option value="">Selecione</option>';

    horarios.forEach(h => {
        const opt = document.createElement('option');
        opt.value = h.id_horario;
        opt.textContent = h.hora;
        horaEl.appendChild(opt);
    });
}

medicoEl.addEventListener('change', buscarHorarios);
dataEl.addEventListener('change', buscarHorarios);

document.getElementById('form-agendamento').addEventListener('submit', async e => {
    e.preventDefault();

    if (!horaEl.value) {
        alert('Selecione um horário');
        return;
    }

    const resp = await fetch('index.php?url=paciente/agendar', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id_horario: horaEl.value })
    });

    const res = await resp.json();

    if (res.sucesso) {
        alert('Consulta agendada!');
        location.reload();
    } else {
        alert(res.erro);
    }
});