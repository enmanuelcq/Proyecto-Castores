<!-- Modal Registrar Partido -->
<div class="modal fade" id="modalRegistrarPartido" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle-fill me-2"></i>Registrar Partido
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="Controller/RegistrarPartidoController.php" method="POST" id="formRegistrarPartido">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- ===== TIPO DE PARTIDO ===== -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de Partido</label>
                            <select class="form-select" name="tipo_partido" id="tipoPartido" required>
                                <option value="Amistoso">Amistoso</option>
                                <option value="Torneo">Torneo</option>
                                <option value="Liga">Liga</option>
                                <option value="Copa">Copa</option>
                            </select>
                        </div>

                        <!-- ===== NOMBRE DE COMPETENCIA (condicional) ===== -->
                        <div class="col-md-6" id="divNombreCompetencia" style="display:none;">
                            <label class="form-label fw-bold">Nombre de la Competencia</label>
                            <input type="text" class="form-control" name="nombre_competencia" 
                                   placeholder="Ej. Torneo Intercolegial 2026" id="inputNombreCompetencia">
                            <small class="text-muted">Obligatorio para Torneo, Liga o Copa</small>
                        </div>

                        <!-- ===== CATEGORÍA ===== -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Categoría</label>
                            <select class="form-select" name="categoria_id" required>
                                <option value="">Seleccionar...</option>
                                <?php foreach ($categorias as $cat): ?>
                                    <option value="<?php echo $cat->getId(); ?>">
                                        <?php echo htmlspecialchars($cat->getSubCategoria()); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- ===== EQUIPO RIVAL (con opción "Otro") ===== -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Equipo Rival</label>
                            <select class="form-select" name="equipo_id" id="selectEquipoRival">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($equiposRivales as $equipo): ?>
                                    <option value="<?php echo $equipo->getId(); ?>">
                                        <?php echo htmlspecialchars($equipo->getNombre()); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="nuevo">➕ Agregar nuevo equipo...</option>
                            </select>
                            <div id="divNuevoEquipo" style="display:none; margin-top:5px;">
                                <input type="text" class="form-control" name="nuevo_equipo" 
                                       placeholder="Nombre del nuevo equipo" id="inputNuevoEquipo">
                            </div>
                        </div>

                        <!-- ===== CAMPO (con opción "Otro") ===== -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Campo / Estadio</label>
                            <select class="form-select" name="campo_id" id="selectCampo">
                                <option value="">Seleccionar...</option>
                                <?php foreach ($campos as $campo): ?>
                                    <option value="<?php echo $campo->getId(); ?>">
                                        <?php echo htmlspecialchars($campo->getNombre()); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="nuevo">➕ Agregar nuevo campo...</option>
                            </select>
                            <div id="divNuevoCampo" style="display:none; margin-top:5px;">
                                <div class="mb-2">
                                    <input type="text" class="form-control" name="nuevo_campo" 
                                           placeholder="Nombre del nuevo campo" id="inputNuevoCampo">
                                </div>
                                <div>
                                    <input type="url" class="form-control" name="mapa_campo" 
                                           placeholder="URL de Google Maps (opcional)" id="inputMapaCampo">
                                    <small class="text-muted">Ej: https://maps.google.com/... (opcional)</small>
                                </div>
                            </div>
                        </div>

                        <!-- ===== FECHA Y HORA (un solo campo) ===== -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Fecha y Hora del Partido</label>
                            <input type="datetime-local" class="form-control" name="fecha_hora" 
                                   value="<?php echo date('Y-m-d\TH:i'); ?>" required>
                            <small class="text-muted">Selecciona la fecha y hora del partido</small>
                        </div>

                        <!-- ===== DURACIÓN ===== -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Duración</label>
                            <input type="text" class="form-control" name="duracion" 
                                   placeholder="Ej. 90 min, 45 min, Penales" 
                                   value="90 min" required>
                            <small class="text-muted">Ej: 90 min, 45 min, Penales, etc.</small>
                        </div>

                        <!-- ===== CONFIRMACIÓN ===== -->
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
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Registrar Partido</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('tipoPartido').addEventListener('change', function() {
        var div = document.getElementById('divNombreCompetencia');
        var input = document.getElementById('inputNombreCompetencia');
        if (this.value === 'Torneo' || this.value === 'Liga' || this.value === 'Copa') {
            div.style.display = 'block';
            input.setAttribute('required', 'required');
        } else {
            div.style.display = 'none';
            input.removeAttribute('required');
        }
    });

    document.getElementById('selectEquipoRival').addEventListener('change', function() {
        var div = document.getElementById('divNuevoEquipo');
        var input = document.getElementById('inputNuevoEquipo');
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

    document.getElementById('selectCampo').addEventListener('change', function() {
        var div = document.getElementById('divNuevoCampo');
        var inputNombre = document.getElementById('inputNuevoCampo');
        var inputMapa = document.getElementById('inputMapaCampo');
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

    document.getElementById('formRegistrarPartido').addEventListener('submit', function(e) {
        var tipoPartido = document.getElementById('tipoPartido').value;
        var nombreCompetencia = document.getElementById('inputNombreCompetencia');

        if (['Torneo', 'Liga', 'Copa'].includes(tipoPartido)) {
            if (!nombreCompetencia.value.trim()) {
                alert('Debes especificar el nombre de la competencia.');
                nombreCompetencia.focus();
                e.preventDefault();
                return;
            }
        }

        var equipoSelect = document.getElementById('selectEquipoRival');
        var nuevoEquipo = document.getElementById('inputNuevoEquipo');
        if (equipoSelect.value === 'nuevo' && !nuevoEquipo.value.trim()) {
            alert('Debes escribir el nombre del nuevo equipo.');
            nuevoEquipo.focus();
            e.preventDefault();
            return;
        }

        var campoSelect = document.getElementById('selectCampo');
        var nuevoCampo = document.getElementById('inputNuevoCampo');
        if (campoSelect.value === 'nuevo' && !nuevoCampo.value.trim()) {
            alert('Debes escribir el nombre del nuevo campo.');
            nuevoCampo.focus();
            e.preventDefault();
            return;
        }
    });
});
</script>