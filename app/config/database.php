<?php

class Database {
    // Configurações do Banco de Dados
    private $host = "localhost";
    private $db_name = "agendmed"; 
    private $username = "root"; 
    private $password = ""; 
    public $conn;

    // Método de conexão
    public function getConnection() {
        $this->conn = null;
        try {
            // Cria a conexão PDO
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            
            // Define o charset para utf8mb4 (Melhor que utf8 comum, aceita acentos e emojis)
            $this->conn->exec("set names utf8mb4");
            
            // Ativa o modo de erros para Exceptions (importante para try/catch funcionar)
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
        } catch(PDOException $exception) {
            // Em produção, evite dar echo direto, prefira gravar em log
            echo "Erro de conexão: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>