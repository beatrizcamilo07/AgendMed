<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Consulta.php';

class AdministradorController {
    private $consultaModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'administrador') {
            header("Location: index.php?url=login/index");
            exit();
        }

        $database = new Database();
        $db = $database->getConnection();
        $this->consultaModel = new ConsultaModel($db);
    }

    // Dashboard
    public function dashboardAdministrador() {
        $consultas = $this->consultaModel->getAllConsultasDetalhadas();
        require __DIR__ . '/../views/administrador/dashboard-adm.php';
    }

    // Agendar consulta
    public function agendar() {
        if (empty($_POST['id_paciente']) || empty($_POST['id_medico']) || empty($_POST['data']) || empty($_POST['hora'])) {
            $_SESSION['mensagem_erro'] = "Dados incompletos.";
            header("Location: index.php?url=administrador/dashboardAdministrador");
            exit;
        }

        $resultado = $this->consultaModel->criarAgendamentoPorHorario(
            $_POST['id_paciente'], $_POST['id_medico'], $_POST['data'], $_POST['hora']
        );

        if (isset($resultado['sucesso']) && $resultado['sucesso']) {
            $_SESSION['mensagem_sucesso'] = "Consulta agendada com sucesso.";
        } else {
            $_SESSION['mensagem_erro'] = $resultado['erro'] ?? "Erro ao agendar a consulta.";
        }

        header("Location: index.php?url=administrador/dashboardAdministrador");
        exit;
    }

    // Logout
    public function logout() {
        $_SESSION = [];
        session_destroy();
        header("Location: index.php?url=login/index");
        exit;
    }
}
?>
