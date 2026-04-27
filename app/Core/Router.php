<?php
namespace App\Core;

class Router
{
    protected $routes = [];

    public function add($method, $route, $handler)
    {
        $this->routes[] = [
            'method'  => $method,
            'route'   => trim($route, '/'), // Limpiamos barras
            'handler' => $handler
        ];
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Detectamos la carpeta del proyecto dinámicamente
        // Si tu URL es localhost/tecnosia/, esto detectará /tecnosia
        $basePath = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
        $path = str_replace($basePath, '', $uri);
        $path = trim($path, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['route'] === $path) {
                $controllerName = $route['handler'][0];
                $methodName = $route['handler'][1];

                if (class_exists($controllerName)) {
                    $controller = new $controllerName();
                    return $controller->$methodName();
                }
            }
        }

        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        echo "<p>El sistema no reconoce la ruta: <strong>" . ($path ?: '(raíz)') . "</strong></p>";
        echo "<a href='" . \URL_BASE . "'>Volver al inicio</a>";
    }
}