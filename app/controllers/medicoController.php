<?php
session_start();

require_once "../config/conexao.php";
require_once "../models/medico.php";
require_once "../models/consulta.php";
require_once "../models/horarios.php";

class MedicoController
{
    private $db;
    private $medicoModel;
    private $consultaModel;
    private $horariosModel;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();

        $this->medicoModel = new Medico($this->db);
        $this->consultaModel = new Consulta($this->db);
        $this->horariosModel = new Horarios($this->db);
    }

    public function handle()
    {
        $action = $_GET['action'] ?? 'index';

        switch ($action) {

            case 'index':
                $this->index();
                break;

            case 'consultasHoje':
                echo json_encode(
                    $this->consultaModel->listarConsultasHoje($_SESSION['usuario_id'])
                );
                break;

            case 'consultasFuturas':
                echo json_encode(
                    $this->consultaModel->listarConsultasFuturas($_SESSION['usuario_id'])
                );
                break;

            case 'listarHorarios':
                echo json_encode(
                    $this->horariosModel->listarHorariosMedico($_SESSION['usuario_id'])
                );
                break;

            case 'adicionarHorario':
                $dia = $_POST['dia_semana'];
                $inicio = $_POST['hora_inicio'];
                $fim = $_POST['hora_fim'];

                $ok = $this->horariosModel->adicionarHorario(
                    $_SESSION['usuario_id'],
                    $dia,
                    $inicio,
                    $fim
                );

                echo json_encode([
                    "sucesso" => $ok,
                    "mensagem" => $ok ? "Horário adicionado!" : "Erro ao adicionar horário"
                ]);
                break;

            default:
                echo json_encode(["erro" => true, "mensagem" => "Ação inválida"]);
        }
    }

    private function index()
    {
        if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'medico') {
            header("Location: /agendmed/public/index.php");
            exit;
        }

        $nome_medico = $_SESSION['usuario_nome'];
        require __DIR__ . "/../views/medico/dashboard-medico.php";
    }
}

$controller = new MedicoController();
$controller->handle();
