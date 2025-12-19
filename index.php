<?php
session_start();

require_once __DIR__ . '/app/config/database.php';

$url = $_GET['url'] ?? 'login/index';
$url = trim($url, '/');
$urlParts = explode('/', $url);

$controllerSegment = $urlParts[0] ?? 'login';
$methodName        = $urlParts[1] ?? 'index';

// Controllers públicos
$rotasPublicas = ['login', 'cadastro'];

// Logout
if ($controllerSegment === 'logout') {
    session_unset();
    session_destroy();
    header('Location: index.php?url=login/index');
    exit;
}

// Bloqueio de rotas
$usuarioLogado = $_SESSION['user_id'] ?? null;
$tipoUsuario = $_SESSION['user_tipo'] ?? null;

if (!$usuarioLogado && !in_array($controllerSegment, $rotasPublicas)) {
    header('Location: index.php?url=login/index');
    exit;
}

if ($usuarioLogado && in_array($controllerSegment, $rotasPublicas)) {
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
            header('Location: index.php?url=login/index');
            break;
    }
    exit;
}

// Monta controller
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

$database = new Database();
$db = $database->getConnection();

try {
    $controller = new $controllerName($db);
} catch (ArgumentCountError $e) {
    $controller = new $controllerName();
}

if (!method_exists($controller, $methodName)) {
    http_response_code(404);
    echo "Método {$methodName} não encontrado no controller {$controllerName}.";
    exit;
}

// Parâmetros extras da URL
$params = array_slice($urlParts, 2);
call_user_func_array([$controller, $methodName], $params);
?>