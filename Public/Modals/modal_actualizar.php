<div class="modal fade" id="modalActualizar<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content custom-modal-content">
            <!-- ===== HEADER ===== -->
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-uppercase">
                    <i class="bi bi-pencil-square me-2"></i>Actualizar Información del Atleta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <!-- ===== BODY ===== -->
            <div class="modal-body">

                <!-- ===== CABECERA DEL ATLETA ===== -->
                <div class="atleta-header-modal mb-3">
                    <div class="atleta-avatar-modal">
                        <?php $fotoHeader = $atleta->getFoto(); ?>
                        <?php if (!empty($fotoHeader)): ?>
                            <img src="./Public/uploads/<?php echo htmlspecialchars($fotoHeader); ?>" alt="Foto" class="avatar-img-modal">
                        <?php else: ?>
                            <?php echo strtoupper(substr($atleta->getPrimerNombre() ?? 'A', 0, 1) . substr($atleta->getPrimerApellido() ?? 'A', 0, 1)); ?>
                        <?php endif; ?>
                    </div>
                    <div class="atleta-info-modal">
                        <h4><?php echo htmlspecialchars($atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido()); ?></h4>
                        <div class="atleta-badges">
                            <span class="badge bg-primary"><?php echo htmlspecialchars($categoria); ?></span>
                            <span class="badge bg-warning text-dark">Dorsal: <?php echo htmlspecialchars($numeroFranela); ?></span>
                        </div>
                    </div>
                </div>

                <!-- ===== WIZARD STEPPER ===== -->
                <div class="wizard-stepper mb-3" id="stepper-<?php echo $atleta_id; ?>">
                    <div class="wizard-step active" data-step="1">
                        <div class="wizard-step-circle">1</div>
                        <span class="wizard-step-label">Datos</span>
                    </div>
                    <div class="wizard-step-line"></div>
                    <div class="wizard-step" data-step="2">
                        <div class="wizard-step-circle">2</div>
                        <span class="wizard-step-label">Categoría</span>
                    </div>
                    <div class="wizard-step-line"></div>
                    <div class="wizard-step" data-step="3">
                        <div class="wizard-step-circle">3</div>
                        <span class="wizard-step-label">Salud</span>
                    </div>
                    <div class="wizard-step-line"></div>
                    <div class="wizard-step" data-step="4">
                        <div class="wizard-step-circle">4</div>
                        <span class="wizard-step-label">Académico</span>
                    </div>
                </div>

                <!-- ===== FORMULARIO ===== -->
                <form action="./Controller/ActualizarAtleta_controller.php" method="POST" enctype="multipart/form-data" class="FormularioAjax" id="actualizarAtletaForm<?php echo $atleta_id; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="atleta_id" value="<?php echo $atleta_id; ?>">
                    <input type="hidden" name="atleta_cedula_original" value="<?php echo htmlspecialchars($atleta->getCedula() ?: ''); ?>">

                    <!-- ============================================ -->
                    <!-- PASO 1: DATOS PERSONALES -->
                    <!-- ============================================ -->
                    <div class="wizard-panel active" data-panel="1">
                        <div class="card form-section-card mb-0">
                            <h2 class="section-card-title"><i class="bi bi-person-fill me-2"></i>Datos del atleta</h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="custom-form-label">1er Nombre:</label>
                                    <input type="text" class="form-control custom-input" name="primer_nombre"
                                        value="<?php echo htmlspecialchars($atleta->getPrimerNombre()); ?>"
                                        pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required placeholder="Ej. Juan">
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">2do Nombre:</label>
                                    <input type="text" class="form-control custom-input" name="segundo_nombre"
                                        value="<?php echo htmlspecialchars($atleta->getSegundoNombre() ?: ''); ?>"
                                        pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" placeholder="Ej. Carlos">
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">1er Apellido:</label>
                                    <input type="text" class="form-control custom-input" name="primer_apellido"
                                        value="<?php echo htmlspecialchars($atleta->getPrimerApellido()); ?>"
                                        pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required placeholder="Ej. Pérez">
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">2do Apellido:</label>
                                    <input type="text" class="form-control custom-input" name="segundo_apellido"
                                        value="<?php echo htmlspecialchars($atleta->getSegundoApellido() ?: ''); ?>"
                                        pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" placeholder="Ej. García">
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">Fecha de nacimiento:</label>
                                    <input type="date" class="form-control custom-input" name="atleta_fecha" required
                                        value="<?php echo htmlspecialchars($atleta->getFechaNacimiento()); ?>"
                                        max="2023-12-31" min="2000-01-01">
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">Sexo:</label>
                                    <select class="form-select custom-input" name="atleta_sexo" required>
                                        <option value="" disabled>Seleccione...</option>
                                        <option value="Masculino" <?php echo ($atleta->getSexo() == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                        <option value="Femenino" <?php echo ($atleta->getSexo() == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">Cédula (si aplica):</label>
                                    <input type="text" class="form-control custom-input" name="atleta_cedula"
                                        pattern="[0-9]{5,8}" maxlength="8"
                                        value="<?php echo htmlspecialchars($atleta->getCedula() ?: ''); ?>"
                                        placeholder="Opcional">
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">Pie Dominante:</label>
                                    <select class="form-select custom-input" name="pie_dominante" required>
                                        <option value="" disabled>Seleccione...</option>
                                        <option value="Derecho" <?php echo ($atleta->getPieDominante() == 'Derecho') ? 'selected' : ''; ?>>Derecho (Diestro)</option>
                                        <option value="Izquierdo" <?php echo ($atleta->getPieDominante() == 'Izquierdo') ? 'selected' : ''; ?>>Izquierdo (Zurdo)</option>
                                        <option value="Ambidiestro" <?php echo ($atleta->getPieDominante() == 'Ambidiestro') ? 'selected' : ''; ?>>Ambidiestro</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="custom-form-label">Dirección de habitación:</label>
                                    <input type="text" class="form-control custom-input" name="atleta_direccion"
                                        pattern="[A-Za-z0-9áéíóúÁÉÍÓÚñÑ#., ]{3,100}" maxlength="200" required
                                        value="<?php echo htmlspecialchars($atleta->getDireccion()); ?>"
                                        placeholder="Calle/Avenida, Número, Urbanización">
                                </div>
                            </div>
                        </div>

                        <!-- FOTO Y DOCUMENTOS -->
                        <div class="card form-section-card mb-0">
                            <h2 class="section-card-title"><i class="bi bi-camera-fill me-2"></i>Foto y Documentos</h2>
                            <div class="row g-3">
                                <div class="col-md-4 text-center">
                                    <label class="custom-form-label">Foto actual:</label>
                                    <?php $fotoActual = $atleta->getFoto(); ?>
                                    <?php if (!empty($fotoActual)): ?>
                                        <img src="./Public/uploads/<?php echo htmlspecialchars($fotoActual); ?>" alt="Foto" class="rounded-circle mb-2" style="width:100px;height:100px;object-fit:cover;border:3px solid var(--primary);">
                                    <?php else: ?>
                                        <div class="avatar-circle mb-2" style="width:100px;height:100px;font-size:2rem;margin:0 auto;">
                                            <?php echo strtoupper(substr($atleta->getPrimerNombre() ?? 'A', 0, 1) . substr($atleta->getPrimerApellido() ?? 'A', 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                    <label class="custom-form-label">Cambiar foto:</label>
                                    <input type="file" name="foto_atleta" accept="image/*" class="form-control form-control-sm">
                                    <small class="text-muted">JPG/PNG, máx. 2MB</small>
                                </div>
                                <div class="col-md-4 text-center">
                                    <label class="custom-form-label">Cédula (PDF):</label>
                                    <?php $docCed = $atleta->getDocumentoCedula(); ?>
                                    <?php if (!empty($docCed)): ?>
                                        <div class="p-2 rounded border bg-light mb-2">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size:1.8rem;"></i>
                                            <p class="small fw-bold mb-1">Cédula actual</p>
                                            <a href="./Public/uploads/<?php echo htmlspecialchars($docCed); ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-eye me-1"></i>Ver
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="p-2 rounded border bg-light mb-2">
                                            <i class="bi bi-file-earmark-x text-muted" style="font-size:1.8rem;"></i>
                                            <p class="small text-muted mb-0">Sin cargar</p>
                                        </div>
                                    <?php endif; ?>
                                    <label class="custom-form-label">Actualizar cédula:</label>
                                    <input type="file" name="documento_cedula" accept="application/pdf" class="form-control form-control-sm">
                                    <small class="text-muted">PDF, máx. 5MB</small>
                                </div>
                                <div class="col-md-4 text-center">
                                    <label class="custom-form-label">Partida de nacimiento (PDF):</label>
                                    <?php $docPart = $atleta->getDocumentoPartida(); ?>
                                    <?php if (!empty($docPart)): ?>
                                        <div class="p-2 rounded border bg-light mb-2">
                                            <i class="bi bi-file-earmark-pdf-fill text-danger" style="font-size:1.8rem;"></i>
                                            <p class="small fw-bold mb-1">Partida actual</p>
                                            <a href="./Public/uploads/<?php echo htmlspecialchars($docPart); ?>" target="_blank" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-eye me-1"></i>Ver
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="p-2 rounded border bg-light mb-2">
                                            <i class="bi bi-file-earmark-x text-muted" style="font-size:1.8rem;"></i>
                                            <p class="small text-muted mb-0">Sin cargar</p>
                                        </div>
                                    <?php endif; ?>
                                    <label class="custom-form-label">Actualizar partida:</label>
                                    <input type="file" name="documento_partida" accept="application/pdf" class="form-control form-control-sm">
                                    <small class="text-muted">PDF, máx. 5MB</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- PASO 2: CATEGORÍA Y UNIFORME -->
                    <!-- ============================================ -->
                    <div class="wizard-panel" data-panel="2">
                        <div class="card form-section-card mb-0">
                            <h2 class="section-card-title"><i class="bi bi-shield-fill me-2"></i>Categoría y Uniforme</h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="custom-form-label">Categoría:</label>
                                    <select class="form-select custom-input" name="categoria_atleta" required>
                                        <option value="" disabled>Seleccione...</option>
                                        <?php
                                        $cats = ['Sub-4','Sub-5','Sub-6','Sub-7','Sub-8','Sub-9','Sub-10','Sub-11','Sub-12','Sub-13','Sub-14','Sub-15','Sub-16','Sub-17','Sub-18'];
                                        foreach($cats as $cat): ?>
                                        <option value="<?php echo $cat; ?>" <?php echo ($categoria == $cat) ? 'selected' : ''; ?>><?php echo $cat; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">Año:</label>
                                    <select class="form-select custom-input" name="categoria_anio" required>
                                        <option value="" disabled>Seleccione...</option>
                                        <option value="Primero" <?php echo ($categoriaAnio == 'Primero') ? 'selected' : ''; ?>>Primero</option>
                                        <option value="Segundo" <?php echo ($categoriaAnio == 'Segundo') ? 'selected' : ''; ?>>Segundo</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">Talla de franela:</label>
                                    <input type="text" name="talla_franela" class="form-control custom-input"
                                        placeholder="Ej. 12, S, M" required pattern="[A-Za-z0-9\s]+"
                                        value="<?php echo htmlspecialchars($uniforme ? $uniforme->getFranelaTalla() : ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">Número de franela:</label>
                                    <input type="number" name="numero_franela" class="form-control custom-input"
                                        placeholder="Dorsal" min="1" max="99"
                                        value="<?php echo htmlspecialchars($uniforme ? $uniforme->getFranelaNumero() : ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">Talla de short:</label>
                                    <input type="text" name="talla_short" class="form-control custom-input"
                                        required pattern="[A-Za-z0-9\s]+"
                                        value="<?php echo htmlspecialchars($uniforme ? $uniforme->getShortTalla() : ''); ?>"
                                        placeholder="Ej. S, M, L, 28">
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">Talla de zapatos:</label>
                                    <input type="text" name="talla_zapatos" class="form-control custom-input"
                                        required pattern="[0-9]+(?:\.[0-9]+)?"
                                        value="<?php echo htmlspecialchars($uniforme ? $uniforme->getZapatosTalla() : ''); ?>"
                                        placeholder="Ej. 39, 40, 41.5">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- PASO 3: FICHA DE SALUD -->
                    <!-- ============================================ -->
                    <div class="wizard-panel" data-panel="3">
                        <div class="card form-section-card mb-0">
                            <h2 class="section-card-title"><i class="bi bi-heart-pulse me-2"></i>Ficha de Salud</h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="custom-form-label">Tipo de sangre:</label>
                                    <input type="text" name="tipo_sangre" class="form-control custom-input"
                                        placeholder="Ej. ORH+" required pattern="[A-Za-z0-9+\-]+"
                                        value="<?php echo htmlspecialchars($salud ? $salud->getTipoSangre() : ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">Alergias:</label>
                                    <input type="text" name="alergias" class="form-control custom-input"
                                        placeholder="Ninguna / Alimentos / Medicamentos"
                                        pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                                        value="<?php echo htmlspecialchars($salud ? $salud->getAlergias() : ''); ?>">
                                </div>
                                <div class="col-12">
                                    <label class="custom-form-label">Padecimiento crónico:</label>
                                    <input type="text" name="padecimiento_cronico" class="form-control custom-input"
                                        placeholder="Ninguno / Asma / Condición específica"
                                        pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                                        value="<?php echo htmlspecialchars($salud ? $salud->getPadecimiento() : ''); ?>">
                                </div>
                                <div class="col-12">
                                    <label class="custom-form-label">Medicamento que no debe tomar:</label>
                                    <input type="text" name="medicamento_restriccion" class="form-control custom-input"
                                        placeholder="Restricciones médicas"
                                        pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                                        value="<?php echo htmlspecialchars($salud ? $salud->getMedicamentoNoTomar() : ''); ?>">
                                </div>
                                <div class="col-12">
                                    <label class="custom-form-label">Lesiones previas relevantes:</label>
                                    <input type="text" name="lesiones_previas" class="form-control custom-input"
                                        placeholder="Esguinces, fracturas, operaciones..."
                                        pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                                        value="<?php echo htmlspecialchars($salud ? $salud->getLesionesPrevias() : ''); ?>">
                                </div>

                                <div class="col-12"><hr class="my-1"></div>

                                <div class="col-md-6">
                                    <label class="custom-form-label">Póliza de seguros:</label>
                                    <select name="poliza_seguro" class="form-select custom-input" id="polizaSeguro<?php echo $atleta_id; ?>">
                                        <option value="">Seleccione una opción</option>
                                        <option value="posee" <?php echo ($salud && $salud->getPolizaSeguro() == 'posee') ? 'selected' : ''; ?>>Posee</option>
                                        <option value="no_posee" <?php echo ($salud && $salud->getPolizaSeguro() == 'no_posee') ? 'selected' : ''; ?>>No posee</option>
                                    </select>
                                </div>
                                <div class="col-md-6 seguro-field<?php echo $atleta_id; ?>">
                                    <label class="custom-form-label">Vigencia:</label>
                                    <input type="date" name="vigencia_seguro" class="form-control custom-input"
                                        value="<?php echo htmlspecialchars($salud ? $salud->getVigencia() : ''); ?>">
                                </div>
                                <div class="col-md-6 seguro-field<?php echo $atleta_id; ?>">
                                    <label class="custom-form-label">Empresa aseguradora:</label>
                                    <input type="text" name="empresa_aseguradora" class="form-control custom-input"
                                        pattern="[A-Za-záéíóúñÑ\s,\.\-]+"
                                        value="<?php echo htmlspecialchars($salud ? $salud->getEmpresa() : ''); ?>"
                                        placeholder="Nombre de la aseguradora">
                                </div>
                                <div class="col-md-6 seguro-field<?php echo $atleta_id; ?>">
                                    <label class="custom-form-label">Cobertura contratada:</label>
                                    <input type="text" name="cobertura_contratada" class="form-control custom-input"
                                        pattern="[A-Za-záéíóúñÑ\s,\.\-]+"
                                        value="<?php echo htmlspecialchars($salud ? $salud->getCobertura() : ''); ?>"
                                        placeholder="Ej. Básica, Amplia, Total">
                                </div>
                                <div class="col-12 seguro-field<?php echo $atleta_id; ?>">
                                    <label class="custom-form-label">Número de póliza:</label>
                                    <input type="text" name="numero_poliza" class="form-control custom-input"
                                        pattern="[A-Za-z0-9-]+"
                                        value="<?php echo htmlspecialchars($salud ? $salud->getNroPoliza() : ''); ?>"
                                        placeholder="Número de póliza">
                                </div>
                                <div class="col-md-6 seguro-field<?php echo $atleta_id; ?>">
                                    <label class="custom-form-label">Médico tratante:</label>
                                    <input type="text" name="medico_tratante" class="form-control custom-input"
                                        pattern="[A-Za-záéíóúñÑ\s.]+"
                                        value="<?php echo htmlspecialchars($salud ? $salud->getNombreMedico() : ''); ?>"
                                        placeholder="Dr. Nombre Apellido">
                                </div>
                                <div class="col-md-6 seguro-field<?php echo $atleta_id; ?>">
                                    <label class="custom-form-label">Teléfono del médico:</label>
                                    <input type="tel" name="telefono_medico" class="form-control custom-input"
                                        pattern="[0-9+\-\s()]{7,15}"
                                        value="<?php echo htmlspecialchars($salud ? $salud->getTelefonoMedico() : ''); ?>"
                                        placeholder="0412-1234567">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================================ -->
                    <!-- PASO 4: ACADÉMICO Y HOBBIIE -->
                    <!-- ============================================ -->
                    <div class="wizard-panel" data-panel="4">
                        <div class="card form-section-card mb-0">
                            <h2 class="section-card-title"><i class="bi bi-mortarboard me-2"></i>Información Académica</h2>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="custom-form-label">Nombre del colegio:</label>
                                    <input type="text" name="nombre_colegio" class="form-control custom-input" required
                                        pattern="[A-Za-záéíóúñÑ\s,.\-]+"
                                        value="<?php echo htmlspecialchars($colegio ? $colegio->getNombre() : ''); ?>"
                                        placeholder="Nombre completo del colegio">
                                </div>
                                <div class="col-md-8">
                                    <label class="custom-form-label">Dirección del colegio:</label>
                                    <input type="text" name="direccion_colegio" class="form-control custom-input"
                                        pattern="[A-Za-záéíóúñÑ0-9\s,.°#\-]+"
                                        value="<?php echo htmlspecialchars($colegio ? $colegio->getDireccion() : ''); ?>"
                                        placeholder="Calle/Avenida, Número, Urbanización">
                                </div>
                                <div class="col-md-4">
                                    <label class="custom-form-label">Grado / Año cursante:</label>
                                    <input type="text" name="grado_cursante" class="form-control custom-input" required
                                        pattern="[A-Za-záéíóúñÑ0-9\s°]+"
                                        value="<?php echo htmlspecialchars($grado ? $grado->getGrado() : ''); ?>"
                                        placeholder="Ej. 4to Grado">
                                </div>
                            </div>
                        </div>

                        <div class="card form-section-card mb-0">
                            <h2 class="section-card-title"><i class="bi bi-palette me-2"></i>Actividades Extra / Hobbie</h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="custom-form-label">Nombre del hobbie:</label>
                                    <input type="text" name="nombre_hobbie" class="form-control custom-input"
                                        placeholder="Música, pintura, ajedrez..."
                                        pattern="[A-Za-záéíóúñÑ\s,\.\-]+"
                                        value="<?php echo htmlspecialchars($hobbie ? $hobbie->getNombre() : ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="custom-form-label">Horario del hobbie:</label>
                                    <input type="text" name="horario_hobbie" class="form-control custom-input"
                                        placeholder="Ej. Martes y Jueves 4pm"
                                        pattern="[A-Za-záéíóúñÑ0-9\s,.:apmAPM\-]+"
                                        value="<?php echo htmlspecialchars($horarioHobbie ?: ''); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===== WIZARD NAVIGATION ===== -->
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <button type="button" class="btn btn-outline-secondary rounded-pill px-3 d-none" id="btn-wiz-prev-<?php echo $atleta_id; ?>">
                            <i class="bi bi-arrow-left me-1"></i>Anterior
                        </button>
                        <div class="flex-grow-1"></div>
                        <button type="button" class="btn btn-action-green rounded-pill px-3" id="btn-wiz-next-<?php echo $atleta_id; ?>">
                            Siguiente <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                        <button type="submit" class="btn btn-action-green rounded-pill px-3 d-none" id="btn-wiz-submit-<?php echo $atleta_id; ?>">
                            <i class="bi bi-save me-1"></i>Actualizar
                        </button>
                    </div>

                    <div class="form-rest"></div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- ===== WIZARD NAVIGATION JS ===== -->
<script>
if (!window.initActualizarModal) {
window.initActualizarModal = function(aid) {
    var form = document.getElementById('actualizarAtletaForm' + aid);
    if (!form || form.dataset.wizardInit) return;
    form.dataset.wizardInit = 'true';

    var panels = document.querySelectorAll('#modalActualizar' + aid + ' .wizard-panel');
    var steps = document.querySelectorAll('#stepper-' + aid + ' .wizard-step');
    var btnPrev = document.getElementById('btn-wiz-prev-' + aid);
    var btnNext = document.getElementById('btn-wiz-next-' + aid);
    var btnSubmit = document.getElementById('btn-wiz-submit-' + aid);
    var currentStep = 1;
    var totalSteps = panels.length;

    function goToStep(step) {
        if (step < 1 || step > totalSteps) return;
        panels.forEach(function(p) { p.classList.remove('active'); });
        steps.forEach(function(s) {
            s.classList.remove('active', 'completed');
            var sn = parseInt(s.dataset.step);
            if (sn < step) s.classList.add('completed');
            if (sn === step) s.classList.add('active');
        });
        var target = document.querySelector('#modalActualizar' + aid + ' [data-panel="' + step + '"]');
        if (target) target.classList.add('active');
        currentStep = step;
        if (btnPrev) btnPrev.classList.toggle('d-none', step === 1);
        if (btnNext) btnNext.classList.toggle('d-none', step === totalSteps);
        if (btnSubmit) btnSubmit.classList.toggle('d-none', step !== totalSteps);
    }

    if (btnNext) btnNext.addEventListener('click', function() { goToStep(currentStep + 1); });
    if (btnPrev) btnPrev.addEventListener('click', function() { goToStep(currentStep - 1); });

    goToStep(1);

    /* Custom submit handler with file upload support */
    form.dataset.ajaxBound = 'true';
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();
        if (!confirm('¿Quieres actualizar la información del atleta?')) return;

        var data = new FormData(form);
        var formRest = form.querySelector('.form-rest');
        if (formRest) formRest.innerHTML = '<div class="text-center py-2"><div class="spinner-border text-success spinner-border-sm me-2"></div>Guardando...</div>';

        fetch(form.getAttribute('action'), { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { if (!r.ok) throw new Error('Error del servidor'); return r.text(); })
            .then(function(html) {
                if (formRest) formRest.innerHTML = html;
                if (html.includes('alert-success')) {
                    setTimeout(function() { location.reload(); }, 1500);
                }
            })
            .catch(function(err) {
                if (formRest) formRest.innerHTML = '<div class="alert alert-danger py-2 small"><strong>Error:</strong> ' + err.message + '</div>';
            });
    });

    /* Toggle seguro */
    var polizaSelect = document.getElementById('polizaSeguro' + aid);
    function toggleSeguro() {
        var fields = document.querySelectorAll('.seguro-field' + aid);
        var disable = !polizaSelect || polizaSelect.value !== 'posee';
        fields.forEach(function(f) {
            f.style.display = disable ? 'none' : '';
            f.querySelectorAll('input').forEach(function(inp) {
                inp.disabled = disable;
            });
        });
    }
    if (polizaSelect) {
        polizaSelect.addEventListener('change', toggleSeguro);
        toggleSeguro();
    }
};
}
window.initActualizarModal('<?php echo $atleta_id; ?>');
</script>
