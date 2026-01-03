<?php
class AjudaController {
    public function __construct($db = null) { }
    public function index() {
        $usuarioTipo = $_SESSION['user_tipo'] ?? 'paciente';
        $dados = ['usuarioTipo' => $usuarioTipo];
        require_once __DIR__ . '/../views/ajuda/ajuda.php';
    }
}
