<!-- views/dashboard/ideas.php -->
<div class="container mt-4">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark"><i class="fas fa-rocket text-primary"></i> Mi Tablero de Innovación</h2>
        <a href="<?= \URL_BASE; ?>ideas/crear" class="btn btn-success shadow-sm px-4">
            <i class="fas fa-plus"></i> Registrar Nueva Hipótesis
        </a>
    </div>

    <!-- Alertas -->
    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <strong>¡Excelente!</strong> Tu idea ha sido registrada y procesada por Tecnosia AI.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <?php if (empty($ideas)): ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted italic">Aún no has registrado ninguna hipótesis de negocio.</p>
            </div>
        <?php else: ?>
            <?php foreach ($ideas as $idea): ?>
                <div class="col-12 mb-4">
                    <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
                        
                        <!-- Cabecera de Tarjeta -->
                        <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 fw-bold text-primary"><?= htmlspecialchars($idea['titulo']); ?></h4>
                            <span class="badge rounded-pill bg-light text-dark border px-3"><?= htmlspecialchars($idea['sector']); ?></span>
                        </div>
                        
                        <div class="card-body pt-2">
                            <!-- Descripción Principal -->
                            <p class="text-secondary"><?= nl2br(htmlspecialchars($idea['descripcion'])); ?></p>
                            
                            <hr class="opacity-25">

                            <!-- SECCIÓN DE DIAGNÓSTICO IA -->
                            <div class="ai-diagnostic-container">
                                <h6 class="text-info mb-3 d-flex align-items-center gap-2">
                                    <i class="fas fa-brain"></i> Diagnóstico Experimental de IA
                                </h6>
                                
                                <?php if (!empty($idea['ai_data'])): ?>
                                    <!-- Caja de Análisis -->
                                    <div class="p-3 mb-3 bg-light rounded border-start border-info border-4 shadow-sm">
                                        <p class="small mb-0">
                                            <strong>Análisis:</strong> <?= htmlspecialchars($idea['ai_data']['coherence_analysis']); ?>
                                        </p>
                                    </div>

                                    <!-- Fila de Riesgos y Sugerencias -->
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="h-100 p-2">
                                                <label class="text-danger small fw-bold d-block mb-1">RIESGOS:</label>
                                                <p class="small text-muted mb-0"><?= htmlspecialchars($idea['ai_data']['risks_identified']); ?></p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="h-100 p-2">
                                                <label class="text-success small fw-bold d-block mb-1">SUGERENCIAS:</label>
                                                <p class="small text-muted mb-0"><?= htmlspecialchars($idea['ai_data']['focus_suggestions']); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-warning py-2 mt-2 small border-0">
                                        <i class="fas fa-hourglass-half fa-spin"></i> No hay diagnóstico disponible para esta hipótesis.
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Pie de Tarjeta -->
                        <div class="card-footer bg-white border-0 pb-3 d-flex justify-content-between align-items-center">
                            <small class="text-muted font-monospace">ID: #<?= $idea['id']; ?> | <?= date('d/m/Y', strtotime($idea['created_at'])); ?></small>
                            <a href="<?= \URL_BASE; ?>ideas/eliminar?id=<?= $idea['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar esta idea?');">
                                <i class="fas fa-trash-alt"></i> Eliminar
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<style>
    .card { transition: transform 0.2s; }
    .card:hover { transform: translateY(-3px); }
    .border-info { border-color: #0dcaf0 !important; }
</style>