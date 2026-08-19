<?php foreach($entrenamientos_filtrados as $e): 
    // Obtener atletas de la categoría (simulado desde el array de atletas)
    $atletas_categoria = array_filter($todos_los_atletas, function($a) use ($e) {
        return $a['atleta_categoria'] == $e['categoria'];
    });
    $atletas_categoria = array_values($atletas_categoria);
?>
<div class="modal fade" id="modalRegistrarAsistencia<?php echo $e['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-person-check-fill me-2 text-success"></i>
                    Asistencia - <?php echo $e['tipo']; ?> (<?php echo $e['categoria']; ?>)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                
                <!-- ===== CABECERA DEL ENTRENAMIENTO ===== -->
                <div class="atleta-header-modal mb-4">
                    <div class="atleta-info-modal">
                        <h4><?php echo $e['tipo']; ?> - <?php echo $e['categoria']; ?></h4>
                        <div class="atleta-badges">
                            <span class="badge bg-secondary"><?php echo date('d/m/Y', strtotime($e['fecha'])); ?></span>
                            <span class="badge bg-secondary"><?php echo $e['hora_inicio']; ?> - <?php echo $e['hora_fin']; ?></span>
                            <span class="badge bg-secondary"><?php echo $e['lugar']; ?></span>
                            <span class="badge bg-info">Entrenador: <?php echo $e['entrenador_nombre']; ?></span>
                        </div>
                    </div>
                    <div class="atleta-actions-modal">
                        <span class="text-white-50 small">
                            <i class="bi bi-people me-1"></i>
                            <?php echo count($atletas_categoria); ?> atletas
                        </span>
                    </div>
                </div>

                <form id="formAsistencia<?php echo $e['id']; ?>" method="POST" action="./Controller/RegistrarAsistencia_controller.php" class="FormularioAjax">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="entrenamiento_id" value="<?php echo $e['id']; ?>">
                    <input type="hidden" name="entrenador_cedula" value="<?php echo $_SESSION['usuario']['usuario_cedula'] ?? ''; ?>">
                    
                    <div class="table-responsive">
                        <table class="table table-dashboard">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">#</th>
                                    <th>Atleta</th>
                                    <th>Cédula</th>
                                    <th style="width: 120px;">Asistió</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach($atletas_categoria as $a): 
                                    $asistencia_existente = null;
                                    foreach($toda_la_asistencia as $as) {
                                        if($as['entrenamiento_id'] == $e['id'] && $as['atleta_cedula'] == $a['atleta_cedula']) {
                                            $asistencia_existente = $as;
                                            break;
                                        }
                                    }
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <strong><?php echo $a['atleta_primer_nombre'] . ' ' . $a['atleta_primer_apellido']; ?></strong>
                                    </td>
                                    <td><?php echo $a['atleta_cedula']; ?></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="asistencia[<?php echo $a['atleta_cedula']; ?>]" 
                                                   id="asistencia_<?php echo $e['id']; ?>_<?php echo $a['atleta_cedula']; ?>"
                                                   <?php echo ($asistencia_existente && $asistencia_existente['asistio'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="asistencia_<?php echo $e['id']; ?>_<?php echo $a['atleta_cedula']; ?>">
                                                Presente
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control custom-input-2 form-control-sm" 
                                               name="observacion[<?php echo $a['atleta_cedula']; ?>]" 
                                               placeholder="Observación..."
                                               value="<?php echo ($asistencia_existente) ? $asistencia_existente['observacion'] : ''; ?>">
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success px-4 rounded-pill fw-bold">
                            <i class="bi bi-save me-2"></i>Guardar Asistencia
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