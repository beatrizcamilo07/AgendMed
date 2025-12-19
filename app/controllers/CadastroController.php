<?php

require_once __DIR__ . '/../models/Usuario.php';

// Caso a constante não tenha sido definida no index, definimos um padrão de segurança
if (!defined('HOME_PAGE')) {
    define('HOME_PAGE', 'index.php?url=login/index');
}

class CadastroController
{
    private $conn;

    /**
     * Construtor recebe a conexão do banco injetada pelo index.php
     */
    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function cadastrar(): void
    {
        // Verifica se é POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . HOME_PAGE);
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        try {
            // Instancia o Model passando a conexão que recebemos no construtor
            $usuarioModel = new UsuarioModel($this->conn);

            // Coleta e limpa os dados
            $dados = [
                'nome'          => trim($_POST['nome'] ?? ''),
                'sobrenome'     => trim($_POST['sobrenome'] ?? ''),
                'email'         => trim($_POST['email'] ?? ''),
                'celular'       => trim($_POST['celular'] ?? ''),
                'senha'         => $_POST['senha'] ?? '',
                'tipo_usuario'  => $_POST['tipo_usuario'] ?? '',
                'genero'        => $_POST['genero'] ?? 'Prefiro não informar',
                'crm'           => trim($_POST['crm'] ?? ''),
                'especialidade' => trim($_POST['especialidade'] ?? '')
            ];

            /* =============================
               VALIDAÇÕES
            ============================== */

            if (empty($dados['nome']) || empty($dados['email']) || empty($dados['senha']) || empty($dados['tipo_usuario'])) {
                $_SESSION['error'] = 'Preencha todos os campos obrigatórios.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            $tiposPermitidos = ['paciente', 'medico', 'administrador'];
            if (!in_array($dados['tipo_usuario'], $tiposPermitidos, true)) {
                $_SESSION['error'] = 'Tipo de usuário inválido.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            if ($dados['tipo_usuario'] === 'medico' && (empty($dados['crm']) || empty($dados['especialidade']))) {
                $_SESSION['error'] = 'Médicos precisam informar CRM e Especialidade.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

            /* =============================
               TENTATIVA DE CADASTRO
            ============================== */

            if ($usuarioModel->cadastrar($dados)) {
                $_SESSION['success'] = 'Cadastro realizado com sucesso! Faça login.';
                // Redireciona para a tela de login
                header('Location: index.php?url=login/index');
                exit;
            } else {
                // Se o Model retornar false (erro de SQL tratado no catch do model)
                $_SESSION['error'] = 'Erro ao cadastrar. Verifique se o E-mail ou CRM já existem.';
                header('Location: ' . $_SERVER['HTTP_REFERER']);
                exit;
            }

        } catch (Throwable $e) {
            // Log do erro real no servidor
            error_log('Erro Crítico no CadastroController: ' . $e->getMessage());
            
            // Mensagem amigável para o usuário
            $_SESSION['error'] = 'Erro interno no servidor. Tente novamente mais tarde.';
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function index(): void
    {
        // Carrega a view de cadastro
        include __DIR__ . '/../views/cadastro/cadastro.php';
    }
}
?>