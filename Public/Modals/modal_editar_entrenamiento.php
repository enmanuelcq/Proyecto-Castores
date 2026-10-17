<?php foreach($entrenamientos_filtrados as $e): ?>
<div class="modal fade" id="modalEditarEntrenamiento<?php echo $e['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-pencil-square me-2 text-warning"></i>
                    Editar Entrenamiento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                
                <!-- ===== CABECERA ===== -->
                <div class="atleta-header-modal mb-4">
                    <div class="atleta-info-modal">
                        <h4><?php echo $e['tipo']; ?> - <?php echo $e['categoria']; ?></h4>
                        <div class="atleta-badges">
                            <span class="badge bg-secondary"><?php echo $e['fecha']; ?></span>
                            <span class="badge bg-secondary"><?php echo $e['hora_inicio']; ?> - <?php echo $e['hora_fin']; ?></span>
                        </div>
                    </div>
                    <div class="atleta-actions-modal">
                        <span class="text-white-50 small">
                            <i class="bi bi-person-badge me-1"></i>
                            ID: <?php echo $e['id']; ?>
                        </span>
                    </div>
                </div>

                <form id="formEditarEntrenamiento<?php echo $e['id']; ?>" method="POST" action="./Controller/ActualizarEntrenamiento_controller.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="id" value="<?php echo $e['id']; ?>">
                    <input type="hidden" name="entrenador_cedula" value="<?php echo $_SESSION['usuario']['usuario_cedula'] ?? ''; ?>">
                    <input type="hidden" name="entrenador_nombre" value="<?php echo ($_SESSION['usuario']['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario']['usuario_apellido'] ?? ''); ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="custom-form-label">Fecha</label>
                            <input type="date" class="form-control custom-input" name="fecha" value="<?php echo $e['fecha']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="custom-form-label">Hora Inicio</label>
                            <input type="time" class="form-control custom-input" name="hora_inicio" value="<?php echo $e['hora_inicio']; ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="custom-form-label">Hora Fin</label>
                            <input type="time" class="form-control custom-input" name="hora_fin" value="<?php echo $e['hora_fin']; ?>" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="custom-form-label">Categoría</label>
                            <select class="form-select custom-input" name="categoria" required>
                                <?php 
                                $categorias = ['Sub-4','Sub-5','Sub-6','Sub-7','Sub-8','Sub-9','Sub-10','Sub-11','Sub-12','Sub-13','Sub-14','Sub-15','Sub-16','Sub-17','Sub-18'];
                                foreach($categorias as $cat): ?>
                                <option value="<?php echo $cat; ?>" <?php echo ($e['categoria'] == $cat) ? 'selected' : ''; ?>>
                                    <?php echo $cat; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="custom-form-label">Tipo</label>
                            <select class="form-select custom-input" name="tipo" required>
                                <option value="Físico" <?php echo ($e['tipo'] == 'Físico') ? 'selected' : ''; ?>>Físico</option>
                                <option value="Técnico" <?php echo ($e['tipo'] == 'Técnico') ? 'selected' : ''; ?>>Técnico</option>
                                <option value="Táctico" <?php echo ($e['tipo'] == 'Táctico') ? 'selected' : ''; ?>>Táctico</option>
                                <option value="Mixto" <?php echo ($e['tipo'] == 'Mixto') ? 'selected' : ''; ?>>Mixto</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="custom-form-label">Lugar</label>
                            <input type="text" class="form-control custom-input" name="lugar" value="<?php echo $e['lugar']; ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="custom-form-label">Estado</label>
                            <select class="form-select custom-input" name="estado">
                                <option value="Programado" <?php echo ($e['estado'] == 'Programado') ? 'selected' : ''; ?>>Programado</option>
                                <option value="En curso" <?php echo ($e['estado'] == 'En curso') ? 'selected' : ''; ?>>En curso</option>
                                <option value="Finalizado" <?php echo ($e['estado'] == 'Finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Total Atletas</label>
                            <input type="number" class="form-control custom-input" name="total_atletas" value="<?php echo $e['total_atletas']; ?>" min="0" required>
                        </div>
                        
                        <div class="col-12">
                            <label class="custom-form-label">Descripción</label>
                            <textarea class="form-control custom-input" name="descripcion" rows="3"><?php echo $e['descripcion']; ?></textarea>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-warning px-4 rounded-pill fw-bold text-dark">
                            <i class="bi bi-save me-2"></i>Actualizar
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
<?php endforeach; ?>