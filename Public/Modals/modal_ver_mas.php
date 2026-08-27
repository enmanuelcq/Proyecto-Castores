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
                        <?php $foto = $atleta->getFoto(); ?>
                        <?php if (!empty($foto)): ?>
                            <img src="./Public/uploads/<?php echo htmlspecialchars($foto); ?>" alt="Foto" class="avatar-img-modal">
                        <?php else: ?>
                            <?php echo strtoupper(substr($atleta->getPrimerNombre() ?? 'A', 0, 1) . substr($atleta->getPrimerApellido() ?? 'A', 0, 1)); ?>
                        <?php endif; ?>
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

                <!-- ===== FOTO GRANDE DEL ATLETA ===== -->
                <?php if (!empty($foto)): ?>
                <div class="text-center mb-4">
                    <img src="./Public/uploads/<?php echo htmlspecialchars($foto); ?>" alt="Foto de <?php echo htmlspecialchars($nombre_completo); ?>" class="foto-grande-atleta">
                    <div class="mt-2">
                        <a href="./Public/uploads/<?php echo htmlspecialchars($foto); ?>" download="<?php echo htmlspecialchars($atleta->getPrimerNombre()) . '_' . htmlspecialchars($atleta->getPrimerApellido()) . '.jpg'; ?>" class="btn btn-sm btn-action-green">
                            <i class="bi bi-download me-1"></i> Descargar foto
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <!-- ===== INFORMACIÓN PRINCIPAL EN BLOQUES ===== -->
                <div class="row g-3 mb-3">
                    <!-- Representantes -->
                    <div class="col-lg-6">
                        <div class="card form-section-card">
                            <h4 class="section-card-title"><i class="bi bi-people-fill me-2 text-primary"></i>Representante(s)</h4>
                            <?php if (!empty($representantes)): ?>
                                <?php foreach ($representantes as $idx => $repData): 
                                    $rep = $repData['representante'];
                                    $parentesco = $repData['parentesco'] ?? '';
                                ?>
                                <?php if ($idx > 0): ?>
                                    <hr class="my-2">
                                <?php endif; ?>
                                <div class="mb-1">
                                    <?php if (count($representantes) > 1): ?>
                                        <small class="text-muted fw-bold text-uppercase"><?php echo htmlspecialchars($parentesco); ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="label-title">Cédula</span>
                                            <span class="value"><?php echo htmlspecialchars($rep->getCedula()); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="label-title">Nombre</span>
                                            <span class="value"><?php echo htmlspecialchars($rep->getNombre() . ' ' . $rep->getApellido()); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="label-title">Teléfono</span>
                                            <span class="value"><?php echo htmlspecialchars($rep->getTelefono()); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="label-title">Email</span>
                                            <span class="value"><?php echo htmlspecialchars($rep->getCorreo()); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="info-item">
                                            <span class="label-title">Ocupación</span>
                                            <span class="value"><?php echo htmlspecialchars($rep->getOcupacion() ?: 'No especificada'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                            <p class="text-muted mb-0">No hay representante registrado.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Datos del Atleta -->
                    <div class="col-lg-6">
                        <div class="card form-section-card">
                            <h4 class="section-card-title"><i class="bi bi-person-fill me-2 text-success"></i>Datos del Atleta</h4>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Cédula</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getCedula() ?: 'No registrada'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Fecha Nac.</span>
                                        <span class="value"><?php echo date('d/m/Y', strtotime($atleta->getFechaNacimiento())); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Nombres</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getPrimerNombre() . ' ' . $atleta->getSegundoNombre()); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Apellidos</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getPrimerApellido() . ' ' . $atleta->getSegundoApellido()); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Sexo</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getSexo()); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Pie Dominante</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getPieDominante()); ?></span>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="info-item">
                                        <span class="label-title">Dirección</span>
                                        <span class="value"><?php echo htmlspecialchars($atleta->getDireccion()); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <!-- Categoría -->
                    <div class="col-lg-6">
                        <div class="card form-section-card">
                            <h4 class="section-card-title"><i class="bi bi-shield-fill me-2 text-warning"></i>Categoría</h4>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Categoría</span>
                                        <span class="value"><?php echo htmlspecialchars($categoria); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Año</span>
                                        <span class="value"><?php echo htmlspecialchars($categoriaAnio); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Indumentaria -->
                    <div class="col-lg-6">
                        <div class="card form-section-card">
                            <h4 class="section-card-title"><i class="bi bi-shirt me-2 text-info"></i>Indumentaria</h4>
                            <?php if ($uniforme): ?>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Dorsal</span>
                                        <span class="value"><?php echo htmlspecialchars($numeroFranela); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Talla Franela</span>
                                        <span class="value"><?php echo htmlspecialchars($uniforme->getFranelaTalla()); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Talla Short</span>
                                        <span class="value"><?php echo htmlspecialchars($uniforme->getShortTalla()); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <span class="label-title">Talla Zapatos</span>
                                        <span class="value"><?php echo htmlspecialchars($uniforme->getZapatosTalla()); ?></span>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <p class="text-muted mb-0">No hay uniforme registrado.</p>
                            <?php endif; ?>
                        </div>
                    </div>
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

                <!-- ===== DOCUMENTOS DEL ATLETA ===== -->
                <?php
                $docCedula = $atleta->getDocumentoCedula();
                $docPartida = $atleta->getDocumentoPartida();
                if (!empty($docCedula) || !empty($docPartida)):
                ?>
                <div class="row g-3 mt-1">
                    <div class="col-12">
                        <div class="card form-section-card">
                            <h4 class="section-card-title"><i class="bi bi-file-earmark-medical me-2 text-danger"></i>Documentos</h4>
                            <div class="row g-3">
                                <?php if (!empty($docCedula)): ?>
                                <div class="col-md-6 text-center">
                                    <div class="p-3 rounded-3 border bg-light">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 2.5rem;"></i>
                                        <p class="fw-bold mb-2 mt-2">Cédula de Identidad</p>
                                        <a href="./Public/uploads/<?php echo htmlspecialchars($docCedula); ?>" target="_blank" class="btn btn-sm btn-action-green me-1">
                                            <i class="bi bi-eye me-1"></i> Ver PDF
                                        </a>
                                        <a href="./Public/uploads/<?php echo htmlspecialchars($docCedula); ?>" download class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-download me-1"></i> Descargar
                                        </a>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="col-md-6 text-center">
                                    <div class="p-3 rounded-3 border bg-light">
                                        <i class="bi bi-file-earmark-x text-muted" style="font-size: 2.5rem;"></i>
                                        <p class="text-muted mb-0 mt-2">Cédula no cargada</p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($docPartida)): ?>
                                <div class="col-md-6 text-center">
                                    <div class="p-3 rounded-3 border bg-light">
                                        <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size: 2.5rem;"></i>
                                        <p class="fw-bold mb-2 mt-2">Partida de Nacimiento</p>
                                        <a href="./Public/uploads/<?php echo htmlspecialchars($docPartida); ?>" target="_blank" class="btn btn-sm btn-action-green me-1">
                                            <i class="bi bi-eye me-1"></i> Ver PDF
                                        </a>
                                        <a href="./Public/uploads/<?php echo htmlspecialchars($docPartida); ?>" download class="btn btn-sm btn-outline-success">
                                            <i class="bi bi-download me-1"></i> Descargar
                                        </a>
                                    </div>
                                </div>
                                <?php else: ?>
                                <div class="col-md-6 text-center">
                                    <div class="p-3 rounded-3 border bg-light">
                                        <i class="bi bi-file-earmark-x text-muted" style="font-size: 2.5rem;"></i>
                                        <p class="text-muted mb-0 mt-2">Partida no cargada</p>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

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