<?php
/**
 * ARCHIVO DE ENTRADA PRINCIPAL - TECNOSIA
 * Ubicación: public/index.php
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/config.php';

// Autocargador manual
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../app/';
    $relativeClass = str_replace('App\\', '', $class);
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

use App\Controllers\AuthController;
use App\Controllers\IdeaController;
use App\Core\Router;

// --- AQUÍ ESTABA EL ERROR: Inicializamos la variable ---
$router = new Router();

// --- REGISTRO DE RUTAS ---
$router->add('GET', '', [AuthController::class, 'index']);
$router->add('GET', 'login', [AuthController::class, 'index']);
$router->add('POST', 'login', [AuthController::class, 'login']);
$router->add('GET', 'logout', [AuthController::class, 'logout']);

// Rutas de Registro
$router->add('GET', 'registro', [AuthController::class, 'showRegister']);
$router->add('POST', 'registro', [AuthController::class, 'register']);

// Rutas de Dashboard/Ideas
$router->add('GET', 'dashboard', [IdeaController::class, 'dashboard']);
$router->add('GET', 'ideas/crear', [IdeaController::class, 'create']);
$router->add('POST', 'ideas/guardar', [IdeaController::class, 'store']);
$router->add('GET', 'ideas/eliminar', [IdeaController::class, 'delete']);

// Resolver
$router->resolve();