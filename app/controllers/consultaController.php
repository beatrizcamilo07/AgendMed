<?php
session_start();

require_once "../config/conexao.php";
require_once "../models/consulta.php";

$database = new Database();
$db = $database->getConnection();

$model = new Consulta($db);

$action = $_GET['action'] ?? '';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(["erro" => true, "mensagem" => "Não autenticado"]);
    exit;
}

$idPaciente = $_SESSION['id_usuario'];

switch ($action) {

    case 'agendar':
        $idMedico = $_POST['id_medico'] ?? null;
        $data     = $_POST['data'] ?? null;
        $hora     = $_POST['hora'] ?? null;

        $ok = $model->agendar($idPaciente, $idMedico, $data, $hora);

        echo json_encode([
            "mensagem" => $ok ? "Consulta agendada!" : "Erro ao agendar consulta."
        ]);
        break;

    case 'listar':
        echo json_encode($model->listar($idPaciente));
        break;

    case 'historico':
        echo json_encode($model->historico($idPaciente));
        break;

    default:
        echo json_encode(["erro" => true, "mensagem" => "Ação inválida"]);
}
