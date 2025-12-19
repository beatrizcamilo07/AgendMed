<?php

class UsuarioModel
{
    private PDO $conn;

    private string $tableUsuario       = 'usuario';
    private string $tableMedico        = 'medico';
    private string $tableEspecialidade = 'especialidades';

    public function __construct(PDO $db)
    {
        $this->conn = $db;
    }

    /* =====================================================
       CADASTRO
    ====================================================== */
    public function cadastrar(array $dados): bool
    {
        try {
            $this->conn->beginTransaction();

            $sqlUsuario = "
                INSERT INTO {$this->tableUsuario}
                (nome, sobrenome, celular, email, senha, tipo_usuario, genero)
                VALUES
                (:nome, :sobrenome, :celular, :email, :senha, :tipo_usuario, :genero)
            ";

            $stmt = $this->conn->prepare($sqlUsuario);
            $stmt->bindValue(':nome', trim($dados['nome']));
            $stmt->bindValue(':sobrenome', trim($dados['sobrenome']));
            $stmt->bindValue(':celular', trim($dados['celular']));
            $stmt->bindValue(':email', trim($dados['email']));
            $stmt->bindValue(':senha', password_hash($dados['senha'], PASSWORD_DEFAULT));
            $stmt->bindValue(':tipo_usuario', $dados['tipo_usuario']);
            $stmt->bindValue(':genero', $dados['genero']);
            $stmt->execute();

            $idUser = (int) $this->conn->lastInsertId();

            if ($dados['tipo_usuario'] === 'medico') {
                $this->cadastrarMedico(
                    $idUser,
                    $dados['crm'],
                    $dados['especialidade']
                );
            }

            $this->conn->commit();
            return true;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            throw new RuntimeException(
                'Erro ao cadastrar usuário.'
            );
        }
    }

    /* =====================================================
       LOGIN
    ====================================================== */
    public function login(string $email, string $senha): bool
    {
        $sql = "
            SELECT id_user, nome, senha, tipo_usuario
            FROM {$this->tableUsuario}
            WHERE email = :email
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', trim($email));
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        if (!password_verify($senha, $user['senha'])) {
            return false;
        }

        // sessão JÁ deve estar iniciada no Controller
        $_SESSION['user_id']   = (int) $user['id_user'];
        $_SESSION['user_nome'] = $user['nome'];
        $_SESSION['user_tipo'] = $user['tipo_usuario'];

        return true;
    }

    /* =====================================================
       MÉDICO
    ====================================================== */
    private function cadastrarMedico(
        int $idUser,
        string $crm,
        string $especialidade
    ): void {
        $idEspecialidade = $this->obterOuCriarEspecialidade($especialidade);

        $sql = "
            INSERT INTO {$this->tableMedico}
            (id_user, id_especialidade, crm)
            VALUES
            (:id_user, :id_especialidade, :crm)
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $idUser);
        $stmt->bindValue(':id_especialidade', $idEspecialidade);
        $stmt->bindValue(':crm', trim($crm));
        $stmt->execute();
    }

    private function obterOuCriarEspecialidade(string $nome): int
    {
        $sql = "
            SELECT id
            FROM {$this->tableEspecialidade}
            WHERE nome = :nome
            LIMIT 1
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nome', trim($nome));
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            return (int) $row['id'];
        }

        $sqlInsert = "
            INSERT INTO {$this->tableEspecialidade} (nome)
            VALUES (:nome)
        ";

        $stmtInsert = $this->conn->prepare($sqlInsert);
        $stmtInsert->bindValue(':nome', trim($nome));
        $stmtInsert->execute();

        return (int) $this->conn->lastInsertId();
    }
}
