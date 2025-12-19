<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/cadastro.css">
    <title>Cadastro</title>
</head>

<body>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<div class="container">
    <div class="form-image">
        <img src="public/assets/img/undraw_medicine_hqqg.svg" alt="Cadastro">
    </div>

    <div class="form">
        <form action="index.php?url=cadastro/cadastrar" method="post">

            <div class="form-header">
                <div class="title">
                    <h1>Cadastrar</h1>
                </div>
                <div class="login-button">
                    <a href="index.php?action=login">Entrar</a>
                </div>
            </div>

            <!-- Mensagens -->
            <?php if (!empty($_SESSION['error'])): ?>
                <p class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
            <?php endif; ?>

            <?php if (!empty($_SESSION['success'])): ?>
                <p class="success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
            <?php endif; ?>

            <div class="input-group">

                <div class="input-box">
                    <label>Nome</label>
                    <input type="text" name="nome" required>
                </div>

                <div class="input-box">
                    <label>Sobrenome</label>
                    <input type="text" name="sobrenome" required>
                </div>

                <div class="input-box">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="input-box">
                    <label>Celular</label>
                    <input type="tel" name="celular" required>
                </div>

                <div class="input-box">
                    <label>Tipo de usuário</label>
                    <select id="tipo_usuario" name="tipo_usuario" required>
                        <option value="">Selecione</option>
                        <option value="paciente">Paciente</option>
                        <option value="medico">Médico</option>
                        <option value="administrador">Administrador</option>
                    </select>
                </div>

                <!-- Médico -->
                <div id="campos-medico" class="hidden">
                    <div class="input-box">
                        <label>CRM</label>
                        <input type="text" name="crm">
                    </div>

                    <div class="input-box">
                        <label>Especialidade</label>
                        <input type="text" name="especialidade">
                    </div>
                </div>

                <div class="input-box">
                    <label>Senha</label>
                    <input type="password" name="senha" required>
                </div>
            </div>

            <div class="gender-inputs">
                <h6>Gênero</h6>

                <label><input type="radio" name="genero" value="feminino"> Feminino</label>
                <label><input type="radio" name="genero" value="masculino"> Masculino</label>
                <label><input type="radio" name="genero" value="outros"> Outros</label>
            </div>

            <div class="continue-button">
                <button type="submit">Cadastrar</button>
            </div>

        </form>
    </div>
</div>

<script src="public/javascript/script-cadastro.js"></script>
</body>
</html>
