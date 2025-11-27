<?php
require "conexao.php";

$sql = "SELECT * FROM usuario LIMIT 1";
$result = $con->query($sql);

if ($result) {
    echo "Conexão funcionando e tabela acessível!";
} else {
    echo "Conectou, mas deu erro na consulta: " . $con->error;
}
?>
