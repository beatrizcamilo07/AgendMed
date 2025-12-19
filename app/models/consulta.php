<?php
class ConsultaModel
{
    private PDO $conn;

    const STATUS_CONSULTA_AGENDADA = 'agendada';
    const STATUS_CONSULTA_CANCELADA = 'cancelada';
    const STATUS_ATENDIMENTO_NAO_REALIZADO = 'nao_realizado';
    const STATUS_ATENDIMENTO_REALIZADO = 'realizado';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // =========================
    // CONSULTAS DO PACIENTE
    // =========================
    public function getConsultasPorPaciente($idPaciente, $tipo = 'futuras')
    {
        $operador = ($tipo === 'futuras') ? '>=' : '<';
        $ordem    = ($tipo === 'futuras') ? 'ASC' : 'DESC';

        $sql = "
            SELECT 
                c.id,
                c.status_consulta,
                h.data_hora_inicio,
                u.nome AS nome_medico,
                e.nome AS especialidade
            FROM consultas c
            JOIN horario h ON c.id_horario = h.id_horario
            JOIN medico m ON h.id_med = m.id_med
            JOIN usuario u ON m.id_user = u.id_user
            JOIN especialidades e ON m.id_especialidade = e.id
            WHERE c.id_paciente = :paciente
              AND h.data_hora_inicio {$operador} NOW()
            ORDER BY h.data_hora_inicio {$ordem}
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':paciente' => $idPaciente]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // CONSULTAS DO MÉDICO
    // =========================
    public function getConsultasPorMedico($idMedico, $tipo = 'futuras')
    {
        $operador = ($tipo === 'futuras') ? '>=' : '<';
        $ordem    = ($tipo === 'futuras') ? 'ASC' : 'DESC';

        $sql = "
            SELECT 
                c.id,
                c.status_consulta,
                h.data_hora_inicio,
                u.nome AS nome_paciente
            FROM consultas c
            JOIN horario h ON c.id_horario = h.id_horario
            JOIN usuario u ON c.id_paciente = u.id_user
            WHERE h.id_med = :medico
              AND h.data_hora_inicio {$operador} NOW()
            ORDER BY h.data_hora_inicio {$ordem}
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':medico' => $idMedico]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // HORÁRIOS DISPONÍVEIS
    // =========================
    public function getHorariosDisponiveisPorMedico($idMedico, $data)
    {
        $sql = "
            SELECT 
                id_horario,
                TIME(data_hora_inicio) AS hora
            FROM horario
            WHERE id_med = :medico
              AND DATE(data_hora_inicio) = :data
              AND status = 'disponivel'
            ORDER BY data_hora_inicio ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':medico' => $idMedico,
            ':data'   => $data
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // AGENDAR CONSULTA
    // =========================
    public function criarAgendamentoPorHorario($idPaciente, $idHorario)
    {
        try {
            $this->conn->beginTransaction();

            $sqlCheck = "
                SELECT id_horario
                FROM horario
                WHERE id_horario = :horario
                  AND status = 'disponivel'
                FOR UPDATE
            ";
            $stmt = $this->conn->prepare($sqlCheck);
            $stmt->execute([':horario' => $idHorario]);

            if (!$stmt->fetch()) {
                $this->conn->rollBack();
                return ['sucesso' => false, 'erro' => 'Horário indisponível'];
            }

            $sqlInsert = "
                INSERT INTO consultas (id_paciente, id_horario, status_consulta)
                VALUES (:paciente, :horario, :status)
            ";
            $stmt = $this->conn->prepare($sqlInsert);
            $stmt->execute([
                ':paciente' => $idPaciente,
                ':horario'  => $idHorario,
                ':status'   => self::STATUS_CONSULTA_AGENDADA
            ]);

            $sqlUpdate = "
                UPDATE horario
                SET status = 'indisponivel'
                WHERE id_horario = :horario
            ";
            $stmt = $this->conn->prepare($sqlUpdate);
            $stmt->execute([':horario' => $idHorario]);

            $this->conn->commit();
            return ['sucesso' => true];

        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['sucesso' => false, 'erro' => 'Erro ao agendar'];
        }
    }

    // =========================
    // ATUALIZAR STATUS DE ATENDIMENTO
    // =========================
    public function atualizarStatusAtendimento($idConsulta, $status)
    {
        $sql = "
            UPDATE consultas
            SET status_consulta = :status
            WHERE id = :idConsulta
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':status'     => $status,
            ':idConsulta' => $idConsulta
        ]);

        return $stmt->rowCount() > 0;
    }
}
?>