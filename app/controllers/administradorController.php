<?php
require_once __DIR__ . '/../models/Consulta.php';

class AdministradorController
{
    private ConsultaModel $consultaModel;

    public function __construct(PDO $db)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'administrador') {
            header('Location: index.php?url=login/index');
            exit;
        }

        $this->consultaModel = new ConsultaModel($db);
    }

    // DASHBOARD
    public function dashboardAdministrador()
    {
        $consultas = $this->consultaModel->getAllConsultasDetalhadas();
        require __DIR__ . '/../views/administrador/dashboard-adm.php';
    }

    // AGENDAR CONSULTA
    public function agendar()
    {
        if (empty($_POST['id_paciente']) || empty($_POST['id_horario'])) {
            $_SESSION['mensagem_erro'] = 'Dados incompletos.';
            header('Location: index.php?url=administrador/dashboardAdministrador');
            exit;
        }

        $resultado = $this->consultaModel->criarAgendamentoPorHorario(
            $_POST['id_paciente'],
            $_POST['id_horario']
        );

        $_SESSION['mensagem_sucesso'] = $resultado['sucesso']
            ? 'Consulta agendada com sucesso.'
            : ($resultado['erro'] ?? 'Erro ao agendar');

        header('Location: index.php?url=administrador/dashboardAdministrador');
        exit;
    }

    // CANCELAR CONSULTA
    public function cancelarConsulta($idConsulta)
    {
        $this->consultaModel->cancelarConsulta($idConsulta);
        header('Location: index.php?url=administrador/dashboardAdministrador');
        exit;
    }

    // MARCAR CONSULTA COMO REALIZADA
    public function realizarConsulta($idConsulta)
    {
        $this->consultaModel->realizarConsulta($idConsulta);
        header('Location: index.php?url=administrador/dashboardAdministrador');
        exit;
    }

    // LOGOUT
    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?url=login/index');
        exit;
    }
}
?>

