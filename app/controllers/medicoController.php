<?php
require_once __DIR__ . '/../models/Medico.php';
require_once __DIR__ . '/../models/Consulta.php';

class MedicoController {
    private PDO $db;
    private MedicoModel $medicoModel;
    private ConsultaModel $consultaModel;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->medicoModel = new MedicoModel($this->db);
        $this->consultaModel = new ConsultaModel($this->db);
        if (session_status() === PHP_SESSION_NONE) session_start();
    }

    // Dashboard do médico
    public function dashboardMedico() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_tipo'] !== 'medico') {
            header('Location: index.php?url=login/index');
            exit;
        }

        $idUser = $_SESSION['user_id'];
        $dadosMedico = $this->medicoModel->getDadosMedicoCompleto($idUser);
        if (!$dadosMedico) {
            header('Location: index.php?url=login/index');
            exit;
        }

        $id_med = $dadosMedico['id_med'];

        // Consultas do médico
        $consultasHoje    = $this->consultaModel->getConsultasPorMedico($id_med, 'hoje');
        $consultasFuturas = $this->consultaModel->getConsultasPorMedico($id_med, 'futuras');

        require __DIR__ . '/../views/medico/dashboard-med.php';
    }

    // Atualizar status da consulta
    public function atualizarAtendimento() {
        if (!isset($_GET['id'], $_GET['acao']) || $_SESSION['user_tipo'] !== 'medico') {
            header('Location: index.php?url=login/index');
            exit;
        }

        $idConsulta = (int) $_GET['id'];
        $acao = $_GET['acao'];

        $status = null;
        if ($acao === 'finalizada') {
            $status = ConsultaModel::STATUS_FINALIZADA;
        } elseif ($acao === 'cancelada') {
            $status = ConsultaModel::STATUS_CANCELADA;
        } else {
            header('Location: index.php?url=medico/dashboardMedico');
            exit;
        }

        $this->consultaModel->atualizarStatusAtendimento($idConsulta, $status);

        $_SESSION['mensagem_sucesso'] = "Status atualizado com sucesso";
        header('Location: index.php?url=medico/dashboardMedico');
        exit;
    }

    // Logout
    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?url=login/index');
        exit;
    }
}
?>