<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Idea - TECNOSIA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #212529;
        }
        .form-container {
            max-width: 700px;
            margin: 40px auto;
        }
        .form-card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
            padding: 30px;
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
        }
        .form-control, .form-select {
            border-radius: 6px;
            border: 1px solid #ced4da;
            padding: 10px 15px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
    </style>
</head>
<body>

<div class="container form-container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0" style="color: #2c3e50;">✏️ Editar Idea</h3>
        <a href="<?= \URL_BASE; ?>dashboard" class="btn btn-outline-secondary rounded-3 px-3">Cancelar</a>
    </div>

    <div class="form-card">
        <p class="text-secondary mb-4">Modifica los detalles de tu idea. Al guardar los cambios, el sistema experto generará un nuevo diagnóstico automáticamente.</p>

        <!-- Asegúrate de que el action apunte a 'ideas/actualizar' como en el enrutador -->
        <form action="<?= \URL_BASE; ?>ideas/actualizar" method="POST">
            
            <!-- Campo oculto con el ID de la idea -->
            <input type="hidden" name="id" value="<?= htmlspecialchars($idea['id']); ?>">

            <div class="mb-3">
                <label for="titulo" class="form-label">Título de la Idea</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?= htmlspecialchars($idea['titulo']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="sector" class="form-label">Sector / Industria</label>
                <select class="form-select" id="sector" name="sector" required>
                    <?php 
                    $sectores = [
                        "Tecnología / Software (SaaS)", "E-commerce / Retail", "Agrotech", 
                        "Fintech", "Salud Digital / Healthtech", "Educación / Edtech", 
                        "Gastronomía / Alimentos", "Servicios Profesionales / Consultoría", 
                        "Logística y Transporte", "Medio Ambiente / Cleantech", 
                        "Entretenimiento y Medios", "Turismo y Hospitalidad", "General"
                    ];
                    foreach ($sectores as $s) {
                        $selected = ($idea['sector'] === $s) ? 'selected' : '';
                        echo "<option value=\"$s\" $selected>$s</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-4">
                <label for="descripcion" class="form-label">Descripción Detallada</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="6" required><?= htmlspecialchars($idea['descripcion']); ?></textarea>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary py-2 fw-semibold rounded-3">Actualizar Diagnóstico</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>