<?php

require_once '../core/Database.php';

$db = new Database();
$conn = $db->getConnection();

echo "Conexión exitosa a la base de datos.";
