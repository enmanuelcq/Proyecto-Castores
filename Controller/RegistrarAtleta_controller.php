<?php
// Controller/RegistrarAtleta_controller.php

// Incluir todos los repositorios y entidades necesarias
$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Entities/Atleta.php';
require_once $root . '/Model/Entities/Uniforme.php';
require_once $root . '/Model/Entities/Salud.php';
require_once $root . '/Model/Entities/Representante.php';
require_once $root . '/Model/Entities/AtletaRepresentante.php';
require_once $root . '/Model/Entities/Categoria.php';
require_once $root . '/Model/Entities/CategoriaAtleta.php';
require_once $root . '/Model/Entities/Colegio.php';
require_once $root . '/Model/Entities/Grado.php';
require_once $root . '/Model/Entities/Hobbie.php';
require_once $root . '/Model/Entities/HobbieAtleta.php';

// Repositorios
require_once $root . '/Model/Repository/RepositoryUniforme.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';
require_once $root . '/Model/Repository/RepositorySalud.php';
require_once $root . '/Model/Repository/RepositoryRepresentante.php';
require_once $root . '/Model/Repository/RepositoryAtletaRepresentante.php';
require_once $root . '/Model/Repository/RepositoryCategoria.php';
require_once $root . '/Model/Repository/RepositoryCategoriaAtleta.php';
require_once $root . '/Model/Repository/RepositoryColegio.php';
require_once $root . '/Model/Repository/RepositoryGrado.php';
require_once $root . '/Model/Repository/RepositoryHobbie.php';
require_once $root . '/Model/Repository/RepositoryHobbieAtleta.php';


// Función para generar ID de atleta
function generarIdAtleta() {
    return 'atl_' . uniqid();
}

// Función para calcular edad a partir de fecha Y-m-d
function calcularEdad($fecha_nac) {
    $hoy = new DateTime();
    $fn = new DateTime($fecha_nac);
    return $hoy->diff($fn)->y;
}

// ============================================
// 1. VERIFICAR MÉTODO POST
// ============================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// ============================================
// 2. RECOGER Y SANITIZAR DATOS
// ============================================

