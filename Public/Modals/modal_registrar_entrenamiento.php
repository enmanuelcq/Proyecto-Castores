<div class="modal fade" id="modalRegistrarEntrenamiento" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-plus-circle-fill me-2 text-success"></i>
                    Registrar Entrenamiento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                
                <!-- ===== CABECERA ===== -->
                <div class="atleta-header-modal mb-4">
                    <div class="atleta-info-modal">
                        <h4>Nuevo Entrenamiento</h4>
                        <div class="atleta-badges">
                            <span class="badge bg-info">
                                <i class="bi bi-person-badge me-1"></i>
                                Entrenador: 26804373
                            </span>
                        </div>
                    </div>
                    <div class="atleta-actions-modal">
                        <span class="text-white small">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?php echo date('d/m/Y'); ?>
                        </span>
                    </div>
                </div>

                <form id="formRegistrarEntrenamiento" method="POST">
                    <!-- Campos ocultos -->
                    <input type="hidden" name="entrenador_cedula" value="26804373">
                    <input type="hidden" name="entrenador_nombre" value="Carlos Entrenador">
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="custom-form-label">Fecha</label>
                            <input type="date" class="form-control custom-input" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="custom-form-label">Hora Inicio</label>
                            <input type="time" class="form-control custom-input" name="hora_inicio" value="08:00" required>
                        </div>
                        <div class="col-md-4">
                            <label class="custom-form-label">Hora Fin</label>
                            <input type="time" class="form-control custom-input" name="hora_fin" value="10:00" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="custom-form-label">Categoría</label>
                            <select class="form-select custom-input" name="categoria" required>
                                <option value="">Seleccionar...</option>
                                <?php 
                                $categorias = ['Sub-8','Sub-10','Sub-12','Sub-14','Sub-16','Sub-18'];
                                foreach($categorias as $cat): ?>
                                <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="custom-form-label">Tipo</label>
                            <select class="form-select custom-input" name="tipo" required>
                                <option value="">Seleccionar...</option>
                                <option value="Físico">Físico</option>
                                <option value="Técnico">Técnico</option>
                                <option value="Táctico">Táctico</option>
                                <option value="Mixto">Mixto</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="custom-form-label">Lugar</label>
                            <input type="text" class="form-control custom-input" name="lugar" placeholder="Cancha Principal" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="custom-form-label">Estado</label>
                            <select class="form-select custom-input" name="estado">
                                <option value="Programado">Programado</option>
                                <option value="En curso">En curso</option>
                                <option value="Finalizado">Finalizado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Total Atletas</label>
                            <input type="number" class="form-control custom-input" name="total_atletas" placeholder="Número de atletas" min="0" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="custom-form-label">Descripción</label>
                            <textarea class="form-control custom-input" name="descripcion" rows="3" placeholder="Descripción del entrenamiento..."></textarea>
                        </div>
                        
                        <div class="col-12">
                            <div class="alert alert-info" style="background: rgba(13,202,240,0.08); border: 1px solid rgba(13,202,240,0.12); border-radius: 10px; padding: 10px 15px;">
                                <i class="bi bi-info-circle-fill me-2 text-info"></i>
                                <small class="text-white-50">
                                    Entrenador asignado: <strong class="text-white">Carlos Entrenador</strong> 
                                    <span class="text-white-50">(Cédula: 26804373)</span>
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success px-4 rounded-pill fw-bold">
                            <i class="bi bi-save me-2"></i>Registrar
                        </button>
                        <button type="button" class="btn btn-secondary px-4 rounded-pill fw-bold" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>