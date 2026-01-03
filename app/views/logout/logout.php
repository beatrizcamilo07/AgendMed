<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteção básica
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?url=login/index');
    exit;
}

$nome = $_SESSION['usuario']['nome'] ?? 'usuário';
$tipo = $_SESSION['user_tipo'] ?? 'paciente';

// Dashboard correto por tipo
switch ($tipo) {
    case 'paciente':
        $dashboard = 'index.php?url=paciente/dashboardPaciente';
        break;
    case 'medico':
        $dashboard = 'index.php?url=medico/dashboardMedico';
        break;
    case 'administrador':
        $dashboard = 'index.php?url=administrador/dashboardAdministrador';
        break;
    default:
        $dashboard = 'index.php?url=login/index';
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="public/css/sair.css">
  <title>Encerrar Sessão</title>
</head>

<body>
  <!-- ======= MENU HEADER ======= -->
  <header class="header">
    <div class="logo">
      <img src="public/assets/img/estetoscópio-agendmed.png" alt="logo AgendMed">
      <span>AgendMed</span>
    </div>
    <nav class="menu">
      <a href="<?= $dashboard ?>"><img src="public/assets/img/house.png" alt="Início"> Início</a>
      <a href="#"><img src="public/assets/img/user.png" alt="Perfil"> Perfil</a>
      <a href="#"><img src="public/assets/img/folder (2).png" alt="Serviços"> Serviços</a>
      <a href="#"><img src="public/assets/img/about us.png" alt="Sobre Nós"> Sobre nós</a>
      <a href="#"><img src="public/assets/img/help.png" alt="Ajuda"> Ajuda</a>
      <a href="index.php?url=logout/index"><img src="public/assets/img/exit.png" alt="Sair">Sair</a>
    </nav>
  </header>

  <!-- ======= CONTEÚDO ======= -->
  <div class="container">
    <div class="form-image">
      <img src="public/assets/img/undraw_medicine_hqqg.svg" alt="Ilustração Médica">
    </div>

    <div class="form">
      <div class="form-header">
        <div class="title">
          <h1>Encerrar Sessão</h1>
        </div>
      </div>

      <p class="descricao">
        Tem certeza que deseja sair da sua conta, <strong><?= htmlspecialchars($nome) ?></strong>? <br>
        Você poderá acessar novamente a qualquer momento fazendo login.
      </p>

      <div class="buttons">
        <!-- CONFIRMA LOGOUT -->
        <a href="index.php?url=login/logout" class="btn sair">Sair</a>

        <!-- CANCELA -->
        <a href="<?= $dashboard ?>" class="btn cancelar">Voltar</a>
      </div>
    </div>
  </div>
</body>

</html>