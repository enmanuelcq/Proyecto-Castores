<!-- Modal Editar Partido -->
<div class="modal fade" id="modalEditarPartido<?php echo $partido['partido_id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header  text-white">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-fill me-2"></i>Editar Partido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="Controller/EditarPartidoController.php" method="POST" id="formEditarPartido_<?php echo $partido['partido_id']; ?>">
                <input type="hidden" name="partido_id" value="<?php echo $partido['partido_id']; ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de Partido</label>
                            <select class="form-select" name="tipo_partido" required>
                                <option value="Amistoso" <?php echo ($partido['tipo_partido'] ?? 'Amistoso') == 'Amistoso' ? 'selected' : ''; ?>>Amistoso</option>
                                <option value="Torneo" <?php echo ($partido['tipo_partido'] ?? '') == 'Torneo' ? 'selected' : ''; ?>>Torneo</option>
                                <option value="Liga" <?php echo ($partido['tipo_partido'] ?? '') == 'Liga' ? 'selected' : ''; ?>>Liga</option>
                                <option value="Copa" <?php echo ($partido['tipo_partido'] ?? '') == 'Copa' ? 'selected' : ''; ?>>Copa</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre Competencia</label>
                            <input type="text" class="form-control" name="nombre_competencia" 
                                   value="<?php echo htmlspecialchars($partido['nombre_competencia'] ?? ''); ?>"
                                   placeholder="Solo si es Torneo/Liga/Copa">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Categoría</label>
                            <select class="form-select" name="categoria_id" required>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat->getId(); ?>" 
                                        <?php echo ($cat->getId() == $partido['categoria_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat->getSubCategoria()); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Equipo Rival</label>
                            <select class="form-select" name="equipo_id" id="selectEquipoRivalEdit">
                                <?php foreach ($equiposRivales as $equipo): ?>
                                    <option value="<?php echo $equipo->getId(); ?>" 
                                        <?php echo ($equipo->getId() == $partido['equipo_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($equipo->getNombre()); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="nuevo">➕ Agregar nuevo equipo...</option>
                            </select>
                            <div id="divNuevoEquipoEdit" style="display:none; margin-top:5px;">
                                <input type="text" class="form-control" name="nuevo_equipo" 
                                       placeholder="Nombre del nuevo equipo" id="inputNuevoEquipoEdit">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Campo / Estadio</label>
                            <select class="form-select" name="campo_id" id="selectCampoEdit">
                                <?php foreach ($campos as $campo): ?>
                                    <option value="<?php echo $campo->getId(); ?>" 
                                        <?php echo ($campo->getId() == $partido['campo_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($campo->getNombre()); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="nuevo">➕ Agregar nuevo campo...</option>
                            </select>
                            <div id="divNuevoCampoEdit" style="display:none; margin-top:5px;">
                                <div class="mb-2">
                                    <input type="text" class="form-control" name="nuevo_campo" 
                                           placeholder="Nombre del nuevo campo" id="inputNuevoCampoEdit">
                                </div>
                                <div>
                                    <input type="url" class="form-control" name="mapa_campo" 
                                           placeholder="URL de Google Maps (opcional)" id="inputMapaCampoEdit">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Fecha y Hora del Partido</label>
                            <?php 
                            $fecha_obj = null;
                            if (!empty($partido['fecha_partido']) && $partido['fecha_partido'] !== '0000-00-00 00:00:00') {
                                try {
                                    $fecha_obj = new DateTime($partido['fecha_partido']);
                                } catch (Exception $e) {
                                    $fecha_obj = null;
                                }
                            }
                            $fecha_hora_value = $fecha_obj ? $fecha_obj->format('Y-m-d\TH:i') : '';
                            ?>
                            <input type="datetime-local" class="form-control" name="fecha_hora" 
                                   value="<?php echo $fecha_hora_value; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Duración</label>
                            <input type="text" class="form-control" name="duracion" 
                                   value="<?php echo htmlspecialchars($partido['duracion']); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estado</label>
                            <select class="form-select" name="estado" required>
                                <option value="Pendiente" <?php echo ($partido['estado'] ?? 'Pendiente') == 'Pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                <option value="En Curso" <?php echo ($partido['estado'] ?? '') == 'En Curso' ? 'selected' : ''; ?>>En Curso</option>
                                <option value="Finalizado" <?php echo ($partido['estado'] ?? '') == 'Finalizado' ? 'selected' : ''; ?>>Finalizado</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmarEdicion" required>
                                <label class="form-check-label" for="confirmarEdicion">
                                    Confirmo que los cambios son correctos
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('selectEquipoRivalEdit').addEventListener('change', function() {
        var div = document.getElementById('divNuevoEquipoEdit');
        var input = document.getElementById('inputNuevoEquipoEdit');
        if (this.value === 'nuevo') {
            div.style.display = 'block';
            input.setAttribute('required', 'required');
            this.removeAttribute('required');
        } else {
            div.style.display = 'none';
            input.removeAttribute('required');
            this.setAttribute('required', 'required');
        }
    });

    document.getElementById('selectCampoEdit').addEventListener('change', function() {
        var div = document.getElementById('divNuevoCampoEdit');
        var inputNombre = document.getElementById('inputNuevoCampoEdit');
        var inputMapa = document.getElementById('inputMapaCampoEdit');
        if (this.value === 'nuevo') {
            div.style.display = 'block';
            inputNombre.setAttribute('required', 'required');
            this.removeAttribute('required');
        } else {
            div.style.display = 'none';
            inputNombre.removeAttribute('required');
            inputMapa.removeAttribute('required');
            this.setAttribute('required', 'required');
        }
    });

    document.getElementById('formEditarPartido_<?php echo $partido['partido_id']; ?>').addEventListener('submit', function(e) {
        var equipoSelect = document.getElementById('selectEquipoRivalEdit');
        var nuevoEquipo = document.getElementById('inputNuevoEquipoEdit');
        if (equipoSelect.value === 'nuevo' && !nuevoEquipo.value.trim()) {
            alert('Debes escribir el nombre del nuevo equipo.');
            nuevoEquipo.focus();
            e.preventDefault();
            return;
        }

        var campoSelect = document.getElementById('selectCampoEdit');
        var nuevoCampo = document.getElementById('inputNuevoCampoEdit');
        if (campoSelect.value === 'nuevo' && !nuevoCampo.value.trim()) {
            alert('Debes escribir el nombre del nuevo campo.');
            nuevoCampo.focus();
            e.preventDefault();
            return;
        }
    });
});
</script>