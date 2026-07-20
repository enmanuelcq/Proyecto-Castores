<div class="modal fade" id="modalVerMas<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content custom-modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-person-badge me-2"></i>Expediente del Atleta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                
                <!-- ===== CABECERA DEL ATLETA ===== -->
                <div class="atleta-header-modal mb-4">
                    <div class="atleta-avatar-modal">
                        <?php 
                        echo strtoupper(substr($atleta->getPrimerNombre() ?? 'A', 0, 1) . substr($atleta->getPrimerApellido() ?? 'A', 0, 1));
                        ?>
                    </div>
                    <div class="atleta-info-modal">
                        <h4><?php echo htmlspecialchars($nombre_completo); ?></h4>
                        <div class="atleta-badges">
                            <span class="badge bg-primary"><?php echo htmlspecialchars($categoria); ?></span>
                            <span class="badge bg-warning text-dark">Dorsal: <?php echo htmlspecialchars($numeroFranela); ?></span>
                            <span class="badge bg-info"><?php echo htmlspecialchars($atleta->getSexo()); ?></span>
                        </div>
                    </div>
                    <div class="atleta-actions-modal">
                        <span class="text-white small">
                            <i class="bi bi-calendar3 me-1"></i>
                            <?php echo date('d/m/Y', strtotime($atleta->getFechaNacimiento())); ?>
                        </span>
                    </div>
                </div>

                <!-- ===== TABLA DE INFORMACIÓN PRINCIPAL ===== -->
                <div class="table-responsive">
                    <table class="table table-dashboard table-sm">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Datos del Representante</th>
                                <th style="width: 25%;">Datos del Atleta</th>
                                <th style="width: 20%;">Categoría</th>
                                <th style="width: 30%;">Indumentaria</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <?php if ($representante): ?>
                                    <div class="info-item">
                                        <span class="label-title">Cédula</span>
                                        <span class="value"><?php echo htmlspecialchars($representante->getCedula()); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Nombre</span>
                                        <span class="value"><?php echo htmlspecialchars($representante->getNombre() . ' ' . $representante->getApellido()); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Teléfono</span>
                                        <span class="value"><?php echo htmlspecialchars($representante->getTelefono()); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Email</span>
                                        <span class="value"><?php echo htmlspecialchars($representante->getCorreo()); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Ocupación</span>
                                        <span class="value"><?php echo htmlspecialchars($representante->getOcupacion() ?: 'No especificada'); ?></span>
                                    </div>
                                    <?php else: ?>
                                    <p>No hay representante registrado.</p>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="info-item">
                                        <span class="label-title">Cédula</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getCedula() ?: 'No registrada'); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Fecha Nac.</span>
                                        <span class="value"><?php echo date('d/m/Y', strtotime($atleta->getFechaNacimiento())); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Nombres</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getPrimerNombre() . ' ' . $atleta->getSegundoNombre()); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Apellidos</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getPrimerApellido() . ' ' . $atleta->getSegundoApellido()); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Sexo</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getSexo()); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Pie Dominante</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getPieDominante()); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Dirección</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getDireccion()); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <div class="info-item">
                                        <span class="label-title">Categoría</span>
                                        <span class="value"><?php echo htmlspecialchars($categoria); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Año</span>
                                        <span class="value"><?php echo htmlspecialchars($categoriaAnio); ?></span>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($uniforme): ?>
                                    <div class="info-item">
                                        <span class="label-title">Dorsal</span>
                                        <span class="value"><?php echo htmlspecialchars($numeroFranela); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Talla Franela</span>
                                        <span class="value"><?php echo htmlspecialchars($uniforme->getFranelaTalla()); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Talla Short</span>
                                        <span class="value"><?php echo htmlspecialchars($uniforme->getShortTalla()); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="label-title">Talla Zapatos</span>
                                        <span class="value"><?php echo htmlspecialchars($uniforme->getZapatosTalla()); ?></span>
                                    </div>
                                    <?php else: ?>
                                    <p>No hay uniforme registrado.</p>
                                    <?php endif; ?>
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
                            <?php if ($salud): ?>
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="info-item">
                                        <span class="label-title">Tipo Sangre</span>
                                        <span class="value"><?php echo htmlspecialchars($salud->getTipoSangre()); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="info-item">
                                        <span class="label-title">Alergias</span>
                                        <span class="value"><?php echo htmlspecialchars($salud->getAlergias() ?: 'Ninguna'); ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Padecimiento Crónico</span>
                                        <span class="value"><?php echo htmlspecialchars($salud->getPadecimiento() ?: 'Ninguno'); ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Medicamento que no debe tomar</span>
                                        <span class="value"><?php echo htmlspecialchars($salud->getMedicamentoNoTomar() ?: 'Ninguno'); ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Lesiones Previas</span>
                                        <span class="value"><?php echo htmlspecialchars($salud->getLesionesPrevias() ?: 'Ninguna'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <p>No hay ficha de salud registrada.</p>
                            <?php endif; ?>
                        </div>
                    </div>
<script>console.log("Usuario: ' <?php echo $salud->getPolizaSeguro(); ?> . '");</script>
                    <div class="col-lg-6">
                        <?php if ($salud && $salud->getPolizaSeguro() === 'posee'): ?>
                        <div class="card form-section-card">
                            <h4 class="section-card-title"><i class="bi bi-file-earmark-text me-2 text-primary"></i>Póliza de Seguro</h4>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Empresa</span>
                                        <span class="value"><?php echo htmlspecialchars($salud->getEmpresa() ?: 'No especificada'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Vigencia</span>
                                        <span class="value"><?php echo $salud->getVigencia() ? date('d/m/Y', strtotime($salud->getVigencia())) : 'No registra'; ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Cobertura</span>
                                        <span class="value"><?php echo htmlspecialchars($salud->getCobertura() ?: 'No especificada'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">N° Póliza</span>
                                        <span class="value"><?php echo htmlspecialchars($salud->getNroPoliza() ?: 'No registra'); ?></span>
                                    </div>
                                </div>
                                <?php if (!empty($salud->getNombreMedico()) || !empty($salud->getTelefonoMedico())): ?>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Médico</span>
                                        <span class="value"><?php echo htmlspecialchars($salud->getNombreMedico() ?: 'No registra'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Teléfono</span>
                                        <span class="value"><?php echo htmlspecialchars($salud->getTelefonoMedico() ?: 'No registra'); ?></span>
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
                                        <span class="value"><?php echo $colegio ? htmlspecialchars($colegio->getNombre()) : 'No registra'; ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Dirección</span>
                                        <span class="value"><?php echo $colegio ? htmlspecialchars($colegio->getDireccion()) : 'No registra'; ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Grado</span>
                                        <span class="value"><?php echo $grado ? htmlspecialchars($grado->getGrado()) : 'No registra'; ?></span>
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
                                        <span class="value"><?php echo $hobbie ? htmlspecialchars($hobbie->getNombre()) : 'No registra'; ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Horario</span>
                                        <span class="value"><?php echo htmlspecialchars($horarioHobbie ?: 'No registra'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
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