<?php
class MedicoModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getDadosMedicoCompleto($idUser) {
        $sql = "SELECT m.id_med, u.nome
                FROM medico m
                JOIN usuario u ON m.id_user = u.id_user
                WHERE u.id_user = :id_user";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id_user', $idUser);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarMedicosComEspecialidade() {
        $query = "SELECT m.id_med, u.nome, e.nome as especialidade
                  FROM medico m
                  JOIN usuario u ON m.id_user = u.id_user
                  JOIN especialidades e ON m.id_especialidade = e.id
                  ORDER BY e.nome ASC, u.nome ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHorariosDisponiveisPorMedico($idMed, $data) {
        $sql = "SELECT id_horario, TIME(data_hora_inicio) as hora
                FROM horario
                WHERE id_med = :med
                  AND DATE(data_hora_inicio) = :data
                  AND status = 'disponivel'
                ORDER BY data_hora_inicio ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':med' => $idMed, ':data' => $data]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
