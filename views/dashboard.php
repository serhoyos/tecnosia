<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Tecnosia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">🚀 Tecnosia</a>
            <div class="d-flex">
                <span class="navbar-text me-3">
                    Bienvenido, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                </span>
                <a href="logout" class="btn btn-outline-danger btn-sm">Salir</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="list-group shadow-sm">
                    <a href="#" class="list-group-item list-group-item-action active">
                        <i class="bi bi-house-door"></i> Inicio
                    </a>
                    <a href="ideas" class="list-group-item list-group-item-action">
                        <i class="bi bi-lightbulb"></i> Mis Ideas
                    </a>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h3>Resumen de Actividad</h3>
                        <p class="text-muted">Gestiona y valida tus ideas de negocio desde aquí.</p>
                        
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white text-center p-3">
                                    <h1>0</h1>
                                    <h6>Ideas Registradas</h6>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white text-center p-3">
                                    <h1>0</h1>
                                    <h6>Ideas Validadas</h6>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <a href="ideas/crear" class="btn btn-primary btn-lg">
                                <i class="bi bi-plus-circle"></i> Registrar Nueva Idea
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>