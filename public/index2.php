<?php

$url = $_GET['url'] ?? '';

switch ($url) {

    case 'login/autenticar':
        require_once "../app/controllers/LoginController.php";
        $controller = new LoginController();
        $controller->autenticar();
        break;

    case 'usuario/cadastrar':
        require_once "../app/controllers/UsuarioController.php";
        $controller = new UsuarioController();
        $controller->cadastrar();
        break;

    default:
        // AQUI você escolhe QUAL tela quer abrir:
        require_once "../app/views/login.php"; 
        // ou:
        // require_once "../app/views/cadastro.php";
}
