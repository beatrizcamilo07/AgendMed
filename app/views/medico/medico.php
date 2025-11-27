<?php
session_start();
if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'medico') {
    header("Location: /agendmed/public/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel do Médico</title>

    <link rel="stylesheet" href="/agendmed/assets/css/medico.css">
    <script src="/agendmed/assets/js/medico.js" defer></script>
</head>

<body>

<h1>Bem-vindo, Dr(a). <?= $_SESSION['usuario_nome'] ?></h1>

<section>
    <h2>Consultas de Hoje</h2>
    <table id="tabelaHoje">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Hora</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</section>

<section>
    <h2>Consultas Futuras</h2>
    <table id="tabelaFuturas">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Data</th>
                <th>Hora</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</section>

<section>
    <h2>Meus Horários</h2>

    <form id="formHorario">
        <select name="dia_semana">
            <option value="Segunda">Segunda</option>
            <option value="Terça">Terça</option>
            <option value="Quarta">Quarta</option>
            <option value="Quinta">Quinta</option>
            <option value="Sexta">Sexta</option>
        </select>

        <input type="time" name="hora_inicio">
        <input type="time" name="hora_fim">

        <button type="submit">Adicionar</button>
    </form>

    <table id="tabelaHorarios">
        <thead>
            <tr>
                <th>Dia</th>
                <th>Início</th>
                <th>Fim</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</section>

</body>
</html>
