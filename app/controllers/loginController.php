<?php

class LoginController
{
    private UsuarioModel $usuarioModel;

    public function __construct(PDO $db)
    {
        $this->usuarioModel = new UsuarioModel($db);
    }

    public function index(): void
    {
        $erro = $_GET['erro'] ?? null;
        include __DIR__ . '/../views/login/login.php';
    }

    public function autenticar(): void
    {
        session_start(); // <<< OBRIGATÓRIO

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=login/index');
            exit;
        }

        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $senha = filter_input(INPUT_POST, 'senha');

        if (!$email || !$senha) {
            header('Location: index.php?url=login/index&erro=Dados inválidos');
            exit;
        }

        $loginOk = $this->usuarioModel->login($email, $senha);

        if ($loginOk) {

            // agora essa verificação faz sentido
            if (!isset($_SESSION['user_id'], $_SESSION['user_tipo'])) {
                session_destroy();
                header('Location: index.php?url=login/index&erro=Erro de sessão');
                exit;
            }

            switch ($_SESSION['user_tipo']) {
                case 'paciente':
                    header('Location: index.php?url=paciente/dashboard-paciente');
                    break;

                case 'medico':
                    header('Location: index.php?url=medico/dashboard-med');
                    break;

                case 'administrador':
                    header('Location: index.php?url=administrador/dashboard-adm');
                    break;

                default:
                    session_destroy();
                    header('Location: index.php?url=login/index&erro=Tipo de usuário inválido');
                    break;
            }

            exit;
        }

        header(
            'Location: index.php?url=login/index&erro=' .
            urlencode('E-mail ou senha incorretos')
        );
        exit;
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        header('Location: index.php?url=login/index');
        exit;
    }
}
?>