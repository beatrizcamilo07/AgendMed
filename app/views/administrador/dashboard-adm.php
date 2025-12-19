<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteção do dashboard
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'administrador') {
    header('Location: index.php?url=login/index');
    exit;
}

$nome_admin = $nome_admin ?? ($_SESSION['user_nome'] ?? 'Administrador');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="public/css/dashboard-adm.css">
  <title>Dashboard Administrador - AgendMed</title>
</head>
<body>
<div class="container"> 
  <!-- Cabeçalho -->
  <header class="header">
    <div class="logo">
      <img src="public/assets/img/estetoscópio-agendmed.png" alt="estetoscópio AgendMed">
      <span>AgendMed</span>
    </div>
    <nav class="menu">
      <a href="#"><img src="public/assets/img/house.png" alt="House"> Início</a>
      <a href="#"><img src="public/assets/img/user.png" alt="User"> Perfil</a>
      <a href="#"><img src="public/assets/img/notification.png" alt="notification"> Notificação</a>
      <a href="#"><img src="public/assets/img/folder (2).png" alt="Folder"> Serviços</a>
      <a href="#"><img src="public/assets/img/about us.png" alt="Sobre nós"> Sobre nós</a>
      <a href="#"><img src="public/assets/img/help.png" alt="Ajuda"> Ajuda</a>
      <a href="index.php?url=administrador/logout"><img src="public/assets/img/exit.png" alt="Sair"> Sair</a>
    </nav>
  </header> 

  <!-- Conteúdo Principal -->
  <main class="main">
    <!-- Banner -->
    <section class="banner">
      <div class="greeting-banner">
        <h2>Olá, <?= htmlspecialchars($nome_admin) ?> (Admin)</h2>
        <p>Aqui você pode gerenciar agendamentos e serviços da clínica</p>
      </div>
    </section>

    <!-- Área dos Cards -->
    <section class="cards-container">
      <!-- Card de Consultas Agendadas -->
      <div class="card consultas-marcadas">
        <div class="form-header"><h2>Consultas Agendadas</h2></div>
        <table>
          <thead>
            <tr>
              <th>Paciente</th>
              <th>Data</th>
              <th>Hora</th>
              <th>Especialidade (Médico)</th>
              <th>Status</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody id="tbody-agendamentos">
            <?php if(!empty($consultas)): ?>
              <?php foreach($consultas as $c): ?>
                <tr>
                  <td><?= htmlspecialchars($c['nome_paciente']) ?></td>
                  <td><?= htmlspecialchars($c['data']) ?></td>
                  <td><?= htmlspecialchars($c['hora']) ?></td>
                  <td><?= htmlspecialchars($c['especialidade'] . ' (' . $c['nome_medico'] . ')') ?></td>
                  <td><?= htmlspecialchars($c['status']) ?></td>
                  <td>
                    <button class="editar" data-id="<?= $c['id'] ?>">Editar</button>
                    <button class="excluir" data-id="<?= $c['id'] ?>">Excluir</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="6">Nenhuma consulta encontrada.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

      <!-- Card do Formulário de Agendamento -->
      <div class="card formAgendamento">
        <div class="form-header"><h2 id="form-agendamento-titulo">Agendar Consulta</h2></div>
        <form id="form-agendamento-admin" method="POST" action="index.php?url=administrador/agendar">
          <input type="hidden" id="edit-id-agendamento" name="id_agendamento">
          <div class="input-group">
            <div class="input-box">
              <label for="paciente">Nome do Paciente</label>
              <select id="paciente" name="id_paciente" required>
                <option value="">Selecione...</option>
                <?php if(!empty($pacientes)): ?>
                  <?php foreach($pacientes as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nome']) ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="input-box">
              <label for="especialidade">Especialidade e Médico</label>
              <select id="especialidade" name="id_medico" required>
                <option value="">Selecione...</option>
                <?php if(!empty($medicos)): ?>
                  <?php foreach($medicos as $m): ?>
                    <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['especialidade'] . ' (' . $m['nome'] . ')') ?></option>
                  <?php endforeach; ?>
                <?php endif; ?>
              </select>
            </div>
            <div class="input-box">
              <label for="data">Data</label>
              <input type="date" id="data" name="data" required>
            </div>
            <div class="input-box">
              <label for="hora">Hora</label>
              <input type="time" id="hora" name="hora" required>
            </div>
            <div class="input-box" id="status-box" style="display:none;">
              <label for="status">Status</label>
              <select id="status" name="status">
                <option value="confirmado">Confirmado</option>
                <option value="concluido">Concluído</option>
                <option value="cancelado">Cancelado</option>
              </select>
            </div>
          </div>
          <div class="continue-button">
            <button type="submit" id="btn-submit-agendamento">Agendar</button>
            <button type="button" id="btn-cancelar-edicao-agendamento" class="cancel-btn" style="display:none;">Cancelar Edição</button>
          </div>
        </form>
      </div>
    </section>

    <!-- Área de Gerenciar Serviços -->
    <section class="servicos-container">
      <div class="card">
        <div class="form-header"><h2>Gerenciar Serviços (Especialidades)</h2></div>
        <form id="formServico" method="POST" action="index.php?url=administrador/servico">
          <input type="hidden" id="edit-id-servico" name="id">
          <div class="input-group">
            <div class="input-box">
              <label for="nomeServico">Nome do Serviço</label>
              <input type="text" id="nomeServico" name="nome_servico" placeholder="Ex: Cardiologia" required>
            </div>
            <div class="input-box">
              <label for="descricaoServico">Descrição</label>
              <input type="text" id="descricaoServico" name="descricao" placeholder="Digite uma breve descrição">
            </div>
          </div>
          <div class="continue-button">
            <button type="submit" id="btn-submit-servico">Cadastrar Serviço</button>
            <button type="button" id="btn-cancelar-edicao-servico" class="cancel-btn" style="display:none;">Cancelar Edição</button>
          </div>
        </form>
        <hr style="margin:20px 0; border:1px solid #eee;">
        <table>
          <thead>
            <tr>
              <th>Serviço</th>
              <th>Descrição</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody id="listaServicos">
            <?php if(!empty($servicos)): ?>
              <?php foreach($servicos as $s): ?>
                <tr>
                  <td><?= htmlspecialchars($s['nome_servico']) ?></td>
                  <td><?= htmlspecialchars($s['descricao']) ?></td>
                  <td>
                    <button class="editar-servico" data-id="<?= $s['id'] ?>">Editar</button>
                    <button class="excluir-servico" data-id="<?= $s['id'] ?>">Excluir</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="3">Nenhum serviço cadastrado.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>
<script src="javascript/script-adm.js"></script>
</body>
</html>