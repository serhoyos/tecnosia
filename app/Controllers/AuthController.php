<?php
namespace App\Controllers;

use App\Models\Usuario;

class AuthController {

    /**
     * Muestra el formulario de registro (Ruta GET 'registro')
     */
    public function mostrarRegistro() {
        // Subimos dos niveles desde Controllers/ para llegar a la raíz y entrar a views/
        require_once dirname(__DIR__, 2) . '/views/auth/registro.php';
    }

    /**
     * Procesa la creación de cuenta (Ruta POST 'registro')
     */
    public function procesarRegistro() {
        $nombre   = $_POST['nombre']   ?? '';
        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';

        if (!empty($nombre) && !empty($email) && !empty($password)) {
            $usuario = new Usuario();
            if ($usuario->registrar($nombre, $email, $password)) {
                echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>";
                echo "<h2>✅ ¡Registro exitoso!</h2>";
                echo "<p>Ya puedes iniciar sesión. <a href='login'>Ir al Login</a></p>";
                echo "</div>";
            } else {
                echo "❌ Error al guardar el usuario. Es posible que el correo ya esté registrado.";
            }
        } else {
            echo "⚠️ Todos los campos son obligatorios.";
        }
    }

    /**
     * Muestra el formulario de inicio de sesión (Ruta GET 'login')
     */
    public function mostrarLogin() {
        require_once dirname(__DIR__, 2) . '/views/auth/login.php';
    }

    /**
     * Valida las credenciales del usuario (Ruta POST 'login')
     */
    public function procesarLogin() {
        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';

        $model = new Usuario();
        $user = $model->buscarPorEmail($email);

        // Verificamos si el usuario existe y si la contraseña coincide con el hash
        if ($user && password_verify($password, $user['password'])) {
            // Guardamos datos clave en la sesión
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['nombre'];
            $_SESSION['user_rol']  = $user['rol'];

            // Redirigimos a la raíz (Home)
            header("Location: /tecnosia/");
            exit();
        } else {
            echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>";
            echo "❌ Correo o contraseña incorrectos.<br><br>";
            echo "<a href='login'>Volver a intentar</a>";
            echo "</div>";
        }
    }

    /**
     * Destruye la sesión y sale (Ruta GET 'logout')
     */
    public function logout() {
        // Limpiamos el array de sesión
        $_SESSION = [];
        // Destruimos la sesión en el servidor
        session_destroy();
        // Redirigimos al login
        header("Location: /tecnosia/login");
        exit();
    }
}