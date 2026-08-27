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
require_once $root . '/Model/Entities/Configuracion.php';


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
require_once $root . '/Model/Repository/RepositoryCuotaMensual.php';
require_once $root . '/Model/Repository/RepositoryConfiguracion.php';



// ============================================
// 1. VERIFICAR MÉTODO POST
// ============================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// ============================================
// 1b. MANEJAR FOTO DEL ATLETA
// ============================================
$fotoRuta = '';
if (!empty($_FILES['foto_atleta']['name'])) {
    $fotoTmp = $_FILES['foto_atleta']['tmp_name'];
    $fotoError = $_FILES['foto_atleta']['error'];
    $fotoSize = $_FILES['foto_atleta']['size'];
    $fotoType = $_FILES['foto_atleta']['type'];

    if ($fotoError === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['foto_atleta']['name'], PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($ext, $allowedExts)) {
            echo '<div class="alert alert-danger py-2 small" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> Solo se permiten imágenes JPG, PNG, GIF o WebP.</div>';
            exit;
        }
        if ($fotoSize > $maxSize) {
            echo '<div class="alert alert-danger py-2 small" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> La imagen no puede superar 5MB.</div>';
            exit;
        }

        $ext = strtolower(pathinfo($_FILES['foto_atleta']['name'], PATHINFO_EXTENSION));
        $fotoNombre = 'atleta_' . uniqid() . '.' . $ext;
        $uploadDir = dirname(__DIR__) . '/Public/uploads/fotos_atletas/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!move_uploaded_file($fotoTmp, $uploadDir . $fotoNombre)) {
            echo '<div class="alert alert-danger py-2 small" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> No se pudo guardar la imagen.</div>';
            exit;
        }
        $fotoRuta = 'fotos_atletas/' . $fotoNombre;
    }
}

// ============================================
// 1c. MANEJAR DOCUMENTOS (CÉDULA Y PARTIDA)
// ============================================
$cedulaDocRuta = '';
error_log("REGISTRAR ATLETA - FILES: " . print_r($_FILES, true));
if (!empty($_FILES['documento_cedula']['name'])) {
    $docTmp = $_FILES['documento_cedula']['tmp_name'];
    $docError = $_FILES['documento_cedula']['error'];
    $docSize = $_FILES['documento_cedula']['size'];
    $docType = $_FILES['documento_cedula']['type'];

    if ($docError === UPLOAD_ERR_OK) {
        $docExt = strtolower(pathinfo($_FILES['documento_cedula']['name'], PATHINFO_EXTENSION));
        if ($docExt !== 'pdf') {
            echo '<div class="alert alert-danger py-2 small" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> Solo se permiten archivos PDF para la cédula.</div>';
            exit;
        }
        if ($docSize > 5 * 1024 * 1024) {
            echo '<div class="alert alert-danger py-2 small" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> El archivo de cédula no puede superar 5MB.</div>';
            exit;
        }
        $cedulaNombre = 'cedula_' . uniqid() . '.pdf';
        $uploadDocDir = dirname(__DIR__) . '/Public/uploads/documentos/';
        if (!is_dir($uploadDocDir)) { mkdir($uploadDocDir, 0755, true); }
        if (!move_uploaded_file($docTmp, $uploadDocDir . $cedulaNombre)) {
            echo '<div class="alert alert-danger py-2 small" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> No se pudo guardar el documento de cédula.</div>';
            exit;
        }
        $cedulaDocRuta = 'documentos/' . $cedulaNombre;
    }
}

$partidaDocRuta = '';
if (!empty($_FILES['documento_partida']['name'])) {
    $docTmp = $_FILES['documento_partida']['tmp_name'];
    $docError = $_FILES['documento_partida']['error'];
    $docSize = $_FILES['documento_partida']['size'];
    $docType = $_FILES['documento_partida']['type'];

    if ($docError === UPLOAD_ERR_OK) {
        $docExt = strtolower(pathinfo($_FILES['documento_partida']['name'], PATHINFO_EXTENSION));
        if ($docExt !== 'pdf') {
            echo '<div class="alert alert-danger py-2 small" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> Solo se permiten archivos PDF para la partida de nacimiento.</div>';
            exit;
        }
        if ($docSize > 5 * 1024 * 1024) {
            echo '<div class="alert alert-danger py-2 small" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> El archivo de partida de nacimiento no puede superar 5MB.</div>';
            exit;
        }
        $partidaNombre = 'partida_' . uniqid() . '.pdf';
        $uploadDocDir = dirname(__DIR__) . '/Public/uploads/documentos/';
        if (!is_dir($uploadDocDir)) { mkdir($uploadDocDir, 0755, true); }
        if (!move_uploaded_file($docTmp, $uploadDocDir . $partidaNombre)) {
            echo '<div class="alert alert-danger py-2 small" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i> <strong>Error:</strong> No se pudo guardar el documento de partida de nacimiento.</div>';
            exit;
        }
        $partidaDocRuta = 'documentos/' . $partidaNombre;
    }
}

