<?php
require_once __DIR__ . '/../models/Usuario.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class LoginController
{
    private UsuarioModel $usuarioModel;

    public function __construct(PDO $db)
    {
        $this->usuarioModel = new UsuarioModel($db);
    }

    // Tela de login
    public function index(): void
    {
        $erro = $_GET['erro'] ?? null;
        require __DIR__ . '/../views/login/login.php';
    }

    // Processa login
    public function autenticar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=login/index');
            exit;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? null;

        if (!$email || !$senha) {
            header('Location: index.php?url=login/index&erro=Dados inválidos');
            exit;
        }

        $loginOk = $this->usuarioModel->login($email, $senha);

        if (!$loginOk) {
            header(
                'Location: index.php?url=login/index&erro=' .
                urlencode('E-mail ou senha incorretos')
            );
            exit;
        }

        // segurança básica
        if (!isset($_SESSION['user_id'], $_SESSION['user_tipo'])) {
            session_destroy();
            header('Location: index.php?url=login/index&erro=Erro de sessão');
            exit;
        }

        // redirecionamento por tipo
        switch ($_SESSION['user_tipo']) {
            case 'paciente':
                header('Location: index.php?url=paciente/dashboardPaciente');
                break;

            case 'medico':
                header('Location: index.php?url=medico/dashboardMedico');
                break;

            case 'administrador':
                header('Location: index.php?url=administrador/dashboardAdministrador');
                break;

            default:
                session_destroy();
                header('Location: index.php?url=login/index&erro=Tipo de usuário inválido');
                break;
        }

        exit;
    }

    // Logout
    public function logout(): void
    {
        session_destroy();
        header('Location: index.php?url=login/index');
        exit;
    }
}