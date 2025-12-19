<?php
class EspecialidadeModel{
    private $conn;
    private $table = 'especialidades';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Retorna o statement com todas as especialidades.
     */
    public function readAll() {
        $query = "SELECT id, nome FROM " . $this->table . " ORDER BY nome ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Cria uma nova especialidade.
     * @param string $nome O nome da nova especialidade.
     * @return bool True em caso de sucesso, False em caso de falha.
     */
    public function create($nome) {
        $query = "INSERT INTO " . $this->table . " (nome) VALUES (:nome)";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpar dados
        $nome = htmlspecialchars(strip_tags($nome));

        // Vincular dados
        $stmt->bindParam(':nome', $nome);

        // Executar a query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    /**
     * Atualiza o nome de uma especialidade existente.
     * @param int $id O ID da especialidade.
     * @param string $nome O novo nome.
     * @return bool True em caso de sucesso, False em caso de falha.
     */
    public function update($id, $nome) {
        $query = "UPDATE " . $this->table . " SET nome = :nome WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpar dados
        $nome = htmlspecialchars(strip_tags($nome));
        $id = htmlspecialchars(strip_tags($id));

        // Vincular dados
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':id', $id);

        // Executar a query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    /**
     * Deleta uma especialidade pelo ID.
     * ATENÇÃO: Dependendo das FOREIGN KEYS, pode haver erro se houverem médicos associados.
     * @param int $id O ID da especialidade a ser deletada.
     * @return bool True em caso de sucesso, False em caso de falha.
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        
        // Limpar dados
        $id = htmlspecialchars(strip_tags($id));

        // Vincular ID
        $stmt->bindParam(':id', $id);

        // Executar a query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>