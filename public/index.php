<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
 * ARCHIVO DE ENTRADA PRINCIPAL - TECNOSIA
 * Ubicación: public/index.php
 */

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
use App\Controllers\HomeController;
use App\Core\Router;

// Inicializamos el enrutador
$router = new Router();

// --- REGISTRO DE RUTAS ---
// Rutas Públicas y Autenticación
$router->add('GET', '', [HomeController::class, 'index']);
$router->add('GET', 'login', [AuthController::class, 'index']);
$router->add('POST', 'login', [AuthController::class, 'login']);
$router->add('GET', 'logout', [AuthController::class, 'logout']);

// Rutas de Registro
$router->add('GET', 'registro', [AuthController::class, 'showRegister']);
$router->add('POST', 'registro', [AuthController::class, 'register']);

// Rutas de Dashboard
$router->add('GET', 'dashboard', [IdeaController::class, 'dashboard']);

// --- GESTIÓN DE IDEAS (CRUD) ---
// Crear
$router->add('GET', 'ideas/create', [IdeaController::class, 'create']);
$router->add('POST', 'ideas/guardar', [IdeaController::class, 'store']);

// Editar
$router->add('GET', 'ideas/edit', [IdeaController::class, 'edit']);
$router->add('POST', 'ideas/actualizar', [IdeaController::class, 'update']);

// Eliminar (Debe ser POST por seguridad)
$router->add('POST', 'ideas/delete', [IdeaController::class, 'delete']);

// Resolver
$router->resolve();