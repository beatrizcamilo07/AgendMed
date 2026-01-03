<?php

class SobreNosController
{
    private $db;

    // O roteador passa o banco de dados aqui, mesmo que você não use nesta página específica
    public function __construct($db)
    {
        $this->db = $db;
    }

    public function index()
    {
        // Não precisa de session_start() nem de verificação de login aqui. 
        // O index.php já fez isso por você!
        require_once __DIR__ . '/../views/sobrenos/sobre-nos.php';
    }
}