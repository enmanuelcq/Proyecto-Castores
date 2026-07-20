<!-- modalEditarPartido.php -->
<div class="modal fade" id="modalEditarPartido<?php echo $id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-fill me-2"></i>Editar Partido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditarPartido_<?php echo $id; ?>" method="POST" action="">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- ID del partido (oculto) -->
                        <input type="hidden" name="id_partido" value="<?php echo $id; ?>">
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de Partido</label>
                            <select class="form-select" name="tipo" required>
                                <option value="Torneo" <?php echo ($partido['tipo'] == 'Torneo') ? 'selected' : ''; ?>>Torneo</option>
                                <option value="Amistoso" <?php echo ($partido['tipo'] == 'Amistoso') ? 'selected' : ''; ?>>Amistoso</option>
                                <option value="Liga" <?php echo ($partido['tipo'] == 'Liga') ? 'selected' : ''; ?>>Liga</option>
                                <option value="Copa" <?php echo ($partido['tipo'] == 'Copa') ? 'selected' : ''; ?>>Copa</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Categoría</label>
                            <select class="form-select" name="categoria" required>
                                <option value="Sub-8" <?php echo ($partido['categoria'] == 'Sub-8') ? 'selected' : ''; ?>>Sub-8</option>
                                <option value="Sub-10" <?php echo ($partido['categoria'] == 'Sub-10') ? 'selected' : ''; ?>>Sub-10</option>
                                <option value="Sub-12" <?php echo ($partido['categoria'] == 'Sub-12') ? 'selected' : ''; ?>>Sub-12</option>
                                <option value="Sub-14" <?php echo ($partido['categoria'] == 'Sub-14') ? 'selected' : ''; ?>>Sub-14</option>
                                <option value="Sub-16" <?php echo ($partido['categoria'] == 'Sub-16') ? 'selected' : ''; ?>>Sub-16</option>
                                <option value="Sub-18" <?php echo ($partido['categoria'] == 'Sub-18') ? 'selected' : ''; ?>>Sub-18</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Rival</label>
                            <input type="text" class="form-control" name="rival" value="<?php echo $partido['rival']; ?>" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estadio / Cancha</label>
                            <input type="text" class="form-control" name="estadio" value="<?php echo $partido['estadio']; ?>" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Fecha</label>
                            <?php 
                            $fecha_editar = DateTime::createFromFormat('d/m/Y', $partido['fecha']);
                            $fecha_value = $fecha_editar ? $fecha_editar->format('Y-m-d') : '';
                            ?>
                            <input type="date" class="form-control" name="fecha" value="<?php echo $fecha_value; ?>" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Hora</label>
                            <input type="time" class="form-control" name="hora" value="<?php echo $partido['hora']; ?>" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Duración (minutos)</label>
                            <input type="number" class="form-control" name="duracion" value="<?php echo $partido['duracion'] ?: '90'; ?>" min="1">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold">Estado</label>
                            <select class="form-select" name="status">
                                <option value="Por Jugar" <?php echo ($partido['status'] == 'Por Jugar') ? 'selected' : ''; ?>>Por Jugar</option>
                                <option value="Programado" <?php echo ($partido['status'] == 'Programado') ? 'selected' : ''; ?>>Programado</option>
                                <option value="Ganado" <?php echo ($partido['status'] == 'Ganado') ? 'selected' : ''; ?>>Ganado</option>
                                <option value="Perdido" <?php echo ($partido['status'] == 'Perdido') ? 'selected' : ''; ?>>Perdido</option>
                                <option value="Empatado" <?php echo ($partido['status'] == 'Empatado') ? 'selected' : ''; ?>>Empatado</option>
                                <option value="Cancelado" <?php echo ($partido['status'] == 'Cancelado') ? 'selected' : ''; ?>>Cancelado</option>
                            </select>
                        </div>
                        
                        <?php if($partido['status'] == 'Ganado' || $partido['status'] == 'Perdido' || $partido['status'] == 'Empatado'): ?>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Goles a Favor</label>
                            <input type="number" class="form-control" name="goles_a_favor" value="<?php echo $partido['goles_a_favor']; ?>" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Goles en Contra</label>
                            <input type="number" class="form-control" name="goles_contra" value="<?php echo $partido['goles_contra']; ?>" min="0">
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-info text-white">
                        <i class="bi bi-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>