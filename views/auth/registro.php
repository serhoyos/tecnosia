<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Tecnosia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; border: none; }
    </style>
</head>
<body>
    <div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0" style="border-radius: 15px;">
                <div class="card-body p-5">
                    <h2 class="text-center fw-bold mb-4" style="color: #1a237e;">Registro</h2>
                    <form action="<?= \URL_BASE; ?>registro" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre Completo</label>
                            <input type="text" name="nombre" class="form-control" required placeholder="Tu nombre">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Correo Electrón emotion</label>
                            <input type="email" name="email" class="form-control" required placeholder="nombre@correo.com">
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" required placeholder="••••••••">
                        </div>
                        <button type="submit" class="btn btn-success w-100 shadow-sm py-2">Crear Cuenta</button>
                    </form>
                    <div class="mt-4 text-center">
                        <p class="small text-muted">¿Ya tienes cuenta? <a href="<?= \URL_BASE; ?>login" class="fw-bold text-decoration-none">Inicia sesión</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>