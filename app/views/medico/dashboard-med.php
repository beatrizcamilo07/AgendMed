<?php
// Inicia a sessão apenas se ainda não estiver ativa
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteção do dashboard do médico
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'medico') {
    header('Location: index.php?url=login/index');
    exit;
}

// Nome do médico vindo da sessão ou controller
$nome_medico = $nome_medico ?? ($_SESSION['user_nome'] ?? 'Médico');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="public/css/dashbord-med.css">
  <title>Dashboard Médico - AgendMed</title>
</head>
<body>
  <div class="container">
    <!-- Cabeçalho único -->
    <header class="header">
      <div class="logo">
        <img src="public/assets/img/estetoscópio-agendmed.png" alt="logo AgendMed">
        <span>AgendMed</span>
      </div>
      <nav class="menu">
        <a href="index.php?url=medico/dashboardMedico"><img src="public/assets/img/house.png" alt="House"> Início</a>
        <a href="#"><img src="public/assets/img/user.png" alt="User"> Perfil</a>
        <a href="#"><img src="public/assets/img/folder (2).png" alt="Folder"> Serviços</a>
        <a href="#"><img src="public/assets/img/about us.png" alt="Sobre nós"> Sobre nós</a>
        <a href="ajuda.html"><img src="public/assets/img/help.png" alt="Ajuda"> Ajuda</a>
        <a href="index.php?url=logout"><img src="public/assets/img/exit.png" alt="Sair"> Sair</a>
      </nav>
    </header>

    <!-- Conteúdo -->
    <main class="main">
      <section class="banner">
        <div class="greeting-banner">
          <h2>Olá, Dr(a). <?= htmlspecialchars($nome_medico) ?></h2>
          <p>Aqui você pode gerenciar sua agenda de atendimentos</p>
        </div>
      </section>

      <section class="cards-container">
        <!-- Consultas do Dia -->
        <div class="card">
          <div class="form-header">
            <h2>Consultas de Hoje</h2>
          </div>
          <table>
            <thead>
              <tr>
                <th>Paciente</th>
                <th>Hora</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody id="consultas-hoje">
              <tr>
                <td colspan="4" class="empty-message">Nenhuma consulta agendada para hoje</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Consultas Futuras -->
        <div class="card">
          <div class="form-header">
            <h2>Consultas Futuras</h2>
          </div>
          <table>
            <thead>
              <tr>
                <th>Paciente</th>
                <th>Data</th>
                <th>Hora</th>
                <th>Status</th>
                <th>Ações</th>
              </tr>
            </thead>
            <tbody id="consultas-futuras">
              <tr>
                <td colspan="5" class="empty-message">Nenhuma consulta futura encontrada</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
  <script src="javascript/script-medico.js"></script>
</body>
</html>