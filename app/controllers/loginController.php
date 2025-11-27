<?php
require_once __DIR__ . '/../models/login.php';
require_once __DIR__ . '/../../config/conexao.php';

class LoginController {

    public function autenticar() {

        session_start();

        $database = new Database();
        $db = $database->getConnection();

        $login = new Login($db);

        $email = $_POST['email'];
        $senha = $_POST['senha'];

        $usuario = $login->verificarLogin($email);

        if ($usuario && password_verify($senha, $usuario['senha'])) {

            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];

            switch ($usuario['tipo_usuario']) {
                case 'paciente':
                    header('Location: ../app/views/dashboard/paciente.php');
                    break;

                case 'medico':
                    header('Location: ../app/views/dashboard/medico.php');
                    break;

                case 'admin':
                    header('Location: ../app/views/dashboard/admin.php');
                    break;
            }
            exit;
            
        } else {
            echo "Login incorreto.";
        }
    }
}
