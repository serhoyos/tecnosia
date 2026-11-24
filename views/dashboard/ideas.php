<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - TECNOSIA</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Fondo gris muy claro */
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .dashboard-container {
            max-width: 800px; /* Ancho máximo para mantener la legibilidad de la tarjeta */
            margin: 0 auto;
        }
        .idea-card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .diagnostic-card {
            background-color: #f8f9fa;
            border-radius: 8px;
            border: 1px solid #e9ecef;
        }
        .progress {
            height: 12px;
            background-color: #e9ecef;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="container py-5 dashboard-container">
    
    <!-- Encabezado Superior -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <!-- Reemplaza la variable de sesión si la llamas diferente -->
        <h2 class="m-0 fw-bold" style="color: #2c3e50;">Hola, <?= htmlspecialchars($_SESSION['nombre'] ?? 'Sergio Hoyos'); ?> 👋</h2>
        <a href="<?= \URL_BASE; ?>logout" class="btn btn-danger px-4 rounded-3">Cerrar sesión</a>
    </div>

    <!-- Título de Sección y Botón -->
    <div class="mb-4">
        <h3 class="fw-bold mb-3" style="color: #2c3e50;">💡 Mis Ideas</h3>
        <a href="<?= \URL_BASE; ?>ideas/create" class="btn btn-success px-4 rounded-3 fw-semibold">+ Nueva Idea</a>
    </div>

    <!-- Validar si hay ideas registradas -->
    <?php if (!empty($ideas)): ?>
        
        <!-- Ciclo para imprimir cada idea -->
        <?php foreach ($ideas as $idea): ?>
            <div class="card idea-card mb-4 p-2">
                <div class="card-body">
                    
                    <!-- Información de la Idea -->
                    <h4 class="card-title fw-bold mb-3"><?= htmlspecialchars($idea['titulo']); ?></h4>
                    <p class="card-text text-secondary mb-3" style="line-height: 1.6;">
                        <?= nl2br(htmlspecialchars($idea['descripcion'])); ?>
                    </p>
                    <p class="mb-3 text-dark"><strong>Sector:</strong> <?= htmlspecialchars($idea['sector']); ?></p>

                    <!-- Botones de Acción -->
                    <div class="d-flex gap-2 mb-4">
                        <a href="<?= \URL_BASE; ?>ideas/edit?id=<?= $idea['id']; ?>" class="btn btn-primary btn-sm px-3 rounded-3">Editar</a>
                        
                        <form action="<?= \URL_BASE; ?>ideas/delete" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta idea?');">
                            <input type="hidden" name="id" value="<?= $idea['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm px-3 rounded-3">Eliminar</button>
                        </form>
                    </div>

                    <!-- Diagnóstico (Solo se muestra si existe el JSON) -->
                    <?php 
                    $diag = isset($idea['result_json']) ? json_decode($idea['result_json'], true) : null;
                    if ($diag): 
                        // Variables del diagnóstico
                        $score = $diag['score'] ?? 0;
                        $semaforo = $diag['semaforo'] ?? 'GRIS';
                        $veredicto = $diag['veredicto'] ?? '';
                        $porcentaje = ($score / 10) * 100;

                        // Lógica de colores según el semáforo
                        $colorSemaforo = 'text-secondary';
                        $bgBarra = 'bg-secondary';

                        if ($semaforo === 'VERDE') {
                            $colorSemaforo = 'text-success';
                            $bgBarra = 'bg-success';
                        } elseif ($semaforo === 'AMARILLO') {
                            $colorSemaforo = 'text-warning'; // El amarillo de Bootstrap
                            $bgBarra = 'bg-warning';
                            // Si el warning no te parece lo bastante fuerte, puedes usar un estilo inline en la etiqueta
                        } elseif ($semaforo === 'ROJO') {
                            $colorSemaforo = 'text-danger';
                            $bgBarra = 'bg-danger';
                        }
                    ?>
                        <div class="diagnostic-card p-4 mt-3">
                            <h5 class="fw-bold mb-3">🧠 Diagnóstico</h5>
                            
                            <!-- Score y Barra de Progreso -->
                            <p class="fw-bold mb-1">Score:</p>
                            <div class="progress mb-1">
                                <div class="progress-bar <?= $bgBarra; ?>" role="progressbar" style="width: <?= $porcentaje; ?>%;" aria-valuenow="<?= $score; ?>" aria-valuemin="0" aria-valuemax="10"></div>
                            </div>
                            <small class="text-secondary d-block mb-3"><?= $score; ?>/10</small>

                            <!-- Semáforo y Veredicto -->
                            <p class="fw-bold mb-2 <?= $colorSemaforo; ?>" style="font-size: 1.1rem;"><?= htmlspecialchars($semaforo); ?></p>
                            <p class="mb-3 text-dark"><strong>Veredicto:</strong> <?= htmlspecialchars($veredicto); ?></p>

                            <!-- Insights -->
                            <?php if (!empty($diag['insights'])): ?>
                                <p class="fw-bold mb-1">Insights</p>
                                <ul class="text-dark mb-3">
                                    <?php foreach ($diag['insights'] as $insight): ?>
                                        <li><?= htmlspecialchars($insight); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <!-- Riesgos -->
                            <?php if (!empty($diag['riesgos'])): ?>
                                <p class="fw-bold mb-1">Riesgos</p>
                                <ul class="text-dark mb-3">
                                    <?php foreach ($diag['riesgos'] as $riesgo): ?>
                                        <li><?= htmlspecialchars($riesgo); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                            <!-- Recomendaciones -->
                            <?php if (!empty($diag['recomendaciones'])): ?>
                                <p class="fw-bold mb-1">Recomendaciones</p>
                                <ul class="text-dark mb-0">
                                    <?php foreach ($diag['recomendaciones'] as $rec): ?>
                                        <li><?= htmlspecialchars($rec); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?> <!-- Fin Diagnóstico -->

                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?> <!-- Fin Validar Ideas -->

</div>

</body>
</html>