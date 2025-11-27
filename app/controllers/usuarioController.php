<?php

class UsuarioController
{
    public function cadastrar()
    {
        // Model
        require_once __DIR__ . '/../models/usuario.php';

        // Conexão
        require_once __DIR__ . '/../../config/conexao.php';

        $database = new Database();
        $db = $database->getConnection();

        $usuario = new Usuario($db);

        // pega os dados do formulário
        $dados = [
            'nome' => $_POST['nome'],
            'sobrenome' => $_POST['sobrenome'],
            'email' => $_POST['email'],
            'celular' => $_POST['celular'],
            'tipo_usuario' => $_POST['tipo_usuario'],
            'genero' => $_POST['genero'],
            'senha' => $_POST['senha']
        ];

        if ($usuario->cadastrar($dados)) {
            echo "Usuário cadastrado com sucesso!";
        } else {
            echo "Erro ao cadastrar usuário.";
        }
    }
}

