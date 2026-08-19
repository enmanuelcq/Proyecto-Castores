<div class="container py-4">
    <header class="register-header d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h1 class="register-title text-uppercase m-0">Fichaje y Registro de Jugadores</h1>
            <p class="register-subtitle m-0">¡Un nuevo miembro para la familia de Los Castores!</p>
        </div>
    </header>

    <!-- WIZARD STEPPER -->
    <div class="wizard-stepper mb-4">
        <div class="wizard-step active" data-step="1">
            <div class="wizard-step-circle">1</div>
            <span class="wizard-step-label">Representante</span>
        </div>
        <div class="wizard-step-line"></div>
        <div class="wizard-step" data-step="2">
            <div class="wizard-step-circle">2</div>
            <span class="wizard-step-label">Atleta</span>
        </div>
        <div class="wizard-step-line"></div>
        <div class="wizard-step" data-step="3">
            <div class="wizard-step-circle">3</div>
            <span class="wizard-step-label">Categoría y Uniforme</span>
        </div>
        <div class="wizard-step-line"></div>
        <div class="wizard-step" data-step="4">
            <div class="wizard-step-circle">4</div>
            <span class="wizard-step-label">Salud</span>
        </div>
        <div class="wizard-step-line"></div>
        <div class="wizard-step" data-step="5">
            <div class="wizard-step-circle">5</div>
            <span class="wizard-step-label">Académico</span>
        </div>
    </div>

    <form action="./Controller/RegistrarAtleta_controller.php" method="POST" enctype="multipart/form-data" id="athleteRegisterForm">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

    <!-- ============================================ -->
    <!-- PASO 1: REPRESENTANTE -->
    <!-- ============================================ -->
    <div class="wizard-panel active" data-panel="1">
        <div class="card form-section-card">
            <h2 class="section-card-title"><i class="bi bi-people-fill me-2"></i>Datos del representante</h2>
            <div id="representantes-container">
                <div class="representante-block" data-index="0">
                    <div class="row g-3">
                        <input type="hidden" name="parentesco[]" value="Representante legal">
                        <div class="col-md-6">
                            <label class="custom-form-label">Cédula del representante:</label>
                            <input type="text" name="cedula_representante[]" class="form-control custom-input cedula-rep-input" placeholder="Ej. V-12345678, E123456789" required>
                            <div class="field-feedback"></div>
                            <small class="text-info d-none cedula-rep-info"></small>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Correo:</label>
                            <input type="email" name="correo_representante[]" class="form-control custom-input" placeholder="ejemplo@correo.com" required>
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Nombres:</label>
                            <input type="text" name="nombres_representante[]" class="form-control custom-input" placeholder="Nombres completos" required>
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Apellidos:</label>
                            <input type="text" name="apellidos_representante[]" class="form-control custom-input" placeholder="Apellidos completos" required>
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Parentesco:</label>
                            <select name="parentesco[]" class="form-select custom-input">
                                <option value="Representante legal">Representante legal</option>
                                <option value="Madre">Madre</option>
                                <option value="Padre">Padre</option>
                                <option value="Tutor">Tutor</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Ocupación:</label>
                            <input type="text" name="ocupacion_representante[]" class="form-control custom-input" placeholder="Ej. Ingeniero, Médico">
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label">Teléfono:</label>
                            <input type="tel" name="telefono_representante[]" class="form-control custom-input" placeholder="+58 412-1234567" required>
                            <div class="field-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-3">
                <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-3" id="btn-add-representante">
                    <i class="bi bi-plus-circle me-1"></i> Agregar otro representante
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- PASO 2: ATLETA -->
    <!-- ============================================ -->
    <div class="wizard-panel" data-panel="2">
        <div class="card form-section-card">
            <h2 class="section-card-title"><i class="bi bi-person-fill me-2"></i>Datos del atleta</h2>
            <div class="row g-3">
                <div class="col-md-4 text-center">
                    <label class="custom-form-label">Foto del atleta:</label>
                    <div class="photo-upload-area" id="photoUploadArea">
                        <input type="file" name="foto_atleta" id="foto_atleta" accept="image/*" class="d-none">
                        <div class="photo-placeholder" id="photoPlaceholder">
                            <i class="bi bi-camera-fill"></i>
                            <span>Subir foto</span>
                        </div>
                        <img id="photoPreview" class="photo-preview d-none" alt="Preview">
                    </div>
                    <small class="text-muted">JPG o PNG, máx. 2MB</small>
                </div>
                <div class="col-md-8">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="custom-form-label" for="primer_nombre">1er Nombre:</label>
                            <input type="text" class="form-control custom-input" name="primer_nombre" id="primer_nombre" maxlength="40" required placeholder="Ej. Juan">
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label" for="segundo_nombre">2do Nombre:</label>
                            <input type="text" class="form-control custom-input" name="segundo_nombre" id="segundo_nombre" maxlength="40" placeholder="Ej. Carlos">
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label" for="primer_apellido">1er Apellido:</label>
                            <input type="text" class="form-control custom-input" name="primer_apellido" id="primer_apellido" maxlength="40" required placeholder="Ej. Pérez">
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label" for="segundo_apellido">2do Apellido:</label>
                            <input type="text" class="form-control custom-input" name="segundo_apellido" id="segundo_apellido" maxlength="40" placeholder="Ej. García">
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label" for="atleta_fecha">Fecha de nacimiento:</label>
                            <input type="date" class="form-control custom-input" name="atleta_fecha" id="atleta_fecha" required>
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label" for="atleta_sexo">Sexo:</label>
                            <select class="form-select custom-input" name="atleta_sexo" id="atleta_sexo" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label" for="atleta_cedula">Cédula del atleta (si aplica):</label>
                            <input type="text" class="form-control custom-input" name="atleta_cedula" id="atleta_cedula" maxlength="15" placeholder="Ej. V-12345678">
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="custom-form-label" for="pie_dominante">Pie Dominante:</label>
                            <select class="form-select custom-input" name="pie_dominante" id="pie_dominante" required>
                                <option value="" disabled selected>Seleccione...</option>
                                <option value="Derecho">Derecho (Diestro)</option>
                                <option value="Izquierdo">Izquierdo (Zurdo)</option>
                                <option value="Ambidiestro">Ambidiestro</option>
                            </select>
                            <div class="field-feedback"></div>
                        </div>
                        <div class="col-12">
                            <label class="custom-form-label" for="atleta_direccion">Dirección de habitación:</label>
                            <input type="text" class="form-control custom-input" name="atleta_direccion" id="atleta_direccion" maxlength="200" required placeholder="Calle/Avenida, Número, Urbanización">
                            <div class="field-feedback"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- PASO 3: CATEGORÍA Y UNIFORME -->
    <!-- ============================================ -->
    <div class="wizard-panel" data-panel="3">
        <div class="card form-section-card">
            <h2 class="section-card-title"><i class="bi bi-shield-fill me-2"></i>Categoría</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="custom-form-label" for="categoria_atleta">Categoría:</label>
                    <select class="form-select custom-input" name="categoria_atleta" id="categoria_atleta" required>
                        <option value="" disabled selected>Seleccione...</option>
                        <option value="Sub-4">Sub-4</option>
                        <option value="Sub-5">Sub-5</option>
                        <option value="Sub-6">Sub-6</option>
                        <option value="Sub-7">Sub-7</option>
                        <option value="Sub-8">Sub-8</option>
                        <option value="Sub-9">Sub-9</option>
                        <option value="Sub-10">Sub-10</option>
                        <option value="Sub-11">Sub-11</option>
                        <option value="Sub-12">Sub-12</option>
                        <option value="Sub-13">Sub-13</option>
                        <option value="Sub-14">Sub-14</option>
                        <option value="Sub-15">Sub-15</option>
                        <option value="Sub-16">Sub-16</option>
                        <option value="Sub-17">Sub-17</option>
                        <option value="Sub-18">Sub-18</option>
                    </select>
                    <div class="field-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="custom-form-label" for="categoria_anio">Año:</label>
                    <select class="form-select custom-input" name="categoria_anio" id="categoria_anio" required>
                        <option value="" disabled selected>Seleccione...</option>
                        <option value="Primero">Primero</option>
                        <option value="Segundo">Segundo</option>
                    </select>
                    <div class="field-feedback"></div>
                </div>
            </div>
        </div>
        <div class="card form-section-card">
            <h2 class="section-card-title"><i class="bi bi-shirt-fill me-2"></i>Uniforme</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="custom-form-label" for="talla_franela">Talla de franela:</label>
                    <input type="text" id="talla_franela" name="talla_franela" class="form-control custom-input" placeholder="Ej. S, M, L, 12" required>
                    <div class="field-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="custom-form-label" for="numero_franela">Número de franela:</label>
                    <input type="number" id="numero_franela" name="numero_franela" class="form-control custom-input" placeholder="Dorsal sugerido" min="1" max="99">
                    <div class="field-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="custom-form-label" for="talla_short">Talla de short:</label>
                    <input type="text" id="talla_short" name="talla_short" class="form-control custom-input" placeholder="Ej. S, M, L, 28" required>
                    <div class="field-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="custom-form-label" for="talla_zapatos">Talla de zapatos:</label>
                    <input type="text" id="talla_zapatos" name="talla_zapatos" class="form-control custom-input" placeholder="Ej. 39, 40, 41.5" required>
                    <div class="field-feedback"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- PASO 4: FICHA DE SALUD -->
    <!-- ============================================ -->
    <div class="wizard-panel" data-panel="4">
        <div class="card form-section-card">
            <h2 class="section-card-title"><i class="bi bi-heart-pulse me-2"></i>Ficha de Salud</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="custom-form-label" for="tipo_sangre">Tipo de sangre:</label>
                    <select class="form-select custom-input" id="tipo_sangre" name="tipo_sangre" required>
                        <option value="" disabled selected>Seleccione...</option>
                        <option value="A+">A+</option>
                        <option value="A-">A-</option>
                        <option value="B+">B+</option>
                        <option value="B-">B-</option>
                        <option value="AB+">AB+</option>
                        <option value="AB-">AB-</option>
                        <option value="O+">O+</option>
                        <option value="O-">O-</option>
                    </select>
                    <div class="field-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="custom-form-label" for="alergias">Alergias:</label>
                    <input type="text" id="alergias" name="alergias" class="form-control custom-input" placeholder="Ninguna / Alimentos / Medicamentos">
                    <div class="field-feedback"></div>
                </div>
                <div class="col-12">
                    <label class="custom-form-label" for="padecimiento_cronico">Padecimiento crónico:</label>
                    <input type="text" id="padecimiento_cronico" name="padecimiento_cronico" class="form-control custom-input" placeholder="Ninguno / Asma / Otra condición">
                    <div class="field-feedback"></div>
                </div>
                <div class="col-12">
                    <label class="custom-form-label" for="medicamento_restriccion">Medicamento que no debe tomar:</label>
                    <input type="text" id="medicamento_restriccion" name="medicamento_restriccion" class="form-control custom-input" placeholder="Restricciones médicas">
                    <div class="field-feedback"></div>
                </div>
                <div class="col-12">
                    <label class="custom-form-label" for="lesiones_previas">Lesiones previas relevantes:</label>
                    <input type="text" id="lesiones_previas" name="lesiones_previas" class="form-control custom-input" placeholder="Esguinces, fracturas, operaciones...">
                    <div class="field-feedback"></div>
                </div>
                <hr class="my-2">
                <div class="col-md-6">
                    <label class="custom-form-label" for="polizaSeguro">Póliza de seguros:</label>
                    <select id="polizaSeguro" name="poliza_seguro" class="form-select custom-input">
                        <option value="">Seleccione una opción</option>
                        <option value="posee">Posee</option>
                        <option value="no_posee">No posee</option>
                    </select>
                </div>
                <div class="col-md-6 seguro-field">
                    <label class="custom-form-label" for="vigencia_seguro">Vigencia:</label>
                    <input type="date" id="vigencia_seguro" name="vigencia_seguro" class="form-control custom-input" disabled>
                </div>
                <div class="col-md-6 seguro-field">
                    <label class="custom-form-label" for="empresa_aseguradora">Empresa aseguradora:</label>
                    <input type="text" id="empresa_aseguradora" name="empresa_aseguradora" class="form-control custom-input" placeholder="Nombre de la aseguradora" disabled>
                </div>
                <div class="col-md-6 seguro-field">
                    <label class="custom-form-label" for="cobertura_contratada">Cobertura contratada:</label>
                    <input type="text" id="cobertura_contratada" name="cobertura_contratada" class="form-control custom-input" placeholder="Ej. Básica, Amplia, Total" disabled>
                </div>
                <div class="col-12 seguro-field">
                    <label class="custom-form-label" for="numero_poliza">Número de póliza:</label>
                    <input type="text" id="numero_poliza" name="numero_poliza" class="form-control custom-input" placeholder="Número de póliza" disabled>
                </div>
                <div class="col-md-6 seguro-field">
                    <label class="custom-form-label" for="medico_tratante">Médico tratante:</label>
                    <input type="text" id="medico_tratante" name="medico_tratante" class="form-control custom-input" placeholder="Dr. Nombre Apellido" disabled>
                </div>
                <div class="col-md-6 seguro-field">
                    <label class="custom-form-label" for="telefono_medico">Teléfono del médico:</label>
                    <input type="tel" id="telefono_medico" name="telefono_medico" class="form-control custom-input" placeholder="+58 412-1234567" disabled>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- PASO 5: INFO ACADÉMICA Y EXTRA -->
    <!-- ============================================ -->
    <div class="wizard-panel" data-panel="5">
        <div class="card form-section-card">
            <h2 class="section-card-title"><i class="bi bi-mortarboard-fill me-2"></i>Información Académica</h2>
            <div class="row g-3">
                <div class="col-12">
                    <label class="custom-form-label" for="nombre_colegio">Nombre del colegio:</label>
                    <input type="text" id="nombre_colegio" name="nombre_colegio" class="form-control custom-input" required placeholder="Nombre completo del colegio">
                    <div class="field-feedback"></div>
                </div>
                <div class="col-md-8">
                    <label class="custom-form-label" for="direccion_colegio">Dirección del colegio:</label>
                    <input type="text" id="direccion_colegio" name="direccion_colegio" class="form-control custom-input" placeholder="Calle/Avenida, Número, Urbanización">
                    <div class="field-feedback"></div>
                </div>
                <div class="col-md-4">
                    <label class="custom-form-label" for="grado_cursante">Grado / Año cursante:</label>
                    <input type="text" id="grado_cursante" name="grado_cursante" class="form-control custom-input" required placeholder="Ej. 4to Grado">
                    <div class="field-feedback"></div>
                </div>
            </div>
        </div>
        <div class="card form-section-card">
            <h2 class="section-card-title"><i class="bi bi-palette-fill me-2"></i>Actividades Extra / Hobbie</h2>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="custom-form-label" for="nombre_hobbie">Nombre del hobbie secundario:</label>
                    <input type="text" id="nombre_hobbie" name="nombre_hobbie" class="form-control custom-input" placeholder="Música, pintura, ajedrez...">
                    <div class="field-feedback"></div>
                </div>
                <div class="col-md-6">
                    <label class="custom-form-label" for="horario_hobbie">Horario del hobbie:</label>
                    <input type="text" id="horario_hobbie" name="horario_hobbie" class="form-control custom-input" placeholder="Ej. Martes y Jueves 4pm">
                    <div class="field-feedback"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================ -->
    <!-- NAVEGACIÓN -->
    <!-- ============================================ -->
    <div class="wizard-nav d-flex justify-content-between align-items-center my-4">
        <button type="button" class="btn btn-outline-secondary rounded-pill px-4 d-none" id="btn-wizard-prev">
            <i class="bi bi-arrow-left me-1"></i> Anterior
        </button>
        <div class="flex-grow-1"></div>
        <button type="button" class="btn btn-submit-save rounded-pill px-4" id="btn-wizard-next">
            Siguiente <i class="bi bi-arrow-right ms-1"></i>
        </button>
        <button type="submit" class="btn btn-submit-save rounded-pill px-4 d-none" id="btn-wizard-submit">
            <i class="bi bi-check-circle me-1"></i> Guardar Registro
        </button>
    </div>

    </form>

    <div class="form-rest"></div>
</div>

<script src="./Public/Js/Registro_atletas.js"></script>