$datos = [
    // Representante
    'cedula_representante' => limpiar_cadena($_POST['cedula_representante'] ?? ''),
    'correo_representante' => limpiar_cadena($_POST['correo_representante'] ?? ''),
    'nombres_representante' => limpiar_cadena($_POST['nombres_representante'] ?? ''),
    'apellidos_representante' => limpiar_cadena($_POST['apellidos_representante'] ?? ''),
    'ocupacion_representante' => limpiar_cadena($_POST['ocupacion_representante'] ?? ''),
    'telefono_representante' => limpiar_cadena($_POST['telefono_representante'] ?? ''),

    // Atleta
    'primer_nombre' => limpiar_cadena($_POST['primer_nombre'] ?? ''),
    'segundo_nombre' => limpiar_cadena($_POST['segundo_nombre'] ?? ''),
    'primer_apellido' => limpiar_cadena($_POST['primer_apellido'] ?? ''),
    'segundo_apellido' => limpiar_cadena($_POST['segundo_apellido'] ?? ''),
    'atleta_fecha' => limpiar_cadena($_POST['atleta_fecha'] ?? ''),
    'atleta_sexo' => limpiar_cadena($_POST['atleta_sexo'] ?? ''),
    'atleta_cedula' => limpiar_cadena($_POST['atleta_cedula'] ?? ''),
    'pie_dominante' => limpiar_cadena($_POST['pie_dominante'] ?? ''),
    'atleta_direccion' => limpiar_cadena($_POST['atleta_direccion'] ?? ''),

    // Categoría
    'categoria_atleta' => limpiar_cadena($_POST['categoria_atleta'] ?? ''),
    'categoria_anio' => limpiar_cadena($_POST['categoria_anio'] ?? ''),

    // Uniforme
    'talla_franela' => limpiar_cadena($_POST['talla_franela'] ?? ''),
    'numero_franela' => limpiar_cadena($_POST['numero_franela'] ?? ''),
    'talla_short' => limpiar_cadena($_POST['talla_short'] ?? ''),
    'talla_zapatos' => limpiar_cadena($_POST['talla_zapatos'] ?? ''),

    // Salud
    'tipo_sangre' => limpiar_cadena($_POST['tipo_sangre'] ?? ''),
    'alergias' => limpiar_cadena($_POST['alergias'] ?? ''),
    'padecimiento_cronico' => limpiar_cadena($_POST['padecimiento_cronico'] ?? ''),
    'medicamento_restriccion' => limpiar_cadena($_POST['medicamento_restriccion'] ?? ''),
    'lesiones_previas' => limpiar_cadena($_POST['lesiones_previas'] ?? ''),
    'poliza_seguro' => limpiar_cadena($_POST['poliza_seguro'] ?? ''),

    // Seguro (campos condicionales)
    'vigencia_seguro' => limpiar_cadena($_POST['vigencia_seguro'] ?? ''),
    'empresa_aseguradora' => limpiar_cadena($_POST['empresa_aseguradora'] ?? ''),
    'cobertura_contratada' => limpiar_cadena($_POST['cobertura_contratada'] ?? ''),
    'numero_poliza' => limpiar_cadena($_POST['numero_poliza'] ?? ''),
    'medico_tratante' => limpiar_cadena($_POST['medico_tratante'] ?? ''),
    'telefono_medico' => limpiar_cadena($_POST['telefono_medico'] ?? ''),

    // Académico
    'nombre_colegio' => limpiar_cadena($_POST['nombre_colegio'] ?? ''),
    'direccion_colegio' => limpiar_cadena($_POST['direccion_colegio'] ?? ''),
    'grado_cursante' => limpiar_cadena($_POST['grado_cursante'] ?? ''),

    // Hobbie (opcional)
    'nombre_hobbie' => limpiar_cadena($_POST['nombre_hobbie'] ?? ''),
    'horario_hobbie' => limpiar_cadena($_POST['horario_hobbie'] ?? ''),
];

// ============================================
// 3. VALIDACIONES (obligatorios, formatos, reglas de negocio)
// ============================================


$errores = [];

// --- 3.1 Validar campos obligatorios ---

// Representante
if (empty($datos['cedula_representante'])) $errores[] = "La cédula del representante es obligatoria.";
if (empty($datos['correo_representante'])) $errores[] = "El correo del representante es obligatorio.";
if (empty($datos['nombres_representante'])) $errores[] = "Los nombres del representante son obligatorios.";
if (empty($datos['apellidos_representante'])) $errores[] = "Los apellidos del representante son obligatorios.";
if (empty($datos['telefono_representante'])) $errores[] = "El teléfono del representante es obligatorio.";

// Atleta
if (empty($datos['primer_nombre'])) $errores[] = "El primer nombre del atleta es obligatorio.";
if (empty($datos['primer_apellido'])) $errores[] = "El primer apellido del atleta es obligatorio.";
if (empty($datos['atleta_fecha'])) $errores[] = "La fecha de nacimiento es obligatoria.";
if (empty($datos['atleta_sexo'])) $errores[] = "El sexo es obligatorio.";
if (empty($datos['pie_dominante'])) $errores[] = "El pie dominante es obligatorio.";
if (empty($datos['atleta_direccion'])) $errores[] = "La dirección es obligatoria.";

// Uniforme
if (empty($datos['talla_franela'])) $errores[] = "La talla de franela es obligatoria.";
if (empty($datos['talla_short'])) $errores[] = "La talla de short es obligatoria.";
if (empty($datos['talla_zapatos'])) $errores[] = "La talla de zapatos es obligatoria.";

// Salud
if (empty($datos['tipo_sangre'])) $errores[] = "El tipo de sangre es obligatorio.";

// Categoría
if (empty($datos['categoria_atleta'])) $errores[] = "La categoría es obligatoria.";
if (empty($datos['categoria_anio'])) $errores[] = "El año de categoría es obligatorio.";

