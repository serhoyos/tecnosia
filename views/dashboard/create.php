<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Idea - TECNOSIA</title>
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
            border-color: #198754;
            box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
        }
    </style>
</head>
<body>

<div class="container form-container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0" style="color: #2c3e50;">💡 Registrar Nueva Idea</h3>
        <a href="<?= \URL_BASE; ?>dashboard" class="btn btn-outline-secondary rounded-3 px-3">Volver al Dashboard</a>
    </div>

    <div class="form-card">
        <p class="text-secondary mb-4">Describe tu idea de negocio. Recuerda incluir el problema, el cliente objetivo, la propuesta de valor y tu modelo de monetización para obtener un mejor diagnóstico.</p>

        <!-- Asegúrate de que el action apunte a 'ideas/guardar' como configuramos en el enrutador -->
        <form action="<?= \URL_BASE; ?>ideas/guardar" method="POST">
            
            <div class="mb-3">
                <label for="titulo" class="form-label">Título de la Idea</label>
                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Ej. Plataforma SaaS para gestión de inventarios" required>
            </div>

            <div class="mb-3">
                <label for="sector" class="form-label">Sector / Industria</label>
                <select class="form-select" id="sector" name="sector" required>
                    <option value="" selected disabled>Selecciona el sector principal...</option>
                    <option value="Tecnología / Software (SaaS)">Tecnología / Software (SaaS)</option>
                    <option value="E-commerce / Retail">E-commerce / Retail</option>
                    <option value="Agrotech">Agrotech</option>
                    <option value="Fintech">Fintech</option>
                    <option value="Salud Digital / Healthtech">Salud Digital / Healthtech</option>
                    <option value="Educación / Edtech">Educación / Edtech</option>
                    <option value="Gastronomía / Alimentos">Gastronomía / Alimentos</option>
                    <option value="Servicios Profesionales / Consultoría">Servicios Profesionales / Consultoría</option>
                    <option value="Logística y Transporte">Logística y Transporte</option>
                    <option value="Medio Ambiente / Cleantech">Medio Ambiente / Cleantech</option>
                    <option value="Entretenimiento y Medios">Entretenimiento y Medios</option>
                    <option value="Turismo y Hospitalidad">Turismo y Hospitalidad</option>
                    <option value="General">General / Otro</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="descripcion" class="form-label">Descripción Detallada</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="6" placeholder="Describe a quién va dirigido, qué problema resuelve, cómo funciona y cómo planeas monetizarlo..." required></textarea>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-success py-2 fw-semibold rounded-3">Guardar y Analizar Idea</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>