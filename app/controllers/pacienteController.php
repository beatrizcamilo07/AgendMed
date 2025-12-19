<?php
// Carrega os models manualmente (porque PHP não adivinha nada)
require_once __DIR__ . '/../models/Consulta.php';
require_once __DIR__ . '/../models/Medico.php';

class PacienteController
{
    private ConsultaModel $consultaModel;
    private MedicoModel $medicoModel;

    public function __construct(PDO $db)
    {
        $this->consultaModel = new ConsultaModel($db);
        $this->medicoModel   = new MedicoModel($db);
    }

    public function dashboardPaciente()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $idPaciente   = $_SESSION['user_id'];
        $nomePaciente = $_SESSION['user_nome'] ?? '';

        $consultasMarcadas  = $this->consultaModel->getConsultasPorPaciente($idPaciente, 'futuras');
        $historicoConsultas = $this->consultaModel->getConsultasPorPaciente($idPaciente, 'passadas');
        $listaMedicos       = $this->medicoModel->listarMedicosComEspecialidade();

        require __DIR__ . '/../views/paciente/dashboard-paciente.php';
    }

    public function horariosDisponiveis()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        $idMed = $_GET['id_med'] ?? null;
        $data  = $_GET['data'] ?? null;

        if (!$idMed || !$data) {
            echo json_encode([]);
            exit;
        }

        $horarios = $this->consultaModel->getHorariosDisponiveisPorMedico($idMed, $data);
        echo json_encode($horarios);
        exit;
    }

    public function agendar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        header('Content-Type: application/json');

        $dados = json_decode(file_get_contents('php://input'), true);
        $idHorario = $dados['id_horario'] ?? null;

        if (!$idHorario) {
            echo json_encode([
                'sucesso' => false,
                'erro' => 'Horário inválido'
            ]);
            exit;
        }

        $resultado = $this->consultaModel->criarAgendamentoPorHorario(
            $_SESSION['user_id'],
            $idHorario
        );

        echo json_encode($resultado);
        exit;
    }
}