// Colegio / Grado
if (empty($datos['nombre_colegio'])) $errores[] = "El nombre del colegio es obligatorio.";
if (empty($datos['grado_cursante'])) $errores[] = "El grado cursante es obligatorio.";

// --- 3.2 Validar formatos con regex ---

// Cédula representante (ej. V-12345678)
if (!empty($datos['cedula_representante']) && !verificar_datos("/^[VEJvej]{1}-?\d{5,10}$/", $datos['cedula_representante'])) {
    $errores[] = "La cédula del representante no tiene un formato válido (ej. V-12345678).";
}
// Correo
if (!empty($datos['correo_representante']) && !filter_var($datos['correo_representante'], FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El correo electrónico no es válido.";
}
// Nombres y apellidos representante (solo letras y espacios)
if (!empty($datos['nombres_representante']) && !verificar_datos("/^[A-Za-záéíóúñÑ\s]+$/", $datos['nombres_representante'])) {
    $errores[] = "Los nombres del representante solo deben contener letras y espacios.";
}
if (!empty($datos['apellidos_representante']) && !verificar_datos("/^[A-Za-záéíóúñÑ\s]+$/", $datos['apellidos_representante'])) {
    $errores[] = "Los apellidos del representante solo deben contener letras y espacios.";
}
// Teléfono
if (!empty($datos['telefono_representante']) && !verificar_datos("/^[0-9+\-\s()]{7,15}$/", $datos['telefono_representante'])) {
    $errores[] = "El teléfono no tiene un formato válido (ej. 0412-1234567).";
}

// Atleta (nombres y apellidos)
if (!empty($datos['primer_nombre']) && !verificar_datos("/^[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}$/", $datos['primer_nombre'])) {
    $errores[] = "El primer nombre del atleta debe tener entre 3 y 40 caracteres (solo letras).";
}
if (!empty($datos['segundo_nombre']) && !verificar_datos("/^[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}$/", $datos['segundo_nombre'])) {
    $errores[] = "El segundo nombre del atleta debe tener entre 3 y 40 caracteres (solo letras).";
}
if (!empty($datos['primer_apellido']) && !verificar_datos("/^[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}$/", $datos['primer_apellido'])) {
    $errores[] = "El primer apellido del atleta debe tener entre 3 y 40 caracteres (solo letras).";
}
if (!empty($datos['segundo_apellido']) && !verificar_datos("/^[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}$/", $datos['segundo_apellido'])) {
    $errores[] = "El segundo apellido del atleta debe tener entre 3 y 40 caracteres (solo letras).";
}

// Fecha de nacimiento (rango)
if (!empty($datos['atleta_fecha'])) {
    $fecha_obj = DateTime::createFromFormat('Y-m-d', $datos['atleta_fecha']);
    $fecha_min = new DateTime('2000-01-01');
    $fecha_max = new DateTime('2018-12-31');
    if (!$fecha_obj || $fecha_obj < $fecha_min || $fecha_obj > $fecha_max) {
        $errores[] = "La fecha de nacimiento debe estar entre el 01/01/2000 y el 31/12/2018.";
    }
}

// Cédula del atleta (opcional, pero si se envía, debe ser numérica de 5-8 dígitos)
if (!empty($datos['atleta_cedula']) && !verificar_datos("/^[0-9]{5,8}$/", $datos['atleta_cedula'])) {
    $errores[] = "La cédula del atleta debe tener entre 5 y 8 dígitos numéricos.";
}

// Dirección
if (!empty($datos['atleta_direccion']) && !verificar_datos("/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ*#,. ]{3,100}$/", $datos['atleta_direccion'])) {
    $errores[] = "La dirección debe tener entre 3 y 100 caracteres (letras, números y espacios).";
}

// Uniforme (tallas)
if (!empty($datos['talla_franela']) && !verificar_datos("/^[A-Za-z0-9\s]+$/", $datos['talla_franela'])) {
    $errores[] = "La talla de franela no es válida (solo letras, números y espacios).";
}
if (!empty($datos['talla_short']) && !verificar_datos("/^[A-Za-z0-9\s]+$/", $datos['talla_short'])) {
    $errores[] = "La talla de short no es válida (solo letras, números y espacios).";
}
if (!empty($datos['talla_zapatos']) && !verificar_datos("/^[0-9]+(?:\.[0-9])?$/", $datos['talla_zapatos'])) {
    $errores[] = "La talla de zapatos no es válida (ej. 39, 40, 41.5).";
}
// Número de franela (opcional, pero si se envía debe ser 1-99)
if (!empty($datos['numero_franela']) && !filter_var($datos['numero_franela'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 99]])) {
    $errores[] = "El número de franela debe ser entre 1 y 99.";
}

