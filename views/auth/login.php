<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tecnosia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; border: none; }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="card shadow-lg border-0" style="width: 100%; max-width: 400px; border-radius: 15px;">
        <div class="card-body p-5">
            <h2 class="text-center fw-bold mb-4" style="color: #1a237e;">TECNOSIA</h2>
            
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger py-2 small">Credenciales incorrectas. Inténtalo de nuevo.</div>
            <?php endif; ?>

            <form action="<?= \URL_BASE; ?>login" method="POST">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Correo Electrónico</label>
                    <input type="email" name="email" class="form-control form-control-lg" placeholder="nombre@correo.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold">Contraseña</label>
                    <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm" style="background-color: #0d6efd; border: none;">
                    Entrar
                </button>
            </form>

            <div class="mt-4 text-center">
                <p class="text-muted small">¿No tienes cuenta? 
                    <a href="<?= \URL_BASE; ?>registro" class="text-primary fw-bold text-decoration-none">Regístrate aquí</a>
                </p>
            </div>
        </div>
    </div>
</div>
</body>
</html>