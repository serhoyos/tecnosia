<?php

require_once __DIR__ . '/../models/User.php';

class AuthController
{

    public function register()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $user = new User();

            if ($user->create($name, $email, $password)) {

                echo "Usuario registrado correctamente";

            } else {

                echo "Error al registrar usuario";

            }
        }

        require_once __DIR__ . '/../views/register.php';
    }


    public function login()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'];
            $password = $_POST['password'];

            $userModel = new User();

            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];

                header("Location: ?route=dashboard");
                exit;

            } else {

                echo "Credenciales incorrectas";

            }
        }

        require_once __DIR__ . '/../views/login.php';
    }

}
