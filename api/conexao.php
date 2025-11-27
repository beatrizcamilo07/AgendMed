<?php
$host = "localhost";
$user = "root"; // ou outro se você mudou no XAMPP
$pass = "";     // senha padrão do XAMPP é vazia
$db   = "agendmed";

$con = new mysqli($host, $user, $pass, $db);

if ($con->connect_error) {
    die("Falha na conexão: " . $con->connect_error);
}
?>
