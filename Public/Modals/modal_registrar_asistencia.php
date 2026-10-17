<?php
require_once dirname(__DIR__, 2) . '/Model/Repository/RepositoryAtleta.php';
require_once dirname(__DIR__, 2) . '/Model/Repository/RepositoryDesempenioEntrenamiento.php';

$repo_atleta_modal = new RepositoryAtleta();
$repo_desempenio_modal = new RepositoryDesempenioEntrenamiento();
?>

<?php foreach($entrenamientos_filtrados as $e):
    // Categorías múltiples (tabla puente) o la categoría principal como respaldo
    $categorias_convocadas = $e['categorias'] ?? [];
    if (empty($categorias_convocadas)) {
        $categorias_convocadas = [$e['categoria']];
    }

    $categoria_id_map = [
        'Sub-4' => 1, 'Sub-5' => 2, 'Sub-6' => 3, 'Sub-7' => 4,
        'Sub-8' => 5, 'Sub-9' => 6, 'Sub-10' => 7, 'Sub-11' => 8,
        'Sub-12' => 9, 'Sub-13' => 10, 'Sub-14' => 11, 'Sub-15' => 12,
        'Sub-16' => 13, 'Sub-17' => 14, 'Sub-18' => 15,
    ];
    $categoria_ids_convocadas = [];
    foreach ($categorias_convocadas as $cn) {
        $cid = $categoria_id_map[$cn] ?? 0;
        if ($cid > 0) $categoria_ids_convocadas[] = $cid;
    }
    $categoria_principal_id = $categoria_ids_convocadas[0] ?? 0;

    $atletas_db = !empty($categoria_ids_convocadas)
        ? $repo_atleta_modal->findByCategorias($categoria_ids_convocadas, $categoria_principal_id)
        : [];

    $atletas_categoria = [];
    foreach ($atletas_db as $a) {
        $atletas_categoria[] = [
            'atleta_id' => $a->getAtletaId(),
            'atleta_cedula' => $a->getCedula(),
            'atleta_primer_nombre' => $a->getPrimerNombre(),
            'atleta_primer_apellido' => $a->getPrimerApellido(),
        ];
    }

    // Obtener asistencia existente desde la BD
    $desempenios_existentes = $repo_desempenio_modal->findByEntrenamientoId($e['id']);
    $asistencia_map = [];
    foreach ($desempenios_existentes as $d) {
        $asistencia_map[$d->getAtletaId()] = $d->toArray();
    }
?>
<div class="modal fade" id="modalRegistrarAsistencia<?php echo $e['id']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-person-check-fill me-2 text-success"></i>
                    Asistencia - <?php echo $e['tipo']; ?> (<?php echo implode(', ', $categorias_convocadas); ?>)
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                
                <div class="atleta-header-modal mb-4">
                    <div class="atleta-info-modal">
                        <h4><?php echo $e['tipo']; ?> - <?php echo implode(', ', $categorias_convocadas); ?></h4>
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

                <form id="formAsistencia<?php echo $e['id']; ?>" method="POST" action="./Controller/RegistrarAsistencia_controller.php">
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
                                    $asistencia_existente = $asistencia_map[$a['atleta_id']] ?? null;
                                ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td>
                                        <strong><?php echo $a['atleta_primer_nombre'] . ' ' . $a['atleta_primer_apellido']; ?></strong>
                                    </td>
                                    <td><?php echo $a['atleta_cedula']; ?></td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="asistencia[<?php echo $a['atleta_id']; ?>]" value="0">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                   name="asistencia[<?php echo $a['atleta_id']; ?>]" 
                                                   id="asistencia_<?php echo $e['id']; ?>_<?php echo $a['atleta_id']; ?>"
                                                   <?php echo ($asistencia_existente && $asistencia_existente['asistio'] == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="asistencia_<?php echo $e['id']; ?>_<?php echo $a['atleta_id']; ?>">
                                                Presente
                                            </label>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control custom-input-2 form-control-sm" 
                                               name="observacion[<?php echo $a['atleta_id']; ?>]" 
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
