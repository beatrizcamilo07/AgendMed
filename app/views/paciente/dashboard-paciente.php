<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'paciente') {
    header('Location: index.php?url=login/index');
    exit;
}

$nomePaciente = $nomePaciente ?? ($_SESSION['user_nome'] ?? 'Paciente');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard do Paciente - AgendMed</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/dashboard-paciente.css">
</head>
<body>

<div class="container">

    <header class="header">
        <div class="logo">
            <span>AgendMed</span>
        </div>

        <nav class="menu">
            <a href="index.php?url=paciente/dashboard-paciente">Início</a>
            <a href="index.php?url=login/logout">Sair</a>
        </nav>
    </header>

    <main class="main">

        <section class="banner">
            <h2>Olá, <?= htmlspecialchars($nomePaciente) ?></h2>
            <p>Gerencie suas consultas</p>
        </section>

        <section class="cards-container">

            <!-- CONSULTAS MARCADAS -->
            <div class="card">
                <h2>Consultas Marcadas</h2>

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
                    <?php if (empty($consultasMarcadas)): ?>
                        <tr>
                            <td colspan="5" style="text-align:center">
                                Nenhuma consulta agendada
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($consultasMarcadas as $c): ?>
                            <tr>
                                <td><?= htmlspecialchars($c['nome_medico']) ?></td>
                                <td><?= date('d/m/Y', strtotime($c['data_consulta'])) ?></td>
                                <td><?= date('H:i', strtotime($c['hora_consulta'])) ?></td>
                                <td><?= htmlspecialchars($c['especialidade']) ?></td>
                                <td><?= ucfirst($c['status_consulta']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- AGENDAR CONSULTA -->
            <div class="card">
                <h2>Agendar Consulta</h2>

                <form id="form-agendamento">

                    <label>Médico / Especialidade</label>
                    <select id="id_medico" required>
                        <option value="">Selecione</option>
                        <?php foreach ($listaMedicos as $m): ?>
                            <option value="<?= (int)$m['id_med'] ?>">
                                <?= htmlspecialchars($m['especialidade']) ?> -
                                Dr(a). <?= htmlspecialchars($m['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <label>Data</label>
                    <input
                        type="date"
                        id="data"
                        required
                        min="<?= date('Y-m-d') ?>"
                    >

                    <label>Horário</label>
                    <select id="hora" required>
                        <option value="">Selecione a data primeiro</option>
                    </select>

                    <p id="resumo-horario" style="font-size:0.9rem;color:#555"></p>

                    <button type="submit">Agendar</button>
                </form>
            </div>

            <!-- HISTÓRICO -->
            <div class="card">
                <h2>Histórico</h2>

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
                            <td colspan="5" style="text-align:center">
                                Nenhuma consulta no histórico
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($historicoConsultas as $h): ?>
                            <tr>
                                <td><?= htmlspecialchars($h['nome_medico']) ?></td>
                                <td><?= date('d/m/Y', strtotime($h['data_consulta'])) ?></td>
                                <td><?= date('H:i', strtotime($h['hora_consulta'])) ?></td>
                                <td><?= htmlspecialchars($h['especialidade']) ?></td>
                                <td><?= ucfirst($h['status_consulta']) ?></td>
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