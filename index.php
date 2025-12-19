<?php
// index.php — Front Controller

session_start();

// Configurações básicas
require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/models/Usuario.php';

// Roteamento
$url = $_GET['url'] ?? 'login/index';
$url = rtrim($url, '/');
$urlParts = explode('/', $url);

$controllerSegment = $urlParts[0] ?? 'login';
$methodName        = $urlParts[1] ?? 'index';

// Controle de sessão
$usuarioLogado = $_SESSION['user_id']   ?? null;
$tipoUsuario   = $_SESSION['user_tipo'] ?? null;

$rotasPublicas = ['login', 'cadastro'];

// Usuário NÃO logado tenta acessar rota privada
if (!$usuarioLogado && !in_array($controllerSegment, $rotasPublicas, true)) {
    header('Location: index.php?url=login/index');
    exit;
}

// Usuário LOGADO tenta acessar rota pública
if ($usuarioLogado && in_array($controllerSegment, $rotasPublicas, true)) {
    switch ($tipoUsuario) {
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

// ----------------- LOGOUT -----------------
if ($controllerSegment === 'logout') {
    if (!$usuarioLogado) {
        header('Location: index.php?url=login/index');
        exit;
    }

    switch ($tipoUsuario) {
        case 'paciente':
            require_once __DIR__ . '/app/controllers/PacienteController.php';
            $controller = new PacienteController();
            $controller->logout();
            break;
        case 'medico':
            require_once __DIR__ . '/app/controllers/MedicoController.php';
            $controller = new MedicoController();
            $controller->logout();
            break;
        case 'administrador':
            require_once __DIR__ . '/app/controllers/AdministradorController.php';
            $controller = new AdministradorController();
            $controller->logout();
            break;
        default:
            session_destroy();
            header('Location: index.php?url=login/index&erro=Tipo de usuário inválido');
            break;
    }
    exit;
}

// Resolução do Controller
$controllerName = ucfirst($controllerSegment) . 'Controller';
$controllerFile = __DIR__ . "/app/controllers/{$controllerName}.php";

if (!file_exists($controllerFile)) {
    http_response_code(404);
    echo "Controller não encontrado: {$controllerName}";
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    http_response_code(500);
    echo "Classe {$controllerName} não existe.";
    exit;
}

// Execução do método
$database = new Database();
$db = $database->getConnection();

// Passa $db só se o controller aceitar no construtor
$controller = new $controllerName($db ?? null);

if (!method_exists($controller, $methodName)) {
    http_response_code(404);
    echo "Método {$methodName} não encontrado no controller {$controllerName}.";
    exit;
}

// Passa parâmetros adicionais na URL se existirem
$params = array_slice($urlParts, 2);
call_user_func_array([$controller, $methodName], $params);