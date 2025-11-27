<?php
session_start();

if (!isset($_SESSION['tipo_usuario']) || $_SESSION['tipo_usuario'] !== 'paciente') {
    header("Location: ../../public/index.php");
    exit;
}

$nomeUsuario = $_SESSION['usuario_nome'];

// depois de validar, manda para a view
include "../../app/views/paciente/paciente.php";
