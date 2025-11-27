<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/cadastro.css">
    <title>Formulário de Cadastro</title>
</head>

<body>
    <div class="container">
        <div class="form-image">
            <img src="assets/img/undraw_medicine_hqqg.svg" alt="Médicos">
        </div>

        <div class="form">
            <form action="../public/index.php?url=usuario/cadastrar" method="post">

                <div class="form-header">
                    <div class="title">
                        <h1>Cadastra-se</h1>
                    </div>
                    <div class="login-button">
                        <button><a href="index.html">Entrar</a></button>
                    </div>
                </div>

                <div class="input-group">

                    <div class="input-box">
                        <label for="nome">Primeiro nome</label>
                        <input type="text" id="nome" name="nome" placeholder="Digite seu primeiro nome" required>
                    </div>

                    <div class="input-box">
                        <label for="sobrenome">Sobrenome</label>
                        <input type="text" id="sobrenome" name="sobrenome" placeholder="Digite seu sobrenome" required>
                    </div>

                    <div class="input-box">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="input-box">
                        <label for="celular">Celular</label>
                        <input type="tel" id="celular" name="celular" placeholder="(xx) xxxx-xxxx" required>
                    </div>

                    <div class="input-box">
                        <label for="tipo_usuario">Tipo de Usuário</label>
                        <select id="tipo_usuario" name="tipo_usuario" required>
                            <option value="">Selecione</option>
                            <option value="paciente">Paciente</option>
                            <option value="medico">Médico</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>

                    <!-- Campos para médicos -->
                    <div id="campos-medico" class="hidden">
                        <div class="input-box">
                            <label for="crm">CRM</label>
                            <input type="text" id="crm" name="crm" placeholder="Digite seu CRM">
                        </div>

                        <div class="input-box">
                            <label for="especialidade">Especialidade</label>
                            <input type="text" id="especialidade" name="especialidade" placeholder="Sua especialidade médica">
                        </div>
                    </div>

                    <div class="input-box">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                    </div>
                </div>

                <div class="gender-inputs">
                    <div class="gender-title">
                        <h6>Gênero</h6>
                    </div>

                    <div class="gender-group">
                        <div class="gender-input">
                            <input type="radio" id="genero_fem" name="genero" value="feminino">
                            <label for="genero_fem">Feminino</label>
                        </div>

                        <div class="gender-input">
                            <input type="radio" id="genero_masc" name="genero" value="masculino">
                            <label for="genero_masc">Masculino</label>
                        </div>

                        <div class="gender-input">
                            <input type="radio" id="genero_outros" name="genero" value="outros">
                            <label for="genero_outros">Outros</label>
                        </div>
                    </div>
                </div>

                <div class="continue-button">
                    <button type="submit">Continuar</button>
                </div>

            </form>
        </div>
    </div>

    <script src="javascript/script.js"></script>
</body>

</html>
