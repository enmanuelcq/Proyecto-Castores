<div class="modal fade" id="modalActualizar<?php echo $atleta_id; ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
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
                <div class="atleta-header-modal mb-4">
                    <div class="atleta-avatar-modal">
                        <?php 
                        echo strtoupper(substr($atleta->getPrimerNombre() ?? 'A', 0, 1) . substr($atleta->getPrimerApellido() ?? 'A', 0, 1));
                        ?>
                    </div>
                    <div class="atleta-info-modal">
                        <h4><?php echo htmlspecialchars($atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido()); ?></h4>
                        <div class="atleta-badges">
                            <span class="badge bg-primary"><?php echo htmlspecialchars($categoria); ?></span>
                            <span class="badge bg-warning text-dark">Dorsal: <?php echo htmlspecialchars($numeroFranela); ?></span>
                        </div>
                    </div>
                </div>

                <!-- ===== FORMULARIO ===== -->
                <form action="./Controller/ActualizarAtleta_controller.php" method="POST" class="FormularioAjax" id="actualizarAtletaForm<?php echo $atleta_id; ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <!-- Campos ocultos -->
                    <input type="hidden" name="atleta_id" value="<?php echo $atleta_id; ?>">
                    <input type="hidden" name="atleta_cedula_original" value="<?php echo htmlspecialchars($atleta->getCedula() ?: ''); ?>">

                    <div class="row">
                        
                        <!-- COLUMNA IZQUIERDA -->
                        <div class="col-lg-6">

                            <!-- Datos del atleta -->
                            <div class="card form-section-card">
                                <h2 class="section-card-title">🏃‍♂️ Datos del atleta</h2>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="primer_nombre<?php echo $atleta_id; ?>">1er Nombre:</label>
                                        <input 
                                            type="text" 
                                            class="form-control custom-input" 
                                            name="primer_nombre" 
                                            id="primer_nombre<?php echo $atleta_id; ?>" 
                                            pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" 
                                            maxlength="40" 
                                            required
                                            value="<?php echo htmlspecialchars($atleta->getPrimerNombre()); ?>"
                                            title="Ingrese solo letras y espacios (mínimo 3, máximo 40 caracteres)"
                                            placeholder="Ej. Juan"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="segundo_nombre<?php echo $atleta_id; ?>">2do Nombre:</label>
                                        <input 
                                            type="text" 
                                            class="form-control custom-input" 
                                            name="segundo_nombre" 
                                            id="segundo_nombre<?php echo $atleta_id; ?>" 
                                            pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" 
                                            maxlength="40"
                                            value="<?php echo htmlspecialchars($atleta->getSegundoNombre() ?: ''); ?>"
                                            title="Ingrese solo letras y espacios (mínimo 3, máximo 40 caracteres)"
                                            placeholder="Ej. Carlos"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="primer_apellido<?php echo $atleta_id; ?>">1er Apellido:</label>
                                        <input 
                                            type="text" 
                                            class="form-control custom-input" 
                                            name="primer_apellido" 
                                            id="primer_apellido<?php echo $atleta_id; ?>" 
                                            pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" 
                                            maxlength="40" 
                                            required
                                            value="<?php echo htmlspecialchars($atleta->getPrimerApellido()); ?>"
                                            title="Ingrese solo letras y espacios (mínimo 3, máximo 40 caracteres)"
                                            placeholder="Ej. Pérez"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="segundo_apellido<?php echo $atleta_id; ?>">2do Apellido:</label>
                                        <input 
                                            type="text" 
                                            class="form-control custom-input" 
                                            name="segundo_apellido" 
                                            id="segundo_apellido<?php echo $atleta_id; ?>" 
                                            pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" 
                                            maxlength="40"
                                            value="<?php echo htmlspecialchars($atleta->getSegundoApellido() ?: ''); ?>"
                                            title="Ingrese solo letras y espacios (mínimo 3, máximo 40 caracteres)"
                                            placeholder="Ej. García"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="atleta_fecha<?php echo $atleta_id; ?>">Fecha de nacimiento:</label>
                                        <input 
                                            type="date" 
                                            class="form-control custom-input" 
                                            name="atleta_fecha" 
                                            id="atleta_fecha<?php echo $atleta_id; ?>" 
                                            required
                                            value="<?php echo htmlspecialchars($atleta->getFechaNacimiento()); ?>"
                                            max="2023-12-31"
                                            min="2000-01-01"
                                            title="Seleccione una fecha válida (entre 2000 y 2018)"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="atleta_sexo<?php echo $atleta_id; ?>">Sexo:</label>
                                        <select 
                                            class="form-select custom-input" 
                                            name="atleta_sexo"
                                            id="atleta_sexo<?php echo $atleta_id; ?>" 
                                            required
                                        >
                                            <option value="" disabled>Seleccione...</option>
                                            <option value="Masculino" <?php echo ($atleta->getSexo() == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                                            <option value="Femenino" <?php echo ($atleta->getSexo() == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="atleta_cedula<?php echo $atleta_id; ?>">Cédula (si aplica):</label>
                                        <input 
                                            type="text" 
                                            class="form-control custom-input" 
                                            name="atleta_cedula" 
                                            id="atleta_cedula<?php echo $atleta_id; ?>" 
                                            pattern="[0-9]{5,8}" 
                                            maxlength="8"
                                            value="<?php echo htmlspecialchars($atleta->getCedula() ?: ''); ?>"
                                            placeholder="Opcional"
                                            title="Ingrese solo números (5 a 8 dígitos)"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="pie_dominante<?php echo $atleta_id; ?>">Pie Dominante:</label>
                                        <select 
                                            class="form-select custom-input" 
                                            name="pie_dominante"
                                            id="pie_dominante<?php echo $atleta_id; ?>" 
                                            required
                                        >
                                            <option value="" disabled>Seleccione...</option>
                                            <option value="Derecho" <?php echo ($atleta->getPieDominante() == 'Derecho') ? 'selected' : ''; ?>>Derecho (Diestro)</option>
                                            <option value="Izquierdo" <?php echo ($atleta->getPieDominante() == 'Izquierdo') ? 'selected' : ''; ?>>Izquierdo (Zurdo)</option>
                                            <option value="Ambidiestro" <?php echo ($atleta->getPieDominante() == 'Ambidiestro') ? 'selected' : ''; ?>>Ambidiestro</option>
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="custom-form-label" for="atleta_direccion<?php echo $atleta_id; ?>">Dirección de habitación:</label>
                                        <input 
                                            type="text" 
                                            class="form-control custom-input" 
                                            name="atleta_direccion" 
                                            id="atleta_direccion<?php echo $atleta_id; ?>" 
                                            pattern="[A-Za-z0-9áéíóúÁÉÍÓÚñÑ#., ]{3,100}" 
                                            maxlength="200" 
                                            required
                                            value="<?php echo htmlspecialchars($atleta->getDireccion()); ?>"
                                            title="Ingrese dirección (letras, números y espacios, mínimo 3, máximo 100 caracteres)"
                                            placeholder="Calle/Avenida, Número, Urbanización"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Información de Categoria -->
                            <div class="card form-section-card">
                                <h2 class="section-card-title">⚽ Información de Categoria</h2>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="categoria_atleta<?php echo $atleta_id; ?>">Categoría:</label>
                                        <select 
                                            class="form-select custom-input" 
                                            name="categoria_atleta" 
                                            id="categoria_atleta<?php echo $atleta_id; ?>" 
                                            required
                                        >
                                            <option value="" disabled>Seleccione...</option>
                                            <?php 
                                            $categorias = ['Sub-4','Sub-5','Sub-6','Sub-7','Sub-8','Sub-9','Sub-10','Sub-11','Sub-12','Sub-13','Sub-14','Sub-15','Sub-16','Sub-17','Sub-18'];
                                            foreach($categorias as $cat): 
                                            ?>
                                            <option value="<?php echo $cat; ?>" <?php echo ($categoria == $cat) ? 'selected' : ''; ?>>
                                                <?php echo $cat; ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="categoria_anio<?php echo $atleta_id; ?>">Año:</label>
                                        <select 
                                            class="form-select custom-input" 
                                            name="categoria_anio" 
                                            id="categoria_anio<?php echo $atleta_id; ?>" 
                                            required
                                        >
                                            <option value="" disabled>Seleccione...</option>
                                            <option value="Primero" <?php echo ($categoriaAnio == 'Primero') ? 'selected' : ''; ?>>Primero</option>
                                            <option value="Segundo" <?php echo ($categoriaAnio == 'Segundo') ? 'selected' : ''; ?>>Segundo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Uniforme -->
                            <div class="card form-section-card">
                                <h2 class="section-card-title">🎽 Uniforme</h2>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="talla_franela<?php echo $atleta_id; ?>">Talla de franela:</label>
                                        <input 
                                            type="text" 
                                            id="talla_franela<?php echo $atleta_id; ?>" 
                                            name="talla_franela" 
                                            class="form-control custom-input"  
                                            placeholder="Ej. 12, S, M" 
                                            required 
                                            pattern="[A-Za-z0-9\s]+" 
                                            value="<?php echo htmlspecialchars($uniforme ? $uniforme->getFranelaTalla() : ''); ?>"
                                            title="Ingrese una talla válida (ej. S, M, L, XL, 12, 14)"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="numero_franela<?php echo $atleta_id; ?>">Número de franela:</label>
                                        <input 
                                            type="number" 
                                            id="numero_franela<?php echo $atleta_id; ?>" 
                                            name="numero_franela" 
                                            class="form-control custom-input" 
                                            placeholder="Dorsal sugerido" 
                                            min="1" 
                                            max="99" 
                                            pattern="[0-9]{1,2}" 
                                            value="<?php echo htmlspecialchars($uniforme ? $uniforme->getFranelaNumero() : ''); ?>"
                                            title="Ingrese un número entre 1 y 99"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="talla_short<?php echo $atleta_id; ?>">Talla de short:</label>
                                        <input 
                                            type="text" 
                                            id="talla_short<?php echo $atleta_id; ?>" 
                                            name="talla_short" 
                                            class="form-control custom-input" 
                                            required 
                                            pattern="[A-Za-z0-9\s]+" 
                                            value="<?php echo htmlspecialchars($uniforme ? $uniforme->getShortTalla() : ''); ?>"
                                            title="Ingrese una talla válida (ej. S, M, L, XL, 28, 30)" 
                                            placeholder="Ej. S, M, L, XL, 28"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="talla_zapatos<?php echo $atleta_id; ?>">Talla de zapatos:</label>
                                        <input 
                                            type="text" 
                                            id="talla_zapatos<?php echo $atleta_id; ?>" 
                                            name="talla_zapatos" 
                                            class="form-control custom-input" 
                                            required 
                                            pattern="[0-9]+(?:\.[0-9]+)?" 
                                            value="<?php echo htmlspecialchars($uniforme ? $uniforme->getZapatosTalla() : ''); ?>"
                                            title="Ingrese una talla de zapato válida (ej. 39, 40, 41.5)" 
                                            placeholder="Ej. 39, 40, 41.5"
                                        >
                                    </div>
                                </div>
                            </div>

                        </div> <!-- FIN COLUMNA IZQUIERDA -->

                        <!-- COLUMNA DERECHA -->
                        <div class="col-lg-6">
                            
                            <!-- Ficha de Salud -->
                            <div class="card form-section-card">
                                <h2 class="section-card-title">🩺 Ficha de Salud</h2>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="tipo_sangre<?php echo $atleta_id; ?>">Tipo de sangre:</label>
                                        <input 
                                            type="text" 
                                            id="tipo_sangre<?php echo $atleta_id; ?>"
                                            name="tipo_sangre"
                                            class="form-control custom-input" 
                                            placeholder="Ej. ORH+" 
                                            required
                                            pattern="[A-Za-z0-9+\-]+"
                                            value="<?php echo htmlspecialchars($salud ? $salud->getTipoSangre() : ''); ?>"
                                            title="Ingrese un tipo de sangre válido (ej. A+, B-, O+, AB+)"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="alergias<?php echo $atleta_id; ?>">Alergias:</label>
                                        <input 
                                            type="text" 
                                            id="alergias<?php echo $atleta_id; ?>"
                                            name="alergias"
                                            class="form-control custom-input" 
                                            placeholder="Ninguna / Alimentos / Medicamentos"
                                            pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                                            value="<?php echo htmlspecialchars($salud ? $salud->getAlergias() : ''); ?>"
                                            title="Ingrese las alergias (solo letras, espacios, comas y guiones)"
                                        >
                                    </div>
                                    <div class="col-12">
                                        <label class="custom-form-label" for="padecimiento_cronico<?php echo $atleta_id; ?>">Padecimiento crónico:</label>
                                        <input 
                                            type="text" 
                                            id="padecimiento_cronico<?php echo $atleta_id; ?>"
                                            name="padecimiento_cronico"
                                            class="form-control custom-input" 
                                            placeholder="Ninguno / Asma / C condición específica"
                                            pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                                            value="<?php echo htmlspecialchars($salud ? $salud->getPadecimiento() : ''); ?>"
                                            title="Ingrese el padecimiento (solo letras, espacios, comas y guiones)"
                                        >
                                    </div>
                                    <div class="col-12">
                                        <label class="custom-form-label" for="medicamento_restriccion<?php echo $atleta_id; ?>">Medicamento que no debe tomar:</label>
                                        <input 
                                            type="text" 
                                            id="medicamento_restriccion<?php echo $atleta_id; ?>"
                                            name="medicamento_restriccion"
                                            class="form-control custom-input" 
                                            placeholder="Restricciones médicas"
                                            pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                                            value="<?php echo htmlspecialchars($salud ? $salud->getMedicamentoNoTomar() : ''); ?>"
                                            title="Ingrese las restricciones médicas"
                                        >
                                    </div>
                                    <div class="col-12">
                                        <label class="custom-form-label" for="lesiones_previas<?php echo $atleta_id; ?>">Lesiones previas relevantes:</label>
                                        <input 
                                            type="text" 
                                            id="lesiones_previas<?php echo $atleta_id; ?>"
                                            name="lesiones_previas"
                                            class="form-control custom-input" 
                                            placeholder="Esguinces, fracturas, operaciones..."
                                            pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                                            value="<?php echo htmlspecialchars($salud ? $salud->getLesionesPrevias() : ''); ?>"
                                            title="Ingrese las lesiones previas"
                                        >
                                    </div>
                                    
                                    <!-- Campo de póliza de seguros -->
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="polizaSeguro<?php echo $atleta_id; ?>">Póliza de seguros:</label>
                                        <select 
                                            id="polizaSeguro<?php echo $atleta_id; ?>" 
                                            name="poliza_seguro"
                                            class="form-select custom-input" 
                                            onchange="toggleSeguroFields<?php echo $atleta_id; ?>()"
                                        >
                                            <option value="">Seleccione una opción</option>
                                            <option value="posee" <?php echo ($salud && $salud->getPolizaSeguro() == 'posee') ? 'selected' : ''; ?>>Posee</option>
                                            <option value="no_posee" <?php echo ($salud && $salud->getPolizaSeguro() == 'no_posee') ? 'selected' : ''; ?>>No posee</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Campos del seguro -->
                                    <div class="col-md-6 seguro-field<?php echo $atleta_id; ?>">
                                        <label class="custom-form-label" for="vigencia_seguro<?php echo $atleta_id; ?>">Vigencia:</label>
                                        <input 
                                            type="date" 
                                            id="vigencia_seguro<?php echo $atleta_id; ?>"
                                            name="vigencia_seguro"
                                            class="form-control custom-input"
                                            value="<?php echo htmlspecialchars($salud ? $salud->getVigencia() : ''); ?>"
                                        >
                                    </div>
                                    <div class="col-md-6 seguro-field<?php echo $atleta_id; ?>">
                                        <label class="custom-form-label" for="empresa_aseguradora<?php echo $atleta_id; ?>">Empresa aseguradora:</label>
                                        <input 
                                            type="text" 
                                            id="empresa_aseguradora<?php echo $atleta_id; ?>"
                                            name="empresa_aseguradora"
                                            class="form-control custom-input"
                                            pattern="[A-Za-záéíóúñÑ\s,\.\-]+"
                                            value="<?php echo htmlspecialchars($salud ? $salud->getEmpresa() : ''); ?>"
                                            title="Ingrese el nombre de la empresa aseguradora"
                                            placeholder="Nombre de la aseguradora"
                                        >
                                    </div>
                                    <div class="col-md-6 seguro-field<?php echo $atleta_id; ?>">
                                        <label class="custom-form-label" for="cobertura_contratada<?php echo $atleta_id; ?>">Cobertura contratada:</label>
                                        <input 
                                            type="text" 
                                            id="cobertura_contratada<?php echo $atleta_id; ?>"
                                            name="cobertura_contratada"
                                            class="form-control custom-input"
                                            pattern="[A-Za-záéíóúñÑ\s,\.\-]+"
                                            value="<?php echo htmlspecialchars($salud ? $salud->getCobertura() : ''); ?>"
                                            title="Ingrese la cobertura contratada"
                                            placeholder="Ej. Básica, Amplia, Total"
                                        >
                                    </div>
                                    <div class="col-12 seguro-field<?php echo $atleta_id; ?>">
                                        <label class="custom-form-label" for="numero_poliza<?php echo $atleta_id; ?>">Número de póliza de seguros:</label>
                                        <input 
                                            type="text" 
                                            id="numero_poliza<?php echo $atleta_id; ?>"
                                            name="numero_poliza"
                                            class="form-control custom-input"
                                            pattern="[A-Za-z0-9-]+"
                                            value="<?php echo htmlspecialchars($salud ? $salud->getNroPoliza() : ''); ?>"
                                            title="Ingrese el número de póliza (letras, números y guiones)"
                                            placeholder="Número de póliza"
                                        >
                                    </div>
                                    <div class="col-md-6 seguro-field<?php echo $atleta_id; ?>">
                                        <label class="custom-form-label" for="medico_tratante<?php echo $atleta_id; ?>">Nombre del médico tratante:</label>
                                        <input 
                                            type="text" 
                                            id="medico_tratante<?php echo $atleta_id; ?>"
                                            name="medico_tratante"
                                            class="form-control custom-input"
                                            pattern="[A-Za-záéíóúñÑ\s.]+"
                                            value="<?php echo htmlspecialchars($salud ? $salud->getNombreMedico() : ''); ?>"
                                            title="Ingrese el nombre completo del médico"
                                            placeholder="Dr. Nombre Apellido"
                                        >
                                    </div>
                                    <div class="col-md-6 seguro-field<?php echo $atleta_id; ?>">
                                        <label class="custom-form-label" for="telefono_medico<?php echo $atleta_id; ?>">Teléfono de emergencia del médico:</label>
                                        <input 
                                            type="tel" 
                                            id="telefono_medico<?php echo $atleta_id; ?>"
                                            name="telefono_medico"
                                            class="form-control custom-input"
                                            pattern="[0-9+\-\s()]{7,15}"
                                            value="<?php echo htmlspecialchars($salud ? $salud->getTelefonoMedico() : ''); ?>"
                                            title="Ingrese un número de teléfono válido (ej. 0412-1234567)"
                                            placeholder="0412-1234567"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Información Académica -->
                            <div class="card form-section-card">
                                <h2 class="section-card-title">🏫 Información Académica</h2>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="custom-form-label" for="nombre_colegio<?php echo $atleta_id; ?>">Nombre del colegio:</label>
                                        <input 
                                            type="text" 
                                            id="nombre_colegio<?php echo $atleta_id; ?>"
                                            name="nombre_colegio"
                                            class="form-control custom-input" 
                                            required
                                            pattern="[A-Za-záéíóúñÑ\s,.\-]+"
                                            value="<?php echo htmlspecialchars($colegio ? $colegio->getNombre() : ''); ?>"
                                            title="Ingrese el nombre del colegio (solo letras, espacios, puntos y comas)"
                                            placeholder="Nombre completo del colegio"
                                        >
                                    </div>
                                    <div class="col-md-8">
                                        <label class="custom-form-label" for="direccion_colegio<?php echo $atleta_id; ?>">Dirección del colegio:</label>
                                        <input 
                                            type="text" 
                                            id="direccion_colegio<?php echo $atleta_id; ?>"
                                            name="direccion_colegio"
                                            class="form-control custom-input"
                                            pattern="[A-Za-záéíóúñÑ0-9\s,.°#\-]+"
                                            value="<?php echo htmlspecialchars($colegio ? $colegio->getDireccion() : ''); ?>"
                                            title="Ingrese la dirección completa (letras, números, espacios, puntos, comas, #, ° y -)"
                                            placeholder="Calle/Avenida, Número, Urbanización"
                                        >
                                    </div>
                                    <div class="col-md-4">
                                        <label class="custom-form-label" for="grado_cursante<?php echo $atleta_id; ?>">Grado / Año cursante:</label>
                                        <input 
                                            type="text" 
                                            id="grado_cursante<?php echo $atleta_id; ?>"
                                            name="grado_cursante"
                                            class="form-control custom-input" 
                                            placeholder="Ej. 4to Grado" 
                                            required
                                            pattern="[A-Za-záéíóúñÑ0-9\s°]+"
                                            value="<?php echo htmlspecialchars($grado ? $grado->getGrado() : ''); ?>"
                                            title="Ingrese el grado o año (ej. 4to Grado, 5° Año)"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Actividades Extra / Hobbie -->
                            <div class="card form-section-card">
                                <h2 class="section-card-title">🎨 Actividades Extra / Hobbie</h2>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="nombre_hobbie<?php echo $atleta_id; ?>">Nombre del hobbie secundario:</label>
                                        <input 
                                            type="text" 
                                            id="nombre_hobbie<?php echo $atleta_id; ?>"
                                            name="nombre_hobbie"
                                            class="form-control custom-input" 
                                            placeholder="Música, pintura, ajedrez..."
                                            pattern="[A-Za-záéíóúñÑ\s,\.\-]+"
                                            value="<?php echo htmlspecialchars($hobbie ? $hobbie->getNombre() : ''); ?>"
                                            title="Ingrese el nombre del hobbie (solo letras, espacios, comas, puntos y guiones)"
                                        >
                                    </div>
                                    <div class="col-md-6">
                                        <label class="custom-form-label" for="horario_hobbie<?php echo $atleta_id; ?>">Horario del hobbie:</label>
                                        <input 
                                            type="text" 
                                            id="horario_hobbie<?php echo $atleta_id; ?>"
                                            name="horario_hobbie"
                                            class="form-control custom-input" 
                                            placeholder="Ej. Martes y Jueves 4pm"
                                            pattern="[A-Za-záéíóúñÑ0-9\s,.:apmAPM\-]+"
                                            value="<?php echo htmlspecialchars($horarioHobbie ?: ''); ?>"
                                            title="Ingrese el horario (ej. Martes y Jueves 4pm, Lunes a Viernes 9-11am)"
                                        >
                                    </div>
                                </div>
                            </div>

                        </div> <!-- FIN COLUMNA DERECHA -->

                    </div> <!-- FIN ROW -->

                    <!-- ===== BOTONES ===== -->
                    <div class="text-center my-4">
                        <button type="submit" class="btn btn-action-green">
                            <i class="bi bi-save me-2"></i>Actualizar Datos
                        </button>
                        <button type="button" class="btn btn-secondary ms-2" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                    </div>

                    <div class="form-rest"></div>

                </form>
            </div>
        </div>
    </div>
</div>

<!-- ===== SCRIPT PARA TOGGLE DE SEGURO ===== -->
<script>
function toggleSeguroFields<?php echo $atleta_id; ?>() {
    var select = document.getElementById('polizaSeguro<?php echo $atleta_id; ?>');
    var fields = document.querySelectorAll('.seguro-field<?php echo $atleta_id; ?>');
    if (select.value === 'posee') {
        fields.forEach(function(field) {
            field.style.display = 'block';
            var inputs = field.querySelectorAll('input, select');
            inputs.forEach(function(input) {
                input.disabled = false;
                input.required = true;
            });
        });
    } else {
        fields.forEach(function(field) {
            field.style.display = 'none';
            var inputs = field.querySelectorAll('input, select');
            inputs.forEach(function(input) {
                input.disabled = true;
                input.required = false;
            });
        });
    }
}

// Ejecutar al cargar el modal para inicializar el estado
document.addEventListener('DOMContentLoaded', function() {
    toggleSeguroFields<?php echo $atleta_id; ?>();
});
</script>