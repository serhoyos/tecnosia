<?php
/**
 * TECNOSIA - Front Controller Centralizado
 * Proyecto de Grado - UNAD 2026
 * ---------------------------------------------------------
 * Este archivo gestiona: Sesiones, Autoload y Enrutamiento.
 */

// 1. GESTIÓN DE SESIONES
// Debe ir al inicio para que el Dashboard reconozca al usuario.
session_start();

// 2. CONFIGURACIÓN DE ERRORES
// Mantener activo durante el desarrollo para depurar en Linux.
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 3. AUTOLOAD DE CLASES (Estándar PSR-4)
// Busca automáticamente archivos en la carpeta app/
spl_autoload_register(function ($class) {
    $classPath = str_replace('\\', '/', $class);
    
    // Mapeo: El Namespace "App\" apunta a la carpeta física "app/"
    $file = dirname(__DIR__) . '/' . str_replace('App/', 'app/', $classPath) . '.php';

    if (file_exists($file)) {
        require_once $file;
    } else {
        echo "<div style='background:#fff5f5; color:#c53030; padding:20px; border:1px solid #feb2b2; font-family:sans-serif;'>";
        echo "<h3>🚨 Error de Carga de Clase</h3>";
        echo "No se encontró el archivo para la clase: <b>$class</b><br>";
        echo "Ruta esperada: <code>$file</code>";
        echo "</div>";
        die();
    }
});

// 4. IMPORTAR COMPONENTES
use App\Core\Router;

$router = new Router();

// 5. DEFINICIÓN DE RUTAS (Mapeo URL -> Controlador)
// ------------------------------------------------------------------

/**
 * RUTA RAÍZ (Home / Dashboard)
 * Ahora delegamos la lógica al DashboardController para decidir
 * si mostramos el panel o invitamos a iniciar sesión.
 */
$router->get('/', 'DashboardController@index');

/**
 * RUTAS DE AUTENTICACIÓN (AuthController)
 */
// Registro de nuevos emprendedores
$router->get('registro', 'AuthController@mostrarRegistro');
$router->post('registro', 'AuthController@procesarRegistro');

// Inicio y cierre de sesión
$router->get('login', 'AuthController@mostrarLogin');
$router->post('login', 'AuthController@procesarLogin');
$router->get('logout', 'AuthController@logout');


// 6. EJECUCIÓN
// ------------------------------------------------------------------
$router->resolve();