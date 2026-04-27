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
            try {
                // CONFIGURACIÓN DE CONEXIÓN
                $host = 'localhost';
                $db   = 'tecnosia_db'; // Asegúrate que este sea el nombre en phpMyAdmin
                $user = 'admin_tecnosia';        // Usuario por defecto
                $pass = 'TecnoSIA2026*';            // Prueba vacío primero. Si falla, prueba con 'root'
                $charset = 'utf8mb4';

                $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
                
                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                self::$instance = new PDO($dsn, $user, $pass, $options);
                
            } catch (PDOException $e) {
                // Si vuelve a fallar, este mensaje nos dirá por qué
                die("Error de conexión a la base de datos: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}