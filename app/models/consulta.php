<?php
class ConsultaModel
{
    private PDO $conn;

    const STATUS_AGENDADA  = 'agendada';
    const STATUS_FINALIZADA = 'finalizada';
    const STATUS_CANCELADA  = 'cancelada';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    // ============================
    // CONSULTAS DO PACIENTE
    // ============================
    public function getConsultasPorPaciente($idPaciente)
    {
        $sql = "
            SELECT
                c.id,
                u.nome AS nome_medico,
                e.nome AS especialidade,
                h.data AS data_consulta,
                h.hora AS hora_consulta,
                c.status_consulta
            FROM consultas c
            JOIN horario h ON h.id_horario = c.id_horario
            JOIN medico m ON m.id_med = h.id_med
            JOIN usuario u ON u.id_user = m.id_user
            JOIN especialidades e ON e.id = m.id_especialidade
            WHERE c.id_paciente = :idPaciente
            ORDER BY h.data ASC, h.hora ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':idPaciente', $idPaciente, PDO::PARAM_INT);
        $stmt->execute();

        $consultas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // aplica regra das 24h
        foreach ($consultas as &$c) {
            $c['pode_cancelar'] = $this->podeCancelar(
                $c['data_consulta'],
                $c['hora_consulta']
            );
        }

        return $consultas;
    }

    // ============================
    // REGRA DAS 24H
    // ============================
    private function podeCancelar($data, $hora): bool
    {
        $dataConsulta = new DateTime($data . ' ' . $hora);
        $agora = new DateTime();

        $diffHoras = ($dataConsulta->getTimestamp() - $agora->getTimestamp()) / 3600;

        return $diffHoras >= 24;
    }

    // ============================
    // HORÁRIOS DISPONÍVEIS
    // ============================
    public function getHorariosDisponiveisPorMedico($idMedico, $data)
    {
        $sql = "
            SELECT id_horario, data, hora
            FROM horario
            WHERE id_med = :medico
              AND data = :data
              AND status = 'disponivel'
            ORDER BY hora ASC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':medico' => $idMedico,
            ':data'   => $data
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ============================
    // CRIAR AGENDAMENTO
    // ============================
    public function criarAgendamentoPorHorario($idPaciente, $idHorario)
    {
        try {
            $this->conn->beginTransaction();

            $check = $this->conn->prepare(
                "SELECT id_horario FROM horario 
                 WHERE id_horario = :id AND status = 'disponivel' FOR UPDATE"
            );
            $check->execute([':id' => $idHorario]);

            if (!$check->fetch()) {
                $this->conn->rollBack();
                return ['sucesso' => false, 'erro' => 'Horário indisponível'];
            }

            $insert = $this->conn->prepare(
                "INSERT INTO consultas (id_paciente, id_horario, status_consulta)
                 VALUES (:paciente, :horario, :status)"
            );
            $insert->execute([
                ':paciente' => $idPaciente,
                ':horario'  => $idHorario,
                ':status'   => self::STATUS_AGENDADA
            ]);

            $update = $this->conn->prepare(
                "UPDATE horario SET status = 'indisponivel' WHERE id_horario = :id"
            );
            $update->execute([':id' => $idHorario]);

            $this->conn->commit();
            return ['sucesso' => true];

        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['sucesso' => false, 'erro' => 'Erro ao agendar'];
        }
    }

    // ============================
    // CANCELAR CONSULTA
    // ============================
    public function cancelarConsulta($idConsulta)
    {
        $this->conn->beginTransaction();

        $this->conn->prepare(
            "UPDATE consultas 
             SET status_consulta = :status 
             WHERE id = :id"
        )->execute([
            ':status' => self::STATUS_CANCELADA,
            ':id'     => $idConsulta
        ]);

        $this->conn->prepare(
            "UPDATE horario h
             JOIN consultas c ON c.id_horario = h.id_horario
             SET h.status = 'disponivel'
             WHERE c.id = :id"
        )->execute([':id' => $idConsulta]);

        $this->conn->commit();
    }
}