// Tipo de sangre
if (!empty($datos['tipo_sangre']) && !verificar_datos("/^[A-Za-z0-9\+-]+$/", $datos['tipo_sangre'])) {
    $errores[] = "El tipo de sangre no es válido (ej. A+, B-, O+).";
}

// --- 3.3 Validaciones de seguridad (póliza) ---

if ($datos['poliza_seguro'] === 'posee') {
    $campos_seguro = [
        'vigencia_seguro' => 'Vigencia del seguro',
        'empresa_aseguradora' => 'Empresa aseguradora',
        'cobertura_contratada' => 'Cobertura contratada',
        'numero_poliza' => 'Número de póliza'
    ];
    foreach ($campos_seguro as $campo => $nombre) {
        if (empty($datos[$campo])) {
            $errores[] = "El campo '$nombre' es obligatorio cuando posee póliza.";
        }
    }
    // Validar formatos de los campos del seguro
    if (!empty($datos['empresa_aseguradora']) && !verificar_datos("/^[A-Za-záéíóúñÑ\s,.-]+$/", $datos['empresa_aseguradora'])) {
        $errores[] = "La empresa aseguradora contiene caracteres no válidos.";
    }
    if (!empty($datos['cobertura_contratada']) && !verificar_datos("/^[A-Za-záéíóúñÑ\s,.-]+$/", $datos['cobertura_contratada'])) {
        $errores[] = "La cobertura contratada contiene caracteres no válidos.";
    }
    if (!empty($datos['numero_poliza']) && !verificar_datos("/^[A-Za-z0-9-]+$/", $datos['numero_poliza'])) {
        $errores[] = "El número de póliza solo admite letras, números y guiones.";
    }
    if (!empty($datos['telefono_medico']) && !verificar_datos("/^[0-9+\-\s()]{7,15}$/", $datos['telefono_medico'])) {
        $errores[] = "El teléfono del médico no tiene un formato válido.";
    }
    // Validar que la vigencia sea posterior a hoy
    if (!empty($datos['vigencia_seguro'])) {
        $vigencia = DateTime::createFromFormat('Y-m-d', $datos['vigencia_seguro']);
        $hoy = new DateTime();
        if (!$vigencia || $vigencia < $hoy) {
            $errores[] = "La fecha de vigencia del seguro debe ser posterior a la fecha actual.";
        }
    }
}

// --- 3.4 Validaciones de colegio y grado ---

if (!empty($datos['nombre_colegio']) && !verificar_datos("/^[A-Za-záéíóúñÑ\s,.']+$/", $datos['nombre_colegio'])) {
    $errores[] = "El nombre del colegio contiene caracteres no válidos.";
}
if (!empty($datos['direccion_colegio']) && !verificar_datos("/^[A-Za-záéíóúñÑ0-9\s,.°#-]+$/", $datos['direccion_colegio'])) {
    $errores[] = "La dirección del colegio contiene caracteres no válidos.";
}
if (!empty($datos['grado_cursante']) && !verificar_datos("/^[A-Za-záéíóúñÑ0-9\s°]+$/", $datos['grado_cursante'])) {
    $errores[] = "El grado/año no es válido (ej. 4to Grado, 5° Año).";
}

// --- 3.5 Validar hobbie (si se envía uno, el otro también) ---

