<!DOCTYPE html>
<html>
<head>
    <title>Registro - Tecnosia</title>
</head>
<body>

<h2>Registro de usuario</h2>

<form method="POST" action="/tecnosia/public/register.php">

    <label>Nombre</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email</label><br>
    <input type="email" name="email" required><br><br>

    <label>Contraseña</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Registrarse</button>

</form>

</body>
</html>
