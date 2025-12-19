const medicoEl = document.getElementById('id_medico');
const dataEl   = document.getElementById('data');
const horaEl   = document.getElementById('hora');

async function buscarHorarios() {
    if (!medicoEl.value || !dataEl.value) return;

    const url = `index.php?url=paciente/horariosPorMedico&id_medico=${medicoEl.value}&data=${dataEl.value}`;

    console.log('Buscando:', url);

    const resp = await fetch(url);
    const horarios = await resp.json();

    horaEl.innerHTML = '<option value="">Selecione a hora</option>';

    if (horarios.length === 0) {
        const opt = document.createElement('option');
        opt.textContent = 'Nenhum horário disponível';
        opt.disabled = true;
        horaEl.appendChild(opt);
        return;
    }

    horarios.forEach(h => {
        const opt = document.createElement('option');
        opt.value = h.id_horario;
        opt.textContent = h.hora.substring(0, 5);
        horaEl.appendChild(opt);
    });
}

medicoEl.addEventListener('change', buscarHorarios);
dataEl.addEventListener('change', buscarHorarios);

document
  .getElementById('form-agendamento')
  .addEventListener('submit', async e => {
    e.preventDefault();

    if (!horaEl.value) {
        alert('Selecione um horário');
        return;
    }

    const formData = new FormData();
    formData.append('id_horario', horaEl.value);

    const resp = await fetch('index.php?url=paciente/agendar', {
        method: 'POST',
        body: formData
    });

    const res = await resp.json();

    if (res.sucesso) {
        alert('Consulta agendada!');
        location.reload();
    } else {
        alert(res.erro);
    }
});