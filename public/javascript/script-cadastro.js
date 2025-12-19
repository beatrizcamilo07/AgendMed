document.addEventListener('DOMContentLoaded', function() {
    const selectTipo = document.getElementById('tipo_usuario');
    const camposMedico = document.getElementById('campos-medico');
    const inputCrm = document.getElementById('crm');
    const inputEspec = document.getElementById('especialidade');

    // Função para mostrar/esconder
    function toggleCamposMedico() {
        if (selectTipo.value === 'medico') {
            camposMedico.style.display = 'block'; // Remove a classe hidden ou muda display
            camposMedico.classList.remove('hidden');
            
            // Torna obrigatório se for médico
            inputCrm.setAttribute('required', 'required');
            inputEspec.setAttribute('required', 'required');
        } else {
            camposMedico.style.display = 'none';
            camposMedico.classList.add('hidden');
            
            // Remove obrigatoriedade
            inputCrm.removeAttribute('required');
            inputEspec.removeAttribute('required');
            
            // Limpa os campos para não enviar sujeira
            inputCrm.value = '';
            inputEspec.value = '';
        }
    }

    // Escuta a mudança no select
    selectTipo.addEventListener('change', toggleCamposMedico);
});