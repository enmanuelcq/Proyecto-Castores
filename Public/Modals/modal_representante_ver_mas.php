<?php
// ============================================
// /Public/Modals/modal_representante_ver_mas.php
// MODAL PARA VER INFORMACIÓN COMPLETA DEL REPRESENTADO
// ============================================
?>

<div class="modal fade" id="modalRepresentanteVerMas<?php echo $atleta['atleta_cedula']; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <!-- ===== HEADER ===== -->
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-person-badge me-2"></i>
                    Expediente Completo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- ===== BODY ===== -->
            <div class="modal-body">
                
                <!-- ===== CABECERA DEL ATLETA ===== -->
                <div class="atleta-header-modal mb-4">
                    <div class="atleta-avatar-modal">
                        <?php 
                        echo strtoupper(substr($atleta['atleta_primer_nombre'] ?? 'A', 0, 1) . substr($atleta['atleta_primer_apellido'] ?? 'A', 0, 1));
                        ?>
                    </div>
                    <div class="atleta-info-modal">
                        <h4><?php echo $atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']; ?></h4>
                        <div class="atleta-badges">
                            <span class="badge bg-primary"><?php echo $atleta['atleta_categoria']; ?></span>
                            <span class="badge bg-warning text-dark">Dorsal: <?php echo $atleta['atleta_numero_franela'] ?: 'N/A'; ?></span>
                            <span class="badge bg-info"><?php echo $atleta['atleta_sexo']; ?></span>
                        </div>
                    </div>
                    <div class="atleta-actions-modal">
                        <span class="text-white small">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?php echo date('d/m/Y', strtotime($atleta['atleta_fecha_nacimiento'])); ?>
                        </span>
                    </div>
                </div>

                <!-- ===== TABLA DE INFORMACIÓN PRINCIPAL ===== -->
                <div class="table-responsive">
                    <table class="table table-dashboard table-sm">
                        <thead>
                            <tr>
                                <th style="width: 25%; color: #fff;">Datos del Representante</th>
                                <th style="width: 25%; color: #fff;">Datos del Atleta</th>
                                <th style="width: 20%; color: #fff;">Categoría</th>
                                <th style="width: 30%; color: #fff;">Indumentaria</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="info-item">
                                        <span class="label-title">Cédula</span>
                                        <span class="value"><?php echo $representante['usuario_cedula']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Nombre</span>
                                        <span class="value"><?php echo $representante['usuario_nombre'] . ' ' . $representante['usuario_apellido']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Teléfono</span>
                                        <span class="value"><?php echo $representante['usuario_telefono']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Email</span>
                                        <span class="value"><?php echo $representante['usuario_email']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Ocupación</span>
                                        <span class="value"><?php echo $representante['representante_ocupacion']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Dirección</span>
                                        <span class="value"><?php echo $representante['representante_direccion']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="info-item">
                                        <span class="label-title">Cédula</span>
                                        <span class="value"><?php echo $atleta['atleta_cedula']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Fecha Nac.</span>
                                        <span class="value"><?php echo date('d/m/Y', strtotime($atleta['atleta_fecha_nacimiento'])); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Nombres</span>
                                        <span class="value"><?php echo $atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_segundo_nombre']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Apellidos</span>
                                        <span class="value"><?php echo $atleta['atleta_primer_apellido'] . ' ' . $atleta['atleta_segundo_apellido']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Sexo</span>
                                        <span class="value"><?php echo $atleta['atleta_sexo']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Pie Dominante</span>
                                        <span class="value"><?php echo $atleta['atleta_pie_dominante']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Dirección</span>
                                        <span class="value"><?php echo $atleta['atleta_direccion']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="info-item">
                                        <span class="label-title">Categoría</span>
                                        <span class="value"><?php echo $atleta['atleta_categoria']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Año</span>
                                        <span class="value"><?php echo $atleta['atleta_categoria_anio']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="info-item">
                                        <span class="label-title">Dorsal</span>
                                        <span class="value"><?php echo $atleta['atleta_numero_franela'] ?: 'No asignado'; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Talla Franela</span>
                                        <span class="value"><?php echo $atleta['atleta_talla_franela']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Talla Short</span>
                                        <span class="value"><?php echo $atleta['atleta_talla_short']; ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Talla Zapatos</span>
                                        <span class="value"><?php echo $atleta['atleta_talla_zapatos']; ?></span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- ===== INFORMACIÓN DE SALUD ===== -->
                <div class="row g-3 mt-2">
                    <div class="col-lg-6">
                        <div class="card form-section-card">
                            <h4 class="section-card-title"><i class="bi bi-heart-pulse me-2 text-danger"></i>Salud</h4>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <span class="label-title">Tipo Sangre</span>
                                        <span class="value"><?php echo $atleta['atleta_tipo_sangre']; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="info-item">
                                        <span class="label-title">Alergias</span>
                                        <span class="value"><?php echo $atleta['atleta_alergias'] ?: 'Ninguna'; ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Padecimiento Crónico</span>
                                        <span class="value"><?php echo $atleta['atleta_padecimiento_cronico'] ?: 'Ninguno'; ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Medicamento que no debe tomar</span>
                                        <span class="value"><?php echo $atleta['atleta_medicamento_restriccion'] ?: 'Ninguno'; ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Lesiones Previas</span>
                                        <span class="value"><?php echo $atleta['atleta_lesiones_previas'] ?: 'Ninguna'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <?php if($atleta['atleta_poliza_seguro'] === 'posee'): ?>
                        <div class="card form-section-card">
                            <h4 class="section-card-title"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Póliza de Seguro</h4>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Empresa</span>
                                        <span class="value"><?php echo $atleta['atleta_empresa_aseguradora']; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Vigencia</span>
                                        <span class="value"><?php echo date('d/m/Y', strtotime($atleta['atleta_vigencia_seguro'])); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Cobertura</span>
                                        <span class="value"><?php echo $atleta['atleta_cobertura_contratada']; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">N° Póliza</span>
                                        <span class="value"><?php echo $atleta['atleta_numero_poliza']; ?></span>
                                    </div>
                                </div>
                                <?php if(!empty($atleta['atleta_medico_tratante']) || !empty($atleta['atleta_telefono_medico'])): ?>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Médico</span>
                                        <span class="value"><?php echo $atleta['atleta_medico_tratante'] ?: 'No registra'; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Teléfono</span>
                                        <span class="value"><?php echo $atleta['atleta_telefono_medico'] ?: 'No registra'; ?></span>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="card form-section-card">
                            <div class="alert alert-info py-2 small text-start" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <strong>Sin Seguro:</strong> El atleta no posee póliza de seguros
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- ===== INFORMACIÓN ACADÉMICA Y EXTRA ===== -->
                <div class="row g-3 mt-1">
                    <div class="col-lg-6">
                        <div class="card form-section-card">
                            <h4 class="section-card-title"><i class="bi bi-book me-2 text-warning"></i>Información Académica</h4>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Colegio</span>
                                        <span class="value"><?php echo $atleta['atleta_nombre_colegio'] ?: 'No registra'; ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Dirección</span>
                                        <span class="value"><?php echo $atleta['atleta_direccion_colegio'] ?: 'No registra'; ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Grado</span>
                                        <span class="value"><?php echo $atleta['atleta_grado_cursante'] ?: 'No registra'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card form-section-card">
                            <h4 class="section-card-title"><i class="bi bi-palette me-2 text-success"></i>Actividades Extra</h4>
                            <div class="row g-2">
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Hobbie</span>
                                        <span class="value"><?php echo $atleta['atleta_nombre_hobbie'] ?: 'No registra'; ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Horario</span>
                                        <span class="value"><?php echo $atleta['atleta_horario_hobbie'] ?: 'No registra'; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
            <!-- ===== FOOTER ===== -->
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>