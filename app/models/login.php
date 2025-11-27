<?php

class Login {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function verificarLogin($email) {

        $sql = "SELECT * FROM usuario WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

