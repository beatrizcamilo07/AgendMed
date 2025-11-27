<?php
require_once __DIR__ . '/../../config/conexao.php';
class Usuario {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function cadastrar($dados) {

        $sql = "INSERT INTO usuario 
                (nome, sobrenome, email, celular, tipo_usuario, genero, senha)
                VALUES 
                (:nome, :sobrenome, :email, :celular, :tipo_usuario, :genero, :senha)";

        $stmt = $this->conn->prepare($sql);

        $senhaCriptografada = password_hash($dados['senha'], PASSWORD_DEFAULT);

        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':sobrenome', $dados['sobrenome']);
        $stmt->bindParam(':email', $dados['email']);
        $stmt->bindParam(':celular', $dados['celular']);
        $stmt->bindParam(':tipo_usuario', $dados['tipo_usuario']);
        $stmt->bindParam(':genero', $dados['genero']);
        $stmt->bindParam(':senha', $senhaCriptografada);

        return $stmt->execute();
    }
}
