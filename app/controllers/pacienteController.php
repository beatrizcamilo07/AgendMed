<?php
require_once __DIR__ . '/../models/Consulta.php';
require_once __DIR__ . '/../models/Medico.php';

if (session_status() === PHP_SESSION_NONE) session_start();

class PacienteController {

    private PDO $db;
    private ConsultaModel $consultaModel;
    private MedicoModel $medicoModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->consultaModel = new ConsultaModel($this->db);
        $this->medicoModel   = new MedicoModel($this->db);
    }

    // Dashboard do paciente
    public function dashboardPaciente() {

        if (
            !isset($_SESSION['user_id'], $_SESSION['user_tipo']) ||
            $_SESSION['user_tipo'] !== 'paciente'
        ) {
            header('Location: index.php?url=login/index');
            exit;
        }

        $idPaciente   = $_SESSION['user_id'];
        $nomePaciente = $_SESSION['user_nome'];

        // Consultas do paciente
        $consultasMarcadas  = $this->consultaModel->getConsultasPorPaciente($idPaciente);
        $historicoConsultas = []; // pode separar por status depois

        // Lista de médicos
        $listaMedicos = $this->medicoModel->listarMedicosComEspecialidade();

        require __DIR__ . '/../views/paciente/dashboard-paciente.php';
    }

    // Retorna horários disponíveis de um médico (JSON)
    public function horariosPorMedico() {

        $idMedico = $_GET['id_medico'] ?? null;
        $data     = $_GET['data'] ?? null;

        if (!$idMedico || !$data) {
            echo json_encode([]);
            return;
        }

        $horarios = $this->consultaModel
            ->getHorariosDisponiveisPorMedico((int)$idMedico, $data);

        echo json_encode($horarios);
    }

    // Agendar consulta via POST
    public function agendar() {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $idPaciente = $_SESSION['user_id'];
            $idHorario  = $_POST['id_horario'] ?? null;

            if (!$idHorario) {
                echo json_encode(['sucesso' => false, 'erro' => 'Horário inválido']);
                return;
            }

            $resultado = $this->consultaModel
                ->criarAgendamentoPorHorario($idPaciente, (int)$idHorario);

            echo json_encode($resultado);
        }
    }

    // Cancelar consulta
    public function cancelar() {

        $idConsulta = $_GET['id'] ?? null;

        if (!$idConsulta) {
            echo json_encode(['sucesso' => false, 'erro' => 'ID inválido']);
            return;
        }

        $this->consultaModel->cancelarConsulta((int)$idConsulta);
        echo json_encode(['sucesso' => true]);
    }
}