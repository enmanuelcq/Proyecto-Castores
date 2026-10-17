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
                                Entrenador: <?php echo $_SESSION['usuario']['usuario_cedula'] ?? ''; ?>
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

                <form id="formRegistrarEntrenamiento" method="POST" enctype="multipart/form-data" action="./Controller/RegistrarEntrenamiento_controller.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <!-- Campos ocultos -->
                    <input type="hidden" name="entrenador_nombre" value="<?php echo ($_SESSION['usuario']['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario']['usuario_apellido'] ?? ''); ?>">
                    <input type="hidden" id="max_atletas_entrenamiento" value="0">

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

                        <!-- ===== ENTRENADOR ===== -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Entrenador</label>
                            <select class="form-select custom-input" name="entrenador_cedula" required>
                                <?php
                                $cedula_sesion = $_SESSION['usuario']['usuario_cedula'] ?? '';
                                // Opción por defecto: el de sesión (si su rol es entrenador o admin/secretaria lo asigna así)
                                if (trim($cedula_sesion) !== ''):
                                ?>
                                <option value="<?php echo htmlspecialchars($cedula_sesion); ?>">
                                    Yo (<?php echo trim(($_SESSION['usuario']['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario']['usuario_apellido'] ?? '')); ?>)
                                </option>
                                <?php endif; ?>
                                <option value="" disabled>-- Seleccionar otro entrenador --</option>
                                <?php foreach($lista_entrenadores as $ent): ?>
                                <?php if($ent['cedula'] === $cedula_sesion) continue; ?>
                                <option value="<?php echo htmlspecialchars($ent['cedula']); ?>">
                                    <?php echo htmlspecialchars($ent['nombre']); ?> (<?php echo htmlspecialchars($ent['cedula']); ?>)
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-secondary">Por defecto se asigna el entrenador en sesión.</small>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Tipo</label>
                            <select class="form-select custom-input" name="tipo" required>
                                <option value="">Seleccionar...</option>
                                <option value="Físico">Físico</option>
                                <option value="Técnico">Técnico</option>
                                <option value="Táctico">Táctico</option>
                                <option value="Mixto">Mixto</option>
                            </select>
                        </div>

                        <!-- ===== MÚLTIPLES CATEGORÍAS ===== -->
                        <div class="col-md-6">
                            <label class="custom-form-label">Categorías (puedes elegir varias)</label>
                            <div class="border rounded p-2" style="max-height:140px; overflow-y:auto;">
                                <?php foreach($todas_las_categorias as $cat): ?>
                                <div class="form-check">
                                    <input class="form-check-input categoria-entrenamiento" type="checkbox" name="categoria[]" value="<?php echo htmlspecialchars($cat); ?>" id="cat_nueva_<?php echo htmlspecialchars($cat); ?>">
                                    <label class="form-check-label text-dark" for="cat_nueva_<?php echo htmlspecialchars($cat); ?>">
                                        <?php echo htmlspecialchars($cat); ?>
                                    </label>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <small class="text-secondary">Máximo de atletas disponible: <span data-max-atletas-label>0</span></small>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Total Atletas</label>
                            <input type="number" class="form-control custom-input" name="total_atletas" placeholder="Número de atletas" min="0" required>
                            <small class="text-danger d-none" data-error-total-atletas>Supera el máximo de las categorías</small>
                        </div>

                        <div class="col-md-6">
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

                        <div class="col-12">
                            <label class="custom-form-label">Descripción</label>
                            <textarea class="form-control custom-input" name="descripcion" rows="3" placeholder="Descripción del entrenamiento..."></textarea>
                        </div>

                        <!-- ===== RECURSOS MULTIMEDIA ===== -->
                        <div class="col-12">
                            <label class="custom-form-label">
                                <i class="bi bi-paperclip me-1"></i>Recursos multimedia (imágenes, videos, enlaces)
                            </label>
                            <div id="contenedor_recursos_nuevo" data-recurso-contenedor></div>
                            <div class="mt-2 d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-sm btn-outline-info" onclick="agregarRecursoNuevo(this, 'enlace')">
                                    <i class="bi bi-link-45deg me-1"></i>Agregar enlace
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="agregarRecursoNuevo(this, 'archivo')">
                                    <i class="bi bi-upload me-1"></i>Subir imagen/video
                                </button>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="alert alert-info" style="background: rgba(13,202,240,0.08); border: 1px solid rgba(13,202,240,0.12); border-radius: 10px; padding: 10px 15px;">
                                <i class="bi bi-info-circle-fill me-2 text-info"></i>
                                <small class="text-white-50">
                                    Entrenador asignado: <strong class="text-white"><?php echo ($_SESSION['usuario']['usuario_nombre'] ?? '') . ' ' . ($_SESSION['usuario']['usuario_apellido'] ?? ''); ?></strong>
                                    <span class="text-white-50">(Cédula: <?php echo $_SESSION['usuario']['usuario_cedula'] ?? ''; ?>)</span>
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
