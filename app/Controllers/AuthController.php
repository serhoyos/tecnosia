<?php

namespace App\Controllers;

use App\Core\Database;
use PDO;

class AuthController
{
    private $db;

    public function __construct()
    {
        // Establecemos la conexión con la base de datos.
        $this->db = Database::getConnection();

        // Iniciamos la sesión si todavía no existe.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Método index requerido por el Router.
     */
    public function index()
    {
        $this->showLogin();
    }

    /**
     * Muestra el formulario de inicio de sesión.
     */
    public function showLogin()
    {
        // Si el usuario ya está autenticado,
        // lo redirigimos al dashboard.
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . \URL_BASE . 'dashboard');
            exit;
        }

        require __DIR__ . '/../../views/auth/login.php';
    }

    /**
     * Muestra el formulario de registro.
     */
    public function showRegister()
    {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . \URL_BASE . 'dashboard');
            exit;
        }

        require __DIR__ . '/../../views/auth/registro.php';
    }

    /**
     * Procesa la autenticación del usuario.
     */
    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Buscamos al usuario mediante una consulta preparada.
        $stmt = $this->db->prepare(
            "SELECT * FROM users
             WHERE email = :email LIMIT 1"
        );

        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificamos la contraseña contra el hash almacenado.
        if ($user && password_verify($password, $user['password'])) {

            // Regeneramos el identificador de sesión
            // para reducir el riesgo de fijación de sesión.
            session_regenerate_id(true);

            // Almacenamos los datos del usuario autenticado.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];

            header('Location: ' . \URL_BASE . 'dashboard');
            exit;
        }

        // Redirigimos al login si las credenciales son incorrectas.
        header('Location: ' . \URL_BASE . 'login?error=1');
        exit;
    }

    /**
     * Procesa el registro de un nuevo usuario.
     */
    public function register()
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validamos los campos obligatorios.
        if ($nombre === '' || $email === '' || $password === '') {
            die("Error: Todos los campos son obligatorios.");
        }

        // Validamos el formato del correo electrónico.
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Error: El correo electrónico no es válido.");
        }

        // Validamos la longitud de la contraseña.
        // BCRYPT admite hasta 72 bytes.
        if (strlen($password) < 8 || strlen($password) > 72) {
            die("Error: La contraseña debe tener entre 8 y 72 bytes.");
        }

        try {
            // Verificamos si el correo ya está registrado.
            $checkStmt = $this->db->prepare(
                "SELECT id FROM users
                 WHERE email = :email LIMIT 1"
            );

            $checkStmt->execute([
                'email' => $email
            ]);

            if ($checkStmt->fetch()) {
                die("Error: El correo electrónico ya está registrado.");
            }

            // Generamos un hash seguro mediante BCRYPT.
            // La contraseña nunca se almacena en texto plano.
            $passwordHash = password_hash(
                $password,
                PASSWORD_BCRYPT
            );

            // Registramos al usuario mediante PDO.
            $stmt = $this->db->prepare(
                "INSERT INTO users (nombre, email, password)
                 VALUES (:nombre, :email, :password)"
            );

            $stmt->execute([
                'nombre'   => $nombre,
                'email'    => $email,
                'password' => $passwordHash
            ]);

            // Obtenemos el identificador del nuevo usuario.
            $newUserId = $this->db->lastInsertId();

            // Regeneramos el identificador de sesión.
            session_regenerate_id(true);

            // Iniciamos automáticamente la sesión.
            $_SESSION['user_id'] = $newUserId;
            $_SESSION['user_name'] = $nombre;

            // Redirigimos al dashboard.
            header('Location: ' . \URL_BASE . 'dashboard');
            exit;

        } catch (\PDOException $e) {

            // Registramos el error sin exponer información
            // interna de la base de datos al usuario.
            error_log($e->getMessage());

            die("Error: No fue posible registrar el usuario.");
        }
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header('Location: ' . \URL_BASE . 'login');
        exit;
    }
}
