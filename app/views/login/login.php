<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/login.css">
    <title>Login - AgendMed</title>
</head>
<body>

    <div class="login-container">

        <div class="form-image">
            <img src="public/assets/img/undraw_medicine_hqqg.svg" alt="Médicos"> 
        </div>

        <div class="form">
            <!-- Form apontando para o front controller -->
            <form action="index.php?url=login/autenticar" method="POST">
            
                <div class="form-header">
                    <div class="title">
                        <h1>Login</h1>
                    </div>
                    <!-- Link para cadastro -->
                    <a href="index.php?url=cadastro/index" class="btn-cadastrar">Cadastrar-se</a>
                </div>

                <!-- Mensagem de erro -->
                <?php if (isset($erro) && !empty($erro)): ?>
                    <div style="color: red; text-align: center; margin-bottom: 10px; font-size: 0.9em;">
                        <?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                <?php endif; ?>

                <div class="input-group">
                    <div class="input-box">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" placeholder="Digite seu email" required>
                    </div>

                    <div class="input-box">
                        <label for="password">Senha</label>
                        <input id="password" type="password" name="senha" placeholder="Digite sua senha" required>
                    </div>
                </div>

                <div class="continue-button">
                    <button type="submit">Entrar</button>
                </div>

            </form>
        </div>

    </div>

</body>
</html>
