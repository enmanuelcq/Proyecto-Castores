<!-- modalRegistrarPartido.php -->
<div class="modal fade" id="modalRegistrarPartido" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle-fill me-2"></i>Registrar Nuevo Partido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formRegistrarPartido" method="POST" action="">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Información básica -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de Partido</label>
                            <select class="form-select" name="tipo" required>
                                <option value="">Seleccionar...</option>
                                <option value="Torneo">Torneo</option>
                                <option value="Amistoso">Amistoso</option>
                                <option value="Liga">Liga</option>
                                <option value="Copa">Copa</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Categoría</label>
                            <select class="form-select" name="categoria" required>
                                <option value="">Seleccionar...</option>
                                <?php 
                                $categorias = ['Sub-8', 'Sub-10', 'Sub-12', 'Sub-14', 'Sub-16', 'Sub-18'];
                                foreach($categorias as $cat): ?>
                                    <option value="<?php echo $cat; ?>"><?php echo $cat; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Rival</label>
                            <input type="text" class="form-control" name="rival" placeholder="Nombre del equipo rival" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estadio / Cancha</label>
                            <input type="text" class="form-control" name="estadio" placeholder="Nombre del estadio" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Fecha</label>
                            <input type="date" class="form-control" name="fecha" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Hora</label>
                            <input type="time" class="form-control" name="hora" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Duración (minutos)</label>
                            <input type="number" class="form-control" name="duracion" placeholder="90" value="90" min="1">
                        </div>
                        
                        <!-- Estado inicial -->
                        <div class="col-12">
                            <label class="form-label fw-bold">Estado Inicial</label>
                            <select class="form-select" name="status">
                                <option value="Por Jugar">Por Jugar</option>
                                <option value="Programado">Programado</option>
                            </select>
                        </div>
                        
                        
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmarRegistro" required>
                                <label class="form-check-label" for="confirmarRegistro">
                                    Confirmo que los datos son correctos
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-2"></i>Registrar Partido
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>