if (!empty($datos['nombre_hobbie']) && empty($datos['horario_hobbie'])) {
    $errores[] = "Si ingresa un hobbie, también debe indicar el horario.";
}
if (empty($datos['nombre_hobbie']) && !empty($datos['horario_hobbie'])) {
    $errores[] = "Si ingresa un horario, también debe indicar el nombre del hobbie.";
}

// --- 3.6 Reglas de negocio: edad vs categoría ---

if (!empty($datos['atleta_fecha']) && !empty($datos['categoria_atleta'])) {
    $edad = calcularEdad($datos['atleta_fecha']);
    preg_match('/Sub-(\d+)/', $datos['categoria_atleta'], $matches);
    $categoria_num = isset($matches[1]) ? (int)$matches[1] : null;

    if ($categoria_num && $edad > $categoria_num) {
        $errores[] = "La categoría {$datos['categoria_atleta']} es para jugadores de hasta $categoria_num años, pero el atleta tiene $edad años.";
    }
    // Advertencia (no bloqueante) – puedes mostrarla pero no la agregamos a $errores para no bloquear.
    if ($categoria_num && $edad < $categoria_num - 1) {
        // Podríamos agregar una advertencia en un array aparte, pero no bloqueamos.
    }
}

$datos['nombre_hobbie'] = ucfirst(strtolower($datos['nombre_hobbie']));;
// --- 3.7 Duplicados (consultas a BD) ---

$repoRepresentante = new RepositoryRepresentante();
$repoAtleta = new RepositoryAtleta();

// Representante: verificar si ya existe
$representanteExistente = $repoRepresentante->findByCedula($datos['cedula_representante']);
if ($representanteExistente) {
    // Si existe, reutilizamos (no hay error)
    // Pero podríamos verificar si el correo cambió y actualizar
    if ($representanteExistente->getCorreo() !== $datos['correo_representante']) {
        // Actualizar correo (opcional)
        $representanteExistente->setCorreo($datos['correo_representante']);
        $repoRepresentante->update($representanteExistente);
    }
    // Similar con teléfono, etc.
} // si no existe, se creará más adelante

// Atleta: verificar duplicado de cédula (si aplica)
if (!empty($datos['atleta_cedula'])) {
    $atletaExistente = $repoAtleta->findByCedula($datos['atleta_cedula']);
    if ($atletaExistente) {
        $errores[] = "Ya existe un atleta registrado con la cédula " . $datos['atleta_cedula'] . ".";
    }
}

// --- 3.8 Si hay errores, mostrar respuesta ---

if (!empty($errores)) {
    // Construir HTML de error
    $html = '<div class="alert alert-danger py-2 small text-start" role="alert">';
    $html .= '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
    $html .= '<strong>Errores en el formulario:</strong><br>';
    $html .= '<ul class="mb-0 ps-3">';
    foreach ($errores as $error) {
        $html .= '<li>' . htmlspecialchars($error) . '</li>';
    }
    $html .= '</ul></div>';
    echo $html;
    exit;
}

// ============================================
// 4. INICIO DE TRANSACCIÓN E INSERCIÓN
// ============================================

