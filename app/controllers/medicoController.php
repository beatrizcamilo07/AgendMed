<?php
require_once __DIR__ . '/../models/Medico.php';
require_once __DIR__ . '/../models/Consulta.php';

class MedicoController {
    private $db;
    private $medicoModel;
    private $consultaModel;

    public function __construct($db) {
        $this->db = $db;
        $this->medicoModel   = new MedicoModel($this->db);
        $this->consultaModel = new ConsultaModel($this->db);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    // Dashboard do médico
    public function dashboardMedico() {
        if (!isset($_SESSION['user_id'], $_SESSION['user_tipo']) || $_SESSION['user_tipo'] !== 'medico') {
            header("Location: index.php?url=login/index");
            exit();
        }

        $idUser = $_SESSION['user_id'];
        $dadosMedico = $this->medicoModel->getDadosMedicoCompleto($idUser);
        if (!$dadosMedico) {
            header("Location: index.php?url=login/index");
            exit();
        }

        $id_med = $dadosMedico['id_med'];

        // Buscar consultas do médico
        $consultasHoje    = $this->consultaModel->getConsultasPorMedico($id_med, 'hoje');
        $consultasFuturas = $this->consultaModel->getConsultasPorMedico($id_med, 'futuras');

        require __DIR__ . '/../views/medico/dashboard-med.php';
    }

    // Atualizar status de atendimento
    public function atualizarAtendimento() {
        if (!isset($_GET['id'], $_GET['acao']) || $_SESSION['user_tipo'] !== 'medico') {
            header("Location: index.php?url=login/index");
            exit();
        }

        $idConsulta = (int) $_GET['id'];
        $acao = $_GET['acao'];

        $status = null;
        if ($acao === 'realizado') {
            $status = ConsultaModel::STATUS_ATENDIMENTO_REALIZADO;
        } elseif ($acao === 'nao_realizado') {
            $status = ConsultaModel::STATUS_ATENDIMENTO_NAO_REALIZADO;
        } else {
            header("Location: index.php?url=login/index");
            exit();
        }

        $resultado = $this->consultaModel->atualizarStatusAtendimento($idConsulta, $status);

        $_SESSION['mensagem_sucesso'] = $resultado ? "Atualizado com sucesso." : "Erro ao atualizar status";
        header("Location: index.php?url=medico/dashboardMedico");
        exit;
    }

    // Logout do médico
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();

        header("Location: index.php?url=login/index");
        exit();
    }
}
?>