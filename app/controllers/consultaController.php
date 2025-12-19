<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Consulta.php';

class ConsultaController
{
    private PDO $db;
    private ConsultaModel $consultaModel;

    public function __construct()
    {
        $this->db = Database::conectar(); // Ajuste conforme seu Database.php
        $this->consultaModel = new ConsultaModel($this->db);
    }

    // Retorna os horários disponíveis de um médico (JSON)
    public function horariosPorMedico()
    {
        $idMedico = $_GET['id_medico'] ?? null;
        if (!$idMedico) {
            echo json_encode([]);
            return;
        }

        $horarios = $this->consultaModel->getHorariosDisponiveisPorMedico((int)$idMedico);
        echo json_encode($horarios);
    }
}

// Roteamento simples
if (isset($_GET['url']) && $_GET['url'] === 'consulta/horariosPorMedico') {
    $controller = new ConsultaController();
    $controller->horariosPorMedico();
}
