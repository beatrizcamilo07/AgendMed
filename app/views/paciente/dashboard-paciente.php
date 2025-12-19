<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Proteção do dashboard
if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'paciente') {
    header('Location: index.php?url=login/index');
    exit;
}

// Nome do paciente vindo da sessão ou controller
$nomePaciente = $nomePaciente ?? ($_SESSION['user_nome'] ?? 'Paciente');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/dashboard-paciente.css">
    <title>Dashboard do Paciente - AgendMed</title>
</head>
<body>

<div class="container">

    <!-- Cabeçalho -->
    <header class="header">
        <div class="logo">
            <img src="public/assets/img/estetoscópio-agendmed.png" alt="AgendMed">
            <span>AgendMed</span>
        </div>
        <nav class="menu">
            <a href="index.php?url=paciente/dashboard"><img src="public/assets/img/house.png" alt="Início"> Início</a>
            <a href="#"><img src="public/assets/img/user.png" alt="Perfil"> Perfil</a>
            <a href="#"><img src="public/assets/img/folder (2).png" alt="Serviços"> Serviços</a>
            <a href="#"><img src="public/assets/img/about us.png" alt="Sobre nós"> Sobre nós</a>
            <a href="#"><img src="public/assets/img/help.png" alt="Ajuda"> Ajuda</a>
            <a href="index.php?url=paciente/logout"><img src="public/assets/img/exit.png" alt="Sair"> Sair</a>
        </nav>
    </header>

    <!-- Conteúdo principal -->
    <main class="main">

        <!-- Banner -->
        <section class="banner">
            <div class="greeting-banner">
                <h2>Olá, <?= htmlspecialchars($nomePaciente) ?>!</h2>
                <p>Bem-vindo ao AgendMed, onde cuidamos de cada detalhe do seu agendamento.</p>
            </div>
        </section>

        <section class="cards-container">

            <!-- Consultas Marcadas -->
            <div class="card consultas-marcadas">
                <h2>Consultas Marcadas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Médico</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Especialidade</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($consultasMarcadas)): ?>
                            <tr>
                                <td colspan="6" style="text-align:center">Nenhuma consulta agendada.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($consultasMarcadas as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['nome_medico']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($c['data_hora_inicio'])) ?></td>
                                    <td><?= date('H:i', strtotime($c['data_hora_inicio'])) ?></td>
                                    <td><?= htmlspecialchars($c['especialidade']) ?></td>
                                    <td><?= htmlspecialchars($c['status_consulta']) ?></td>
                                    <td>
                                        <button onclick="cancelarConsulta(<?= (int)$c['id'] ?>)">Cancelar</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- Agendar Consulta -->
            <div class="card formAgendamento">
                <h2>Agendar Consulta</h2>
                <form id="form-agendamento">
                    <label>Médico / Especialidade</label>
                    <select name="id_medico" id="id_medico" required>
                        <option value="">Selecione</option>
                        <?php foreach ($listaMedicos as $m): ?>
                            <option value="<?= (int)$m['id_med'] ?>">
                                <?= htmlspecialchars($m['especialidade']) ?> - Dr(a). <?= htmlspecialchars($m['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Data</label>
                    <input type="date" id="data" name="data" required>

                    <label>Hora</label>
                    <select name="hora" id="hora" required>
                        <option value="">Selecione a hora</option>
                    </select>

                    <button type="submit">Agendar</button>
                </form>
            </div>

            <!-- Histórico -->
            <div class="card historico">
                <h2>Histórico de Consultas</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Médico</th>
                            <th>Data</th>
                            <th>Hora</th>
                            <th>Especialidade</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($historicoConsultas)): ?>
                            <tr>
                                <td colspan="5" style="text-align:center">Nenhum histórico encontrado.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($historicoConsultas as $h): ?>
                                <tr>
                                    <td><?= htmlspecialchars($h['nome_medico']) ?></td>
                                    <td><?= date('d/m/Y', strtotime($h['data_hora_inicio'])) ?></td>
                                    <td><?= date('H:i', strtotime($h['data_hora_inicio'])) ?></td>
                                    <td><?= htmlspecialchars($h['especialidade']) ?></td>
                                    <td><?= htmlspecialchars($h['status_consulta']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </section>
    </main>
</div>

<script src="public/javascript/script-paciente.js"></script>
</body>
</html>
