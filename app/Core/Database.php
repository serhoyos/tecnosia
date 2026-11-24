<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static $instance = null;

    public static function getConnection()
    {
        if (self::$instance === null) {

            // Ubicación del archivo de configuración local.
            $configPath = __DIR__ . '/../../config/config.php';

            // Comprobamos que exista el archivo de configuración.
            if (!is_file($configPath)) {
                http_response_code(500);
                exit('Error: falta el archivo de configuración.');
            }

            // Cargamos las credenciales de conexión.
            require_once $configPath;

            // Verificamos las constantes requeridas.
            if (
                !defined('DB_HOST') ||
                !defined('DB_NAME') ||
                !defined('DB_USER') ||
                !defined('DB_PASS') ||
                !defined('DB_CHARSET')
            ) {
                http_response_code(500);
                exit('Error: configuración de base de datos incompleta.');
            }

            try {
                // Obtenemos los parámetros de conexión.
                $host = DB_HOST;
                $db = DB_NAME;
                $user = DB_USER;
                $pass = DB_PASS;
                $charset = DB_CHARSET;

                // Construimos la cadena de conexión PDO.
                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

                // Configuramos las opciones de PDO.
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                // Creamos una única instancia de conexión.
                self::$instance = new PDO(
                    $dsn,
                    $user,
                    $pass,
                    $options
                );

            } catch (PDOException $e) {

                // Registramos el error internamente.
                error_log($e->getMessage());

                // Evitamos exponer información sensible al usuario.
                http_response_code(500);
                exit('Error de conexión a la base de datos.');
            }
        }

        return self::$instance;
    }
}
