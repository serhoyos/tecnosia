<?php

require_once '../app/controllers/AuthController.php';

$controller = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->register();
}

require_once '../app/views/register.php';
