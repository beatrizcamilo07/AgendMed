<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Consulta.php';

class ConsultaController
{
    private PDO $db;
    private ConsultaModel $consultaModel;

    public function __construct()
    {
        $this->db = Database::conectar();
        $this->consultaModel = new ConsultaModel($this->db);
    }

    // Retorna os horários disponíveis de um médico (JSON)
    public function horariosPorMedico()
    {
        $idMedico = $_GET['id_medico'] ?? null;
        $data = $_GET['data'] ?? null;

        if (!$idMedico || !$data) {
            echo json_encode([]);
            return;
        }

        $horarios = $this->consultaModel->getHorariosDisponiveisPorMedico((int)$idMedico, $data);
        echo json_encode($horarios);
    }

    // Cria agendamento via POST
    public function agendar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $idPaciente = $_SESSION['user_id'];
            $idHorario = $_POST['id_horario'] ?? null;

            if (!$idHorario) {
                echo json_encode(['sucesso' => false, 'erro' => 'Horário inválido']);
                return;
            }

            $resultado = $this->consultaModel->criarAgendamentoPorHorario($idPaciente, (int)$idHorario);
            echo json_encode($resultado);
        }
    }

    // Cancelar consulta
    public function cancelar()
    {
        $idConsulta = $_GET['id'] ?? null;
        if (!$idConsulta) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID inválido']);
            return;
        }

        $this->consultaModel->cancelarConsulta((int)$idConsulta);
        echo json_encode(['sucesso' => true]);
    }
}

// ------------------
// Roteamento simples
// ------------------
$controller = new ConsultaController();

$url = $_GET['url'] ?? '';
switch($url) {
    case 'consulta/horariosPorMedico':
        $controller->horariosPorMedico();
        break;
    case 'consulta/agendar':
        $controller->agendar();
        break;
    case 'consulta/cancelar':
        $controller->cancelar();
        break;
    default:
        header('HTTP/1.0 404 Not Found');
        echo 'Página não encontrada';
        break;
}