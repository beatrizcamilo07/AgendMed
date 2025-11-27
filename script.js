// javascript/script.js

document.addEventListener('DOMContentLoaded', function() {
    
    // ==========================================================
    // PARTE 1: LÓGICA DO FORMULÁRIO DE CADASTRO (SEU CÓDIGO)
    // ==========================================================
    const formCadastro = document.querySelector('#form-cadastro'); // Ajustado para usar o ID
    const tipoUsuario = document.getElementById('tipo_usuario');
    const camposMedico = document.getElementById('campos-medico');
   
    if (formCadastro) {
        // Mostrar/ocultar campos de médico conforme seleção
        tipoUsuario.addEventListener('change', function() {
            if (this.value === 'medico') {
                camposMedico.classList.remove('hidden');
                document.getElementById('crm').required = true;
                document.getElementById('especialidade').required = true;
            } else {
                camposMedico.classList.add('hidden');
                document.getElementById('crm').required = false;
                document.getElementById('especialidade').required = false;
            }
        });
       
        // Máscara para telefone
        const phoneInput = document.getElementById('number');
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/g, '($1) $2');
            value = value.replace(/(\d)(\d{4})$/, '$1-$2');
            e.target.value = value;
        });
       
        // Listener de envio do formulário de CADASTRO
        formCadastro.addEventListener('submit', function(e) {
            e.preventDefault();
            
            clearErrors();
            
            const isValidNome = validateName('firstname', 'Primeiro nome');
            const isValidSobrenome = validateName('lastname', 'Sobrenome');
            const isValidEmail = validateEmail();
            const isValidPhone = validatePhone();
            const isValidTipoUsuario = validateTipoUsuario();
            const isValidPassword = validatePassword();
            
            let isValidMedicoFields = true;
            if (tipoUsuario.value === 'medico') {
                isValidMedicoFields = validateCRM() && validateEspecialidade();
            }
            
            if (isValidNome && isValidSobrenome && isValidEmail && isValidPhone && isValidTipoUsuario && isValidPassword && isValidMedicoFields) {
                // Validação passou, agora enviamos com FETCH
                const formData = new FormData(formCadastro);
                const data = Object.fromEntries(formData.entries());

                fetch('api/usuarios/cadastrar.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(response => response.json().then(body => ({ status: response.status, body: body })))
                .then(response => {
                    if (response.status === 201) {
                        alert(response.body.message);
                        formCadastro.reset();
                        window.location.href = 'index.html';
                    } else {
                        showError(document.getElementById('email'), response.body.message);
                    }
                })
                .catch(error => console.error('Erro:', error));
            }
        });
    }

    // ==========================================================
    // PARTE 2: LÓGICA DO FORMULÁRIO DE LOGIN (NOVO CÓDIGO)
    // ==========================================================
    const formLogin = document.getElementById('form-login');

    if (formLogin) {
        formLogin.addEventListener('submit', function(e) {
            e.preventDefault();
            clearErrors();

            const isEmailValid = validateEmail(); // Reutilizando sua função!
            const isPasswordValid = validateLoginPassword();

            if (isEmailValid && isPasswordValid) {
                const formData = new FormData(formLogin);
                const data = Object.fromEntries(formData.entries());

                fetch('api/usuarios/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                })
                .then(response => response.json().then(body => ({ status: response.status, body: body })))
                .then(response => {
                    if (response.status === 200) {
                        // Redirecionamento baseado no tipo de usuário
                        switch (response.body.tipo_usuario) {
                            case 'paciente': window.location.href = 'dashboard_paciente.html'; break;
                            case 'medico': window.location.href = 'dashboard_medico.html'; break;
                            case 'adm': window.location.href = 'dashboard_admin.html'; break;
                            default: window.location.href = 'index.html';
                        }
                    } else {
                        showError(document.getElementById('password'), response.body.message);
                    }
                })
                .catch(error => console.error('Erro:', error));
            }
        });
    }

    // ==========================================================
    // PARTE 3: SUAS FUNÇÕES DE VALIDAÇÃO (COMPARTILHADAS)
    // ==========================================================
    
    // Adicionando a validação de senha para o login (mais simples)
    function validateLoginPassword() {
        const input = document.getElementById('password');
        if (input.value === '') {
            showError(input, 'Senha é obrigatória');
            return false;
        }
        showSuccess(input);
        return true;
    }

    function validateName(id, fieldName) {
        const input = document.getElementById(id);
        const value = input.value.trim();
        if (value === '') { showError(input, `${fieldName} é obrigatório`); return false; }
        if (value.length < 2) { showError(input, `${fieldName} deve ter pelo menos 2 caracteres`); return false; }
        if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(value)) { showError(input, `${fieldName} deve conter apenas letras`); return false; }
        showSuccess(input); return true;
    }
   
    function validateEmail() {
        const input = document.getElementById('email');
        const value = input.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (value === '') { showError(input, 'Email é obrigatório'); return false; }
        if (!emailRegex.test(value)) { showError(input, 'Email inválido'); return false; }
        showSuccess(input); return true;
    }
   
    function validatePhone() {
        const input = document.getElementById('number');
        const value = input.value.trim();
        const phoneRegex = /^\(\d{2}\) \d{4,5}-\d{4}$/;
        if (value === '') { showError(input, 'Celular é obrigatório'); return false; }
        if (!phoneRegex.test(value)) { showError(input, 'Formato inválido. Use (XX) XXXXX-XXXX'); return false; }
        showSuccess(input); return true;
    }
   
    function validateTipoUsuario() {
        const input = document.getElementById('tipo_usuario');
        if (input.value === '') { showError(input, 'Selecione um tipo de usuário'); return false; }
        showSuccess(input); return true;
    }
   
    function validatePassword() {
        const input = document.getElementById('password');
        const value = input.value;
        if (value === '') { showError(input, 'Senha é obrigatória'); return false; }
        if (value.length < 8) { showError(input, 'Senha deve ter pelo menos 8 caracteres'); return false; }
        if (!/[A-Z]/.test(value)) { showError(input, 'Senha deve conter pelo menos uma letra maiúscula'); return false; }
        if (!/[0-9]/.test(value)) { showError(input, 'Senha deve conter pelo menos um número'); return false; }
        if (!/[!@#$%^&*(),.?":{}|<>]/.test(value)) { showError(input, 'Senha deve conter pelo menos um caractere especial'); return false; }
        showSuccess(input); return true;
    }

    function validateLoginPassword() {
        const input = document.getElementById('password');
        if (input.value === '') { showError(input, 'Senha é obrigatória'); return false; }
        showSuccess(input); return true;
    }
   
    function validateCRM() {
        const input = document.getElementById('crm');
        const value = input.value.trim();
        if (value === '') { showError(input, 'CRM é obrigatório para médicos'); return false; }
        showSuccess(input); return true;
    }
   
    function validateEspecialidade() {
        const input = document.getElementById('especialidade');
        const value = input.value.trim();
        if (value === '') { showError(input, 'Especialidade é obrigatória para médicos'); return false; }
        showSuccess(input); return true;
    }
   
    function showPasswordStrength() {
        const password = document.getElementById('password').value;
        const strengthBar = document.getElementById('password-strength-bar') || createPasswordStrengthBar();
        let strength = 0;
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
        const width = (strength / 4) * 100;
        strengthBar.style.width = `${width}%`;
        if (strength <= 1) { strengthBar.style.backgroundColor = '#ff5252'; }
        else if (strength <= 3) { strengthBar.style.backgroundColor = '#ffb142'; }
        else { strengthBar.style.backgroundColor = '#4caf50'; }
    }
   
    function createPasswordStrengthBar() {
        const passwordContainer = document.getElementById('password').parentElement;
        const strengthContainer = document.createElement('div');
        strengthContainer.style.cssText = "height: 4px; margin-top: 5px; background-color: #eee; border-radius: 2px; overflow: hidden;";
        const strengthBar = document.createElement('div');
        strengthBar.id = 'password-strength-bar';
        strengthBar.style.cssText = "height: 100%; width: 0%; transition: width 0.3s, background-color 0.3s;";
        strengthContainer.appendChild(strengthBar);
        passwordContainer.appendChild(strengthContainer);
        return strengthBar;
    }
   
    function showError(input, message) {
        const inputBox = input.parentElement;
        input.classList.remove('success');
        input.classList.add('error');
        let error = inputBox.querySelector('.error-message');
        if (!error) {
            error = document.createElement('span');
            error.className = 'error-message';
            inputBox.appendChild(error);
        }
        error.textContent = message;
        error.style.cssText = "color: #ff5252; font-size: 0.8rem; margin-top: 5px; display: block;";
    }
   
    function showSuccess(input) {
        const inputBox = input.parentElement;
        input.classList.remove('error');
        input.classList.add('success');
        const error = inputBox.querySelector('.error-message');
        if (error) { error.remove(); }
    }
   
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(e => e.remove());
        document.querySelectorAll('.error').forEach(e => e.classList.remove('error'));
        document.querySelectorAll('.success').forEach(e => e.classList.remove('success'));
    }
});

