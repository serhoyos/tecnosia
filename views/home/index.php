<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TECNOSIA - Validación de Ideas de Negocio</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            /* Fondo gris muy claro, idéntico al fondo de tus formularios */
            background-color: #f8f9fa; 
            color: #212529;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .landing-card {
            text-align: center;
            max-width: 600px;
            padding: 50px 30px;
            background: #ffffff;
            border-radius: 12px;
            /* Sombra sutil idéntica a la tarjeta de login/registro */
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); 
            border: 1px solid rgba(0,0,0,0.05);
        }
        .logo-container {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 15px;
            /* Azul muy oscuro para el título TECNOSIA */
            color: #1a1e29; 
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .logo-emoji {
            font-size: 3.2rem;
        }
        .subtitle {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 40px;
            color: #6c757d; /* Texto secundario gris */
        }
        /* Botón verde para coincidir con tu formulario de Registro */
        .btn-register {
            background-color: #198754; 
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        .btn-register:hover {
            background-color: #157347;
            color: white;
            transform: translateY(-1px);
        }
        /* Botón azul para coincidir con tu formulario de Login */
        .btn-login {
            background-color: #0d6efd; 
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s ease;
        }
        .btn-login:hover {
            background-color: #0b5ed7;
            color: white;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

    <div class="landing-card">
        <!-- Logo y Título -->
        <div class="logo-container">
            <span class="logo-emoji">🚀</span> TECNOSIA
        </div>
        
        <!-- Descripción -->
        <p class="subtitle">
            Analiza y valida tus ideas de negocio antes de invertir tiempo o dinero.<br>
            Utiliza un sistema experto basado en principios reales de emprendimiento.
        </p>
        
        <!-- Botones de Acción -->
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="<?= \URL_BASE; ?>registro" class="btn btn-register">Registrarse</a>
            <a href="<?= \URL_BASE; ?>login" class="btn btn-login">Iniciar sesión</a>
        </div>
    </div>

</body>
</html>