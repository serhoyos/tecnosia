<?php
namespace App\Core;

class Router {
    protected $routes = [];

    // Registra una ruta tipo GET
    public function get($uri, $callback) {
        $this->routes['GET'][$uri] = $callback;
    }

    // Registra una ruta tipo POST
    public function post($uri, $callback) {
        $this->routes['POST'][$uri] = $callback;
    }

    // Ejecuta la ruta solicitada
    public function resolve() {
        $uri = $_GET['url'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];
        $callback = $this->routes[$method][$uri] ?? false;

        if ($callback === false) {
            echo "404 - Página no encontrada";
            return;
        }

        // Si el callback es una función, la ejecutamos
        if (is_callable($callback)) {
            return call_user_func($callback);
        }

        // Si es un string tipo "AuthController@login", lo procesamos
        if (is_string($callback)) {
            $parts = explode('@', $callback);
            $controllerName = "\\App\\Controllers\\" . $parts[0];
            $action = $parts[1];
            
            $controller = new $controllerName();
            return $controller->$action();
        }
    }
}