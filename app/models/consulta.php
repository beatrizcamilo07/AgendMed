<?php

class Consulta {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function agendar($idPaciente, $idMedico, $data, $hora) {
        $sql = "INSERT INTO consultas (id_paciente, id_medico, data, hora, status)
                VALUES (?, ?, ?, ?, 'Pendente')";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$idPaciente, $idMedico, $data, $hora]);
    }

    public function listar($idPaciente) {
        $sql = "SELECT c.*, m.nome AS medico, m.especialidade
                FROM consultas c
                INNER JOIN medicos m ON m.id = c.id_medico
                WHERE c.id_paciente = ?
                  AND c.status = 'Pendente'
                ORDER BY c.data ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idPaciente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function historico($idPaciente) {
        $sql = "SELECT c.*, m.nome AS medico, m.especialidade
                FROM consultas c
                INNER JOIN medicos m ON m.id = c.id_medico
                WHERE c.id_paciente = ?
                  AND c.status != 'Pendente'
                ORDER BY c.data DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$idPaciente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
