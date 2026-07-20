<div class="container py-4">
    <header class="register-header d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4">
        <div>
            <h1 class="register-title text-uppercase m-0">Fichaje y Registro de Jugadores</h1>
            <p class="register-subtitle m-0">¡Un nuevo miembro para la familia de Los Castores!</p>
        </div>
    </header>

    <form action="./Controller/RegistrarAtleta_controller.php" method="POST" class="FormularioAjax" id="athleteRegisterForm">
    <div class="row">
        
        <!-- COLUMNA IZQUIERDA -->
        <div class="col-lg-6">
            
            <!-- Datos del representante -->
            <div class="card form-section-card">
                <h2 class="section-card-title">👨‍👩‍👦 Datos del representante:</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="custom-form-label" for="cedula_representante">Cédula del representante:</label>
                        <input 
                            type="text" 
                            id="cedula_representante"
                            name="cedula_representante"
                            class="form-control custom-input" 
                            placeholder="Ej. V-12345678" 
                            required
                            pattern="[VEJvej]{1}-?\d{5,10}"
                            title="Ingrese la cédula con formato (ej. V-12345678, E-123456789)"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="correo_representante">Correo:</label>
                        <input 
                            type="email" 
                            id="correo_representante"
                            name="correo_representante"
                            class="form-control custom-input" 
                            placeholder="ejemplo@correo.com" 
                            required
                            pattern="[^\s@]+@[^\s@]+\.[^\s@]+"
                            title="Ingrese un correo electrónico válido (ej. usuario@dominio.com)"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="nombres_representante">Nombres del representante:</label>
                        <input 
                            type="text" 
                            id="nombres_representante"
                            name="nombres_representante"
                            class="form-control custom-input" 
                            required
                            pattern="[A-Za-záéíóúñÑ\s]+"
                            title="Ingrese solo letras y espacios (ej. María José)"
                            placeholder="Nombres completos"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="apellidos_representante">Apellidos del representante:</label>
                        <input 
                            type="text" 
                            id="apellidos_representante"
                            name="apellidos_representante"
                            class="form-control custom-input" 
                            required
                            pattern="[A-Za-záéíóúñÑ\s]+"
                            title="Ingrese solo letras y espacios (ej. Pérez García)"
                            placeholder="Apellidos completos"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="ocupacion_representante">Ocupación:</label>
                        <input 
                            type="text" 
                            id="ocupacion_representante"
                            name="ocupacion_representante"
                            class="form-control custom-input"
                            pattern="[A-Za-záéíóúñÑ\s,\.\-]+"
                            title="Ingrese la ocupación (letras, espacios, comas, puntos y guiones)"
                            placeholder="Ej. Ingeniero, Médico, Comerciante"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="telefono_representante">Teléfono:</label>
                        <input 
                            type="tel" 
                            id="telefono_representante"
                            name="telefono_representante"
                            class="form-control custom-input" 
                            required
                            pattern="[\d\s\-]{7,15}"
                            title="Ingrese un número de teléfono válido (ej. 0412-1234567, +58 412-1234567)"
                            placeholder="0412-1234567"
                        >
                    </div>
                </div>
            </div>

            <!-- Datos del atleta -->
            <div class="card form-section-card">
                <h2 class="section-card-title">🏃‍♂️ Datos del atleta:</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="custom-form-label" for="primer_nombre">1er Nombre:</label>
                        <input 
                            type="text" 
                            class="form-control custom-input" 
                            name="primer_nombre" 
                            id="primer_nombre" 
                            pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" 
                            maxlength="40" 
                            required
                            title="Ingrese solo letras y espacios (mínimo 3, máximo 40 caracteres)"
                            placeholder="Ej. Juan"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="segundo_nombre">2do Nombre:</label>
                        <input 
                            type="text" 
                            class="form-control custom-input" 
                            name="segundo_nombre" 
                            id="segundo_nombre" 
                            pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" 
                            maxlength="40"
                            title="Ingrese solo letras y espacios (mínimo 3, máximo 40 caracteres)"
                            placeholder="Ej. Carlos"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="primer_apellido">1er Apellido:</label>
                        <input 
                            type="text" 
                            class="form-control custom-input" 
                            name="primer_apellido" 
                            id="primer_apellido" 
                            pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" 
                            maxlength="40" 
                            required
                            title="Ingrese solo letras y espacios (mínimo 3, máximo 40 caracteres)"
                            placeholder="Ej. Pérez"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="segundo_apellido">2do Apellido:</label>
                        <input 
                            type="text" 
                            class="form-control custom-input" 
                            name="segundo_apellido" 
                            id="segundo_apellido" 
                            pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}" 
                            maxlength="40"
                            title="Ingrese solo letras y espacios (mínimo 3, máximo 40 caracteres)"
                            placeholder="Ej. García"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="atleta_fecha">Fecha de nacimiento:</label>
                        <input 
                            type="date" 
                            class="form-control custom-input" 
                            name="atleta_fecha" 
                            id="atleta_fecha" 
                            required
                            max="2018-12-31"
                            min="2000-01-01"
                            title="Seleccione una fecha válida (entre 2000 y 2018)"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="atleta_sexo">Sexo:</label>
                        <select 
                            class="form-select custom-input" 
                            name="atleta_sexo"
                            id="atleta_sexo" 
                            required
                        >
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="atleta_cedula">Cédula (si aplica):</label>
                        <input 
                            type="text" 
                            class="form-control custom-input" 
                            name="atleta_cedula" 
                            id="atleta_cedula" 
                            pattern="[0-9]{5,8}" 
                            maxlength="8"
                            placeholder="Opcional"
                            title="Ingrese solo números (5 a 8 dígitos)"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="pie_dominante">Pie Dominante:</label>
                        <select 
                            class="form-select custom-input" 
                            name="pie_dominante"
                            id="pie_dominante" 
                            required
                        >
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="Derecho">Derecho (Diestro)</option>
                            <option value="Izquierdo">Izquierdo (Zurdo)</option>
                            <option value="Ambidiestro">Ambidiestro</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="custom-form-label" for="atleta_direccion">Dirección de habitación:</label>
                        <input 
                            type="text" 
                            class="form-control custom-input" 
                            name="atleta_direccion" 
                            id="atleta_direccion" 
                            pattern="[A-Za-z0-9áéíóúÁÉÍÓÚñÑ #,.]{3,100}" 
                            maxlength="200" 
                            required
                            title="Ingrese dirección (letras, números y espacios, mínimo 3, máximo 100 caracteres)"
                            placeholder="Calle/Avenida, Número, Urbanización"
                        >
                    </div>
                </div>
            </div>

            <!-- Información de Categoria -->
            <div class="card form-section-card">
                <h2 class="section-card-title">⚽ Información de Categoria:</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="custom-form-label" for="categoria_atleta">Categoría:</label>
                        <select 
                            class="form-select custom-input" 
                            name="categoria_atleta" 
                            id="categoria_atleta" 
                            required
                        >
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
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="categoria_anio">Año:</label>
                        <select 
                            class="form-select custom-input" 
                            name="categoria_anio" 
                            id="categoria_anio" 
                            required
                        >
                            <option value="" disabled selected>Seleccione...</option>
                            <option value="Primero">Primero</option>
                            <option value="Segundo">Segundo</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Uniforme -->
            <div class="card form-section-card">
                <h2 class="section-card-title">🎽 Uniforme:</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="custom-form-label" for="talla_franela">Talla de franela:</label>
                        <input 
                            type="text" id="talla_franela" name="talla_franela" class="form-control custom-input"  placeholder="Ej. 12, S, M" required pattern="[A-Za-z0-9\s]+" title="Ingrese una talla válida (ej. S, M, L, XL, 12, 14)"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="numero_franela">Número de franela:</label>
                        <input 
                            type="number" id="numero_franela" name="numero_franela" class="form-control custom-input" placeholder="Dorsal sugerido" min="1" max="99" pattern="[0-9]{1,2}" title="Ingrese un número entre 1 y 99">
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="talla_short">Talla de short:</label>
                        <input 
                            type="text" id="talla_short" name="talla_short" class="form-control custom-input" required pattern="[A-Za-z0-9\s]+" title="Ingrese una talla válida (ej. S, M, L, XL, 28, 30)" placeholder="Ej. S, M, L, XL, 28">
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="talla_zapatos">Talla de zapatos:</label>
                        <input 
                            type="text" id="talla_zapatos" name="talla_zapatos" class="form-control custom-input" required pattern="[0-9]+(?:\.[0-9]+)?" title="Ingrese una talla de zapato válida (ej. 39, 40, 41.5)" placeholder="Ej. 39, 40, 41.5">
                    </div>
                </div>
            </div>

        </div> <!-- FIN COLUMNA IZQUIERDA -->

        <!-- COLUMNA DERECHA -->
        <div class="col-lg-6">
            
            <!-- Ficha de Salud -->
            <div class="card form-section-card">
                <h2 class="section-card-title">🩺 Ficha de Salud:</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="custom-form-label" for="tipo_sangre">Tipo de sangre:</label>
                        <input 
                            type="text" 
                            id="tipo_sangre"
                            name="tipo_sangre"
                            class="form-control custom-input" 
                            placeholder="Ej. ORH+" 
                            required
                            pattern="[A-Za-z0-9+\-]+"
                            title="Ingrese un tipo de sangre válido (ej. A+, B-, O+, AB+)"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="alergias">Alergias:</label>
                        <input 
                            type="text" 
                            id="alergias"
                            name="alergias"
                            class="form-control custom-input" 
                            placeholder="Ninguna / Alimentos / Medicamentos"
                            pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                            title="Ingrese las alergias (solo letras, espacios, comas y guiones)"
                        >
                    </div>
                    <div class="col-12">
                        <label class="custom-form-label" for="padecimiento_cronico">Padecimiento crónico:</label>
                        <input 
                            type="text" 
                            id="padecimiento_cronico"
                            name="padecimiento_cronico"
                            class="form-control custom-input" 
                            placeholder="Ninguno / Asma / C condición específica"
                            pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                            title="Ingrese el padecimiento (solo letras, espacios, comas y guiones)"
                        >
                    </div>
                    <div class="col-12">
                        <label class="custom-form-label" for="medicamento_restriccion">Medicamento que no debe tomar:</label>
                        <input 
                            type="text" 
                            id="medicamento_restriccion"
                            name="medicamento_restriccion"
                            class="form-control custom-input" 
                            placeholder="Restricciones médicas"
                            pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                            title="Ingrese las restricciones médicas"
                        >
                    </div>
                    <div class="col-12">
                        <label class="custom-form-label" for="lesiones_previas">Lesiones previas relevantes:</label>
                        <input 
                            type="text" 
                            id="lesiones_previas"
                            name="lesiones_previas"
                            class="form-control custom-input" 
                            placeholder="Esguinces, fracturas, operaciones..."
                            pattern="[A-Za-záéíóúñÑ\s,\/\-]+"
                            title="Ingrese las lesiones previas"
                        >
                    </div>
                    
                    <!-- Campo de póliza de seguros -->
                    <div class="col-md-6">
                        <label class="custom-form-label" for="polizaSeguro">Póliza de seguros:</label>
                        <select 
                            id="polizaSeguro" 
                            name="poliza_seguro"
                            class="form-control custom-input" 
                            onchange="toggleSeguroFields()"
                        >
                            <option value="">Seleccione una opción</option>
                            <option value="posee">Posee</option>
                            <option value="no_posee">No posee</option>
                        </select>
                    </div>
                    
                    <!-- Campos que se deshabilitarán -->
                    <div class="col-md-6 seguro-field">
                        <label class="custom-form-label" for="vigencia_seguro">Vigencia:</label>
                        <input 
                            type="date" 
                            id="vigencia_seguro"
                            name="vigencia_seguro"
                            class="form-control custom-input"
                        >
                    </div>
                    <div class="col-md-6 seguro-field">
                        <label class="custom-form-label" for="empresa_aseguradora">Empresa aseguradora:</label>
                        <input 
                            type="text" 
                            id="empresa_aseguradora"
                            name="empresa_aseguradora"
                            class="form-control custom-input"
                            pattern="[A-Za-záéíóúñÑ\s,\.\-]+"
                            title="Ingrese el nombre de la empresa aseguradora"
                            placeholder="Nombre de la aseguradora"
                        >
                    </div>
                    <div class="col-md-6 seguro-field">
                        <label class="custom-form-label" for="cobertura_contratada">Cobertura contratada:</label>
                        <input 
                            type="text" 
                            id="cobertura_contratada"
                            name="cobertura_contratada"
                            class="form-control custom-input"
                            pattern="[A-Za-záéíóúñÑ\s,\.\-]+"
                            title="Ingrese la cobertura contratada"
                            placeholder="Ej. Básica, Amplia, Total"
                        >
                    </div>
                    <div class="col-12 seguro-field">
                        <label class="custom-form-label" for="numero_poliza">Número de póliza de seguros:</label>
                        <input 
                            type="text" 
                            id="numero_poliza"
                            name="numero_poliza"
                            class="form-control custom-input"
                            pattern="[A-Za-z0-9-]+"
                            title="Ingrese el número de póliza (letras, números y guiones)"
                            placeholder="Número de póliza"
                        >
                    </div>
                    <div class="col-md-6 seguro-field">
                        <label class="custom-form-label" for="medico_tratante">Nombre del médico tratante:</label>
                        <input 
                            type="text" 
                            id="medico_tratante"
                            name="medico_tratante"
                            class="form-control custom-input"
                            pattern="[A-Za-záéíóúñÑ\s.]+"
                            title="Ingrese el nombre completo del médico"
                            placeholder="Dr. Nombre Apellido"
                        >
                    </div>
                    <div class="col-md-6 seguro-field">
                        <label class="custom-form-label" for="telefono_medico">Teléfono de emergencia del médico:</label>
                        <input 
                            type="tel" 
                            id="telefono_medico"
                            name="telefono_medico"
                            class="form-control custom-input"
                            pattern="[0-9+\-\s()]{7,15}"
                            title="Ingrese un número de teléfono válido (ej. 0412-1234567)"
                            placeholder="0412-1234567"
                        >
                    </div>
                </div>
            </div>

            <!-- Información Académica -->
            <div class="card form-section-card">
                <h2 class="section-card-title">🏫 Información Académica:</h2>
                <div class="row g-3">
                    <div class="col-12">
                        <label class="custom-form-label" for="nombre_colegio">Nombre del colegio:</label>
                        <input 
                            type="text" 
                            id="nombre_colegio"
                            name="nombre_colegio"
                            class="form-control custom-input" 
                            required
                            pattern="[A-Za-záéíóúñÑ\s,.'\-]+"
                            title="Ingrese el nombre del colegio (solo letras, espacios, puntos y comas)"
                            placeholder="Nombre completo del colegio"
                        >
                    </div>
                    <div class="col-md-8">
                        <label class="custom-form-label" for="direccion_colegio">Dirección del colegio:</label>
                        <input 
                            type="text" 
                            id="direccion_colegio"
                            name="direccion_colegio"
                            class="form-control custom-input"
                            pattern="[A-Za-záéíóúñÑ0-9\s,.°#\-]+"
                            title="Ingrese la dirección completa (letras, números, espacios, puntos, comas, #, ° y -)"
                            placeholder="Calle/Avenida, Número, Urbanización"
                        >
                    </div>
                    <div class="col-md-4">
                        <label class="custom-form-label" for="grado_cursante">Grado / Año cursante:</label>
                        <input 
                            type="text" 
                            id="grado_cursante"
                            name="grado_cursante"
                            class="form-control custom-input" 
                            placeholder="Ej. 4to Grado" 
                            required
                            pattern="[A-Za-záéíóúñÑ0-9\s°]+"
                            title="Ingrese el grado o año (ej. 4to Grado, 5° Año)"
                        >
                    </div>
                </div>
            </div>

            <!-- Actividades Extra / Hobbie -->
            <div class="card form-section-card">
                <h2 class="section-card-title">🎨 Actividades Extra / Hobbie:</h2>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="custom-form-label" for="nombre_hobbie">Nombre del hobbie secundario:</label>
                        <input 
                            type="text" 
                            id="nombre_hobbie"
                            name="nombre_hobbie"
                            class="form-control custom-input" 
                            placeholder="Música, pintura, ajedrez..."
                            pattern="[A-Za-záéíóúñÑ\s,\.\-]+"
                            title="Ingrese el nombre del hobbie (solo letras, espacios, comas, puntos y guiones)"
                        >
                    </div>
                    <div class="col-md-6">
                        <label class="custom-form-label" for="horario_hobbie">Horario del hobbie:</label>
                        <input 
                            type="text" 
                            id="horario_hobbie"
                            name="horario_hobbie"
                            class="form-control custom-input" 
                            placeholder="Ej. Martes y Jueves 4pm"
                            pattern="[A-Za-záéíóúñÑ0-9\s,.:apmAPM\-]+"
                            title="Ingrese el horario (ej. Martes y Jueves 4pm, Lunes a Viernes 9-11am)"
                        >
                    </div>
                </div>
            </div>

        </div> <!-- FIN COLUMNA DERECHA -->

    </div> <!-- FIN ROW -->

    <div class="text-center my-4">
        <button type="submit" class="btn btn-submit-save shadow">Guardar Registro Completo</button>
    </div>
</form>

<div class="form-rest"></div>
</div>

<script src="./Public/Js/Registro_atletas.js"></script>