try {
    $db = Database::getConnection();
    $db->beginTransaction();

    // ---- 4a. Insertar Uniforme ----
    $repoUniforme = new RepositoryUniforme();
    $uniforme = new Uniforme(
        null,
        $datos['talla_franela'],
        (int)$datos['numero_franela'] ?: 0,
        $datos['talla_short'],
        $datos['talla_zapatos']
    );
    if (!$repoUniforme->create($uniforme)) {
        throw new Exception("Error al guardar el uniforme.");
    }
    $uniformeId = $db->lastInsertId();
    if ($uniformeId == 0) {
        throw new Exception("No se pudo obtener el ID del uniforme.");
    }

    // ---- 4b. Insertar Atleta ----
    $atletaId = generarIdAtleta();
    $atleta = new Atleta(
        $atletaId,
        $datos['primer_nombre'],
        $datos['segundo_nombre'] ?: '',
        $datos['primer_apellido'],
        $datos['segundo_apellido'] ?: '',
        $datos['atleta_fecha'],
        $datos['atleta_sexo'],
        $datos['atleta_cedula'] ?: '',
        $datos['pie_dominante'],
        $datos['atleta_direccion'],
        '', // foto (vacía por ahora)
        true, // status activo
        $uniformeId
    );
    if (!$repoAtleta->create($atleta)) {
        throw new Exception("Error al guardar el atleta.");
    }

    // ---- 4c. Insertar Salud ----
    $repoSalud = new RepositorySalud();
    // Convertir cadenas vacías a null para campos que pueden ser nulos
    $vigencia = !empty($datos['vigencia_seguro']) ? $datos['vigencia_seguro'] : null;
    $empresa = !empty($datos['empresa_aseguradora']) ? $datos['empresa_aseguradora'] : null;
    $cobertura = !empty($datos['cobertura_contratada']) ? $datos['cobertura_contratada'] : null;
    $numPoliza = !empty($datos['numero_poliza']) ? $datos['numero_poliza'] : null;
    $medico = !empty($datos['medico_tratante']) ? $datos['medico_tratante'] : null;
    $telMedico = !empty($datos['telefono_medico']) ? $datos['telefono_medico'] : null;

    $salud = new Salud(
        null,
        $datos['tipo_sangre'],
        $datos['alergias'] ?: '',
        $datos['padecimiento_cronico'] ?: '',
        $datos['medicamento_restriccion'] ?: '',
        $datos['lesiones_previas'] ?: '',
        $datos['poliza_seguro'] ?: null,
        $vigencia,
        $empresa,
        $cobertura,
        $numPoliza,
        $medico,
        $telMedico,
        $atletaId
    );
    if (!$repoSalud->create($salud)) {
        throw new Exception("Error al guardar la ficha de salud.");
    }

    // ---- 4d. Insertar Representante (si no existe) ----
    if (!$representanteExistente) {
        $representante = new Representante(
            $datos['cedula_representante'],
            $datos['nombres_representante'],
            $datos['apellidos_representante'],
            $datos['correo_representante'],
            $datos['ocupacion_representante'] ?: '',
            $datos['telefono_representante']
        );
        if (!$repoRepresentante->create($representante)) {
            throw new Exception("Error al guardar el representante.");
        }
    }

    // ---- 4e. Relación Atleta-Representante ----
    $repoAtletaRepresentante = new RepositoryAtletaRepresentante();
    $parentesco = $_POST['parentesco'] ?? 'Representante legal';
    $relacionAR = new AtletaRepresentante(
        null,
        $parentesco,
        date('Y-m-d'),
        $atletaId,
        $datos['cedula_representante']
    );
    if (!$repoAtletaRepresentante->create($relacionAR)) {
        throw new Exception("Error al asociar el atleta con el representante.");
    }

    // ---- 4f. Categoría ----
    $repoCategoria = new RepositoryCategoria();
    $categoria = $repoCategoria->findBySubCategoria($datos['categoria_atleta']);
    if (!$categoria) {
        $categoria = new Categoria(0, $datos['categoria_atleta']);
        if (!$repoCategoria->create($categoria)) {
            throw new Exception("Error al guardar la categoría.");
        }
        $categoriaId = $db->lastInsertId();
        if ($categoriaId == 0) {
            throw new Exception("No se pudo obtener el ID de la categoría.");
        }
    } else {
        $categoriaId = $categoria->getId();
    }

    // ---- 4g. Relación Categoría-Atleta ----
    $repoCategoriaAtleta = new RepositoryCategoriaAtleta();
    $categoriaAtleta = new CategoriaAtleta(
        null,
        $datos['categoria_anio'],
        $atletaId,
        $categoriaId
    );
    if (!$repoCategoriaAtleta->create($categoriaAtleta)) {
        throw new Exception("Error al asociar el atleta con la categoría.");
    }

    // ---- 4h. Colegio ----
    $repoColegio = new RepositoryColegio();
    $colegio = $repoColegio->findByNombre($datos['nombre_colegio']);
    if (!$colegio) {
        $colegio = new Colegio(null, $datos['nombre_colegio'], $datos['direccion_colegio'] ?: '');
        if (!$repoColegio->create($colegio)) {
            throw new Exception("Error al guardar el colegio.");
        }
        $colegioId = $db->lastInsertId();
        if ($colegioId == 0) {
            throw new Exception("No se pudo obtener el ID del colegio.");
        }
    } else {
        $colegioId = $colegio->getId();
    }

    // ---- 4i. Grado ----
    $repoGrado = new RepositoryGrado();
    $grado = new Grado(
        null,
        $datos['grado_cursante'],
        $atletaId,
        $colegioId
    );
    if (!$repoGrado->create($grado)) {
        throw new Exception("Error al guardar el grado académico.");
    }

    // ---- 4j. Hobbie (opcional) ----
    if (!empty($datos['nombre_hobbie']) && !empty($datos['horario_hobbie'])) {
        $repoHobbie = new RepositoryHobbie();
        $hobbie = $repoHobbie->findByNombre($datos['nombre_hobbie']);
        if (!$hobbie) {
            $hobbie = new Hobbie(null, $datos['nombre_hobbie']);
            if (!$repoHobbie->create($hobbie)) {
                throw new Exception("Error al guardar el hobbie.");
            }
            $hobbieId = $db->lastInsertId();
            if ($hobbieId == 0) {
                throw new Exception("No se pudo obtener el ID del hobbie.");
            }
        } else {
            $hobbieId = $hobbie->getId();
        }

        $repoHobbieAtleta = new RepositoryHobbieAtleta();
        $hobbieAtleta = new HobbieAtleta(
            null,
            $datos['horario_hobbie'],
            $atletaId,
            $hobbieId
        );
        if (!$repoHobbieAtleta->create($hobbieAtleta)) {
            throw new Exception("Error al asociar el hobbie con el atleta.");
        }
    }

    // ---- 4k. Confirmar transacción ----
    $db->commit();

    // ============================================
    // 5. RESPUESTA DE ÉXITO
    // ============================================

    // Obtener datos para mostrar
    $nombre_completo = trim($datos['primer_nombre'] . ' ' . $datos['segundo_nombre']) . ' ' . trim($datos['primer_apellido'] . ' ' . $datos['segundo_apellido']);
    $categoria_nombre = $datos['categoria_atleta'];

    $html = '<div class="alert alert-success py-2 small text-start" role="alert">';
    $html .= '<i class="bi bi-check-circle-fill me-2"></i>';
    $html .= '<strong>¡Registro exitoso!</strong> El atleta ha sido registrado correctamente.<br>';
    $html .= '<small><strong>Atleta:</strong> ' . htmlspecialchars($nombre_completo) . ' - <strong>Categoría:</strong> ' . htmlspecialchars($categoria_nombre) . '</small>';
    $html .= '</div>';

    // Añadir script para resetear formulario después de 1.5 segundos
    $html .= '<script>
        setTimeout(function(){
            var form = document.getElementById("athleteRegisterForm");
            if (form) form.reset();
            // Resetear campos del seguro
            var seguroFields = document.querySelectorAll(".seguro-field input, .seguro-field select");
            seguroFields.forEach(function(field) {
                field.value = "";
                field.disabled = true;
            });
            // También resetear el select de póliza a su opción por defecto
            var polizaSelect = document.getElementById("polizaSeguro");
            if (polizaSelect) polizaSelect.value = "";
        }, 1500);
    </script>';

    echo $html;
    exit;

} catch (Exception $e) {
    // Rollback en caso de error
    if (isset($db)) {
        $db->rollBack();
    }
    // Respuesta de error genérico
    $html = '<div class="alert alert-danger py-2 small text-start" role="alert">';
    $html .= '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
    $html .= '<strong>Error al registrar el atleta:</strong> ' . htmlspecialchars($e->getMessage());
    $html .= '</div>';
    echo $html;
    exit;
}