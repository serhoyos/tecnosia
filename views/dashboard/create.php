<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 text-primary"><i class="fas fa-lightbulb"></i> Registrar Nueva Hipótesis de Negocio</h4>
                </div>
                <div class="card-body p-4">
                    <form action="<?= \URL_BASE; ?>ideas/guardar" method="POST">
                        
                        <div class="mb-3">
                            <label for="titulo" class="form-label fw-bold">Título de la Idea</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" 
                                   placeholder="Ej: Plataforma de Monitoreo Agrotech" required>
                            <div class="form-text">Usa un nombre corto y descriptivo.</div>
                        </div>

                        <div class="mb-3">
                            <label for="sector" class="form-label fw-bold">Sector Industrial</label>
                            <select class="form-select" id="sector" name="sector">
                                <option value="General">General</option>
                                <option value="Agrotech">Agrotech</option>
                                <option value="Fintech">Fintech</option>
                                <option value="Salud Digital">Salud Digital</option>
                                <option value="Educación">Educación</option>
                                <option value="E-commerce">E-commerce</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="descripcion" class="form-label fw-bold">Descripción de la Hipótesis</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="5" 
                                      placeholder="Describe qué problema resuelves y cómo funciona tu solución..." required></textarea>
                            <div class="form-text">Entre más detalles des, mejor será el diagnóstico de la IA.</div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="<?= \URL_BASE; ?>dashboard" class="text-decoration-none text-muted">
                                <i class="fas fa-arrow-left"></i> Volver al tablero
                            </a>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="fas fa-magic"></i> Guardar y Analizar con IA
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="alert alert-info mt-4 border-0 shadow-sm">
                <small>
                    <i class="fas fa-info-circle"></i> Al hacer clic en "Guardar", 
                    nuestro sistema enviará tu propuesta a <strong>Gemini 3 Flash</strong> 
                    para generar un análisis de riesgos y coherencia en tiempo real.
                </small>
            </div>
        </div>
    </div>
</div>