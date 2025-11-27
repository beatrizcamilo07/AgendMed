<?php

$url = $_GET['url'] ?? '';

if ($url === "usuario/cadastrar") {
    require_once __DIR__ . "/../app/controllers/usuarioController.php";
    $controller = new UsuarioController();
    $controller->cadastrar();
} else {
    require_once __DIR__ . "/../app/views/cadastro.php";
}

