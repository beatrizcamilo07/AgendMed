<?php
session_start();

// Proteção básica
if (!isset($_SESSION['usuario_nome']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    die("Acesso inválido.");
}

$nomeUsuario = $_SESSION['usuario_nome'];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS correto -->
    <link rel="stylesheet" href="/agendmed/css/dashboard-paciente.css">

    <title>Dashboard do Paciente - AgendMed</title>
</head>

<body>
    <div class="container">
        
        <header class="header">
            <div class="logo">
                <img src="/agendmed/public/assets/img/estetoscópio-agendmed.png" alt="estetoscópio AgendMed">
                <span>AgendMed</span>
            </div>

            <nav class="menu">
                <a href="paciente.php"><img src="/agendmed/public/assets/img/house.png" alt=""> Início</a>
                <a href="perfil.php"><img src="/agendmed/public/assets/img/user.png" alt=""> Perfil</a>
                <a href="notificacao.php"><img src="/agendmed/public/assets/img/notification.png" alt=""> Notificação</a>
                <a href="servicos.php"><img src="/agendmed/public/assets/img/folder (2).png" alt=""> Serviços</a>
                <a href="sobre-nos.php"><img src="/agendmed/public/assets/img/about us.png" alt=""> Sobre nós</a>
                <a href="ajuda.php"><img src="/agendmed/public/assets/img/help.png" alt=""> Ajuda</a>

                <!-- Logout -->
                <a href="/agendmed/app/controllers/LogoutController.php">
                    <img src="/agendmed/public/assets/img/exit.png" alt=""> Sair
                </a>
            </nav>
        </header>

        <main class="main">

            <section class="banner">
                <div class="greeting-banner">
                    <h2>Olá, <?php echo htmlspecialchars($nomeUsuario); ?>!</h2>
                    <p>Bem-vindo ao AgendMed, onde cuidamos de cada detalhe do seu agendamento.</p>
                </div>
            </section>

            <section class="cards-container">
                
                <!-- Consultas Marcadas -->
                <div class="card consultas-marcadas">
                    <div class="form-header">
                        <h2>Consultas Marcadas</h2>
                    </div>

                    <table id="tabelaAgendamentos">
                        <thead>
                            <tr>
                                <th>Médico</th>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Especialidade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- Agendar Consulta -->
                <div class="card formAgendamento">
                    <div class="form-header">
                        <h2>Agendar Consulta</h2>
                    </div>

                    <form id="form-agendamento">
                        <div class="input-group">

                            <div class="input-box">
                                <label for="especialidade">Especialidade e Médico</label>
                                <select id="especialidade" name="id_medico" required>
                                    <option value="">Carregando médicos...</option>
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

                        </div>

                        <div class="continue-button">
                            <button type="submit">Agendar</button>
                        </div>
                    </form>
                </div>

                <!-- Histórico -->
                <div class="card historico">
                    <div class="form-header">
                        <h2>Histórico de Consultas</h2>
                    </div>

                    <table id="tabelaHistorico">
                        <thead>
                            <tr>
                                <th>Médico</th>
                                <th>Data</th>
                                <th>Hora</th>
                                <th>Especialidade</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

            </section>
        </main>

    </div>

    <!-- JS correto -->
    <script src="/agendmed/public/javascript/script-paciente.js"></script>
</body>
</html>