// ============================================
// 2. RECOGER Y SANITIZAR DATOS
// ============================================

$datos = [
    // Representantes (arrays)
    'cedula_representante' => array_map('limpiar_cadena', $_POST['cedula_representante'] ?? []),
    'correo_representante' => array_map('limpiar_cadena', $_POST['correo_representante'] ?? []),
    'nombres_representante' => array_map('limpiar_cadena', $_POST['nombres_representante'] ?? []),
    'apellidos_representante' => array_map('limpiar_cadena', $_POST['apellidos_representante'] ?? []),
    'ocupacion_representante' => array_map('limpiar_cadena', $_POST['ocupacion_representante'] ?? []),
    'telefono_representante' => array_map('limpiar_cadena', $_POST['telefono_representante'] ?? []),
    'parentesco_representante' => array_map('limpiar_cadena', $_POST['parentesco'] ?? []),

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

// Representantes (al menos uno con datos completos)
if (empty($datos['cedula_representante']) || empty($datos['cedula_representante'][0])) {
    $errores[] = "Al menos un representante con cédula es obligatorio.";
} else {
    $telefonoRegexRep = '/^[\d\s\-\+\(\)]{7,20}$/';
    $correoRegexRep = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
    $nombreRegexRep = '/^[A-Za-záéíóúñÑ\s]+$/';
    $cedulaRegexRep = '/^[VEJvej]{1}-?\d{5,10}$/';
    $cedulasVistas = [];

    for ($i = 0; $i < count($datos['cedula_representante']); $i++) {
        $ced = $datos['cedula_representante'][$i];
        $cor = $datos['correo_representante'][$i] ?? '';
        $nom = $datos['nombres_representante'][$i] ?? '';
        $ape = $datos['apellidos_representante'][$i] ?? '';
        $tel = $datos['telefono_representante'][$i] ?? '';
        $tieneDatos = !empty($ced) || !empty($cor) || !empty($nom) || !empty($ape) || !empty($tel);
        if (!$tieneDatos) continue;

        if (empty($ced)) { $errores[] = "La cédula del representante #" . ($i + 1) . " es obligatoria."; continue; }
        if (!verificar_datos($cedulaRegexRep, $ced)) { $errores[] = "La cédula del representante #" . ($i + 1) . " no tiene formato válido (ej. V-12345678)."; }
        if (in_array($ced, $cedulasVistas)) { $errores[] = "Hay representantes duplicados con la misma cédula: " . $ced . "."; }
        $cedulasVistas[] = $ced;
        if (empty($cor) || !verificar_datos($correoRegexRep, $cor)) { $errores[] = "El correo del representante #" . ($i + 1) . " no es válido."; }
        if (empty($nom) || !verificar_datos($nombreRegexRep, $nom)) { $errores[] = "Los nombres del representante #" . ($i + 1) . " no son válidos."; }
        if (empty($ape) || !verificar_datos($nombreRegexRep, $ape)) { $errores[] = "Los apellidos del representante #" . ($i + 1) . " no son válidos."; }
        if (empty($tel) || !verificar_datos($telefonoRegexRep, $tel)) { $errores[] = "El teléfono del representante #" . ($i + 1) . " no es válido (ej. +58 412-1234567)."; }
    }
}

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

// Cédula del atleta (opcional, formato V/E + dígitos)
if (!empty($datos['atleta_cedula']) && !verificar_datos("/^[VEJvej]{1}-?\d{5,10}$/", $datos['atleta_cedula'])) {
    $errores[] = "La cédula del atleta no tiene un formato válido (ej. V-12345678).";
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
    $fecha_max = new DateTime('2023-12-31');
    if (!$fecha_obj || $fecha_obj < $fecha_min || $fecha_obj > $fecha_max) {
        $errores[] = "La fecha de nacimiento debe estar entre el 01/01/2000 y el 31/12/2023.";
    }
}

// Cédula del atleta (ya validada arriba)

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

// Representantes: verificar si ya existen (no es error, se reutilizan)

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
        $fotoRuta,
        $cedulaDocRuta,
        $partidaDocRuta,
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

    // ---- 4d. Insertar Representantes (múltiples) ----
    $repoAtletaRepresentante = new RepositoryAtletaRepresentante();
    for ($i = 0; $i < count($datos['cedula_representante']); $i++) {
        $ced = $datos['cedula_representante'][$i];
        if (empty($ced)) continue;

        $repExistente = $repoRepresentante->findByCedula($ced);
        if (!$repExistente) {
            $representante = new Representante(
                $ced,
                $datos['nombres_representante'][$i] ?? '',
                $datos['apellidos_representante'][$i] ?? '',
                $datos['correo_representante'][$i] ?? '',
                $datos['ocupacion_representante'][$i] ?? '',
                $datos['telefono_representante'][$i] ?? ''
            );
            if (!$repoRepresentante->create($representante)) {
                throw new Exception("Error al guardar el representante #" . ($i + 1) . ".");
            }
        }

        // ---- 4e. Relación Atleta-Representante ----
        $parentesco = $datos['parentesco_representante'][$i] ?? 'Representante legal';
        $relacionAR = new AtletaRepresentante(
            null,
            $parentesco,
            date('Y-m-d'),
            $atletaId,
            $ced
        );
        if (!$repoAtletaRepresentante->create($relacionAR)) {
            throw new Exception("Error al asociar el atleta con el representante #" . ($i + 1) . ".");
        }
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

    // === CREAR CUOTAS MENSUALES PARA EL NUEVO ATLETA ===
    $repoConfig = new RepositoryConfiguracion();
    $repoCuota = new RepositoryCuotaMensual();

    $montoMensual = $repoConfig->getMontoMensualBs();
    if ($montoMensual <= 0) {
        $montoMensual = 50.00; // fallback
    }

    $anioActual = date('Y');
    $mesActual = (int)date('m');

    for ($mes = $mesActual; $mes <= 12; $mes++) {
        $cuota = new CuotaMensual(
            null,
            $atletaId,
            $mes,
            $anioActual,
            $montoMensual,
            'Pendiente'
        );
        $repoCuota->create($cuota);
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
            var seguroFields = document.querySelectorAll(".seguro-field input");
            seguroFields.forEach(function(field) {
                field.value = "";
                field.disabled = true;
            });
            var polizaSelect = document.getElementById("polizaSeguro");
            if (polizaSelect) { polizaSelect.value = ""; window.toggleSeguroFields(); }
            // Reset wizard to step 1
            var panels = document.querySelectorAll(".wizard-panel");
            var steps = document.querySelectorAll(".wizard-step");
            panels.forEach(function(p) { p.classList.remove("active"); });
            steps.forEach(function(s) {
                s.classList.remove("active", "completed");
                var sn = parseInt(s.dataset.step);
                if (sn === 1) s.classList.add("active");
            });
            var firstPanel = document.querySelector("[data-panel=\"1\"]");
            if (firstPanel) firstPanel.classList.add("active");
            var btnPrev = document.getElementById("btn-wizard-prev");
            var btnNext = document.getElementById("btn-wizard-next");
            var btnSubmit = document.getElementById("btn-wizard-submit");
            if (btnPrev) btnPrev.classList.add("d-none");
            if (btnNext) btnNext.classList.remove("d-none");
            if (btnSubmit) btnSubmit.classList.add("d-none");
            // Reset photo preview
            var photoPreview = document.getElementById("photoPreview");
            var photoPlaceholder = document.getElementById("photoPlaceholder");
            if (photoPreview) photoPreview.classList.add("d-none");
            if (photoPlaceholder) photoPlaceholder.classList.remove("d-none");
            // Remove field validation states
            document.querySelectorAll(".is-valid, .is-invalid").forEach(function(el) {
                el.classList.remove("is-valid", "is-invalid");
            });
            document.querySelectorAll(".field-feedback").forEach(function(el) {
                el.textContent = "";
                el.className = "field-feedback";
            });
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