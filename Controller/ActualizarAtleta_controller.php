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


$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

function calcularEdad($fecha_nac) {
    $hoy = new DateTime();
    $fn = new DateTime($fecha_nac);
    return $hoy->diff($fn)->y;
}

// ============================================
// 2. VERIFICAR MÉTODO POST
// ============================================

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// ============================================
// 3. RECOGER Y SANITIZAR DATOS
// ============================================

$datos = [
    // Atleta (campos del formulario)
    'atleta_id' => limpiar_cadena($_POST['atleta_id'] ?? ''),
    'atleta_cedula_original' => limpiar_cadena($_POST['atleta_cedula_original'] ?? ''),
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
    'numero_franela' => isset($_POST['numero_franela']) && $_POST['numero_franela'] !== '' ? (int)$_POST['numero_franela'] : 0,
    'talla_short' => limpiar_cadena($_POST['talla_short'] ?? ''),
    'talla_zapatos' => limpiar_cadena($_POST['talla_zapatos'] ?? ''),

    // Salud
    'tipo_sangre' => limpiar_cadena($_POST['tipo_sangre'] ?? ''),
    'alergias' => limpiar_cadena($_POST['alergias'] ?? ''),
    'padecimiento_cronico' => limpiar_cadena($_POST['padecimiento_cronico'] ?? ''),
    'medicamento_restriccion' => limpiar_cadena($_POST['medicamento_restriccion'] ?? ''),
    'lesiones_previas' => limpiar_cadena($_POST['lesiones_previas'] ?? ''),
    'poliza_seguro' => limpiar_cadena($_POST['poliza_seguro'] ?? ''),
    'vigencia_seguro' => !empty($_POST['vigencia_seguro']) ? limpiar_cadena($_POST['vigencia_seguro']) : null,
    'empresa_aseguradora' => !empty($_POST['empresa_aseguradora']) ? limpiar_cadena($_POST['empresa_aseguradora']) : null,
    'cobertura_contratada' => !empty($_POST['cobertura_contratada']) ? limpiar_cadena($_POST['cobertura_contratada']) : null,
    'numero_poliza' => !empty($_POST['numero_poliza']) ? limpiar_cadena($_POST['numero_poliza']) : null,
    'medico_tratante' => !empty($_POST['medico_tratante']) ? limpiar_cadena($_POST['medico_tratante']) : null,
    'telefono_medico' => !empty($_POST['telefono_medico']) ? limpiar_cadena($_POST['telefono_medico']) : null,

    // Académico
    'nombre_colegio' => limpiar_cadena($_POST['nombre_colegio'] ?? ''),
    'direccion_colegio' => limpiar_cadena($_POST['direccion_colegio'] ?? ''),
    'grado_cursante' => limpiar_cadena($_POST['grado_cursante'] ?? ''),

    // Hobbie
    'nombre_hobbie' => limpiar_cadena($_POST['nombre_hobbie'] ?? ''),
    'horario_hobbie' => limpiar_cadena($_POST['horario_hobbie'] ?? ''),
];

// ============================================
// 4. VALIDACIONES
// ============================================

$errores = [];

// Validar que el ID del atleta no esté vacío
if (empty($datos['atleta_id'])) {
    $errores[] = "ID del atleta no proporcionado.";
}

// --- Validar campos obligatorios (igual que en registro) ---

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

// --- Validar formatos (igual que en registro) ---

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

// Fecha de nacimiento
if (!empty($datos['atleta_fecha'])) {
    $fecha_obj = DateTime::createFromFormat('Y-m-d', $datos['atleta_fecha']);
    $fecha_min = new DateTime('2000-01-01');
    $fecha_max = new DateTime('2018-12-31');
    if (!$fecha_obj || $fecha_obj < $fecha_min || $fecha_obj > $fecha_max) {
        $errores[] = "La fecha de nacimiento debe estar entre el 01/01/2000 y el 31/12/2018.";
    }
}

// Cédula del atleta (si aplica)
if (!empty($datos['atleta_cedula']) && !verificar_datos("/^[0-9]{5,8}$/", $datos['atleta_cedula'])) {
    $errores[] = "La cédula del atleta debe tener entre 5 y 8 dígitos numéricos.";
}

// Dirección
if (!empty($datos['atleta_direccion']) && !verificar_datos("/^[A-Za-z0-9áéíóúÁÉÍÓÚñÑ ]{3,100}$/", $datos['atleta_direccion'])) {
    $errores[] = "La dirección debe tener entre 3 y 100 caracteres (letras, números y espacios).";
}

// Uniforme
if (!empty($datos['talla_franela']) && !verificar_datos("/^[A-Za-z0-9\s]+$/", $datos['talla_franela'])) {
    $errores[] = "La talla de franela no es válida (solo letras, números y espacios).";
}
if (!empty($datos['talla_short']) && !verificar_datos("/^[A-Za-z0-9\s]+$/", $datos['talla_short'])) {
    $errores[] = "La talla de short no es válida (solo letras, números y espacios).";
}
if (!empty($datos['talla_zapatos']) && !verificar_datos("/^[0-9]+(?:\.[0-9])?$/", $datos['talla_zapatos'])) {
    $errores[] = "La talla de zapatos no es válida (ej. 39, 40, 41.5).";
}
if (!empty($datos['numero_franela']) && !filter_var($datos['numero_franela'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1, 'max_range' => 99]])) {
    $errores[] = "El número de franela debe ser entre 1 y 99.";
}

// Tipo de sangre
if (!empty($datos['tipo_sangre']) && !verificar_datos("/^[A-Za-z0-9\+-]+$/", $datos['tipo_sangre'])) {
    $errores[] = "El tipo de sangre no es válido (ej. A+, B-, O+).";
}

// Póliza de seguros
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
    // Validar formatos
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
    // Validar vigencia
    if (!empty($datos['vigencia_seguro'])) {
        $vigencia = DateTime::createFromFormat('Y-m-d', $datos['vigencia_seguro']);
        $hoy = new DateTime();
        if (!$vigencia || $vigencia < $hoy) {
            $errores[] = "La fecha de vigencia del seguro debe ser posterior a la fecha actual.";
        }
    }
}

// Colegio y grado
if (!empty($datos['nombre_colegio']) && !verificar_datos("/^[A-Za-záéíóúñÑ\s,.']+$/", $datos['nombre_colegio'])) {
    $errores[] = "El nombre del colegio contiene caracteres no válidos.";
}
if (!empty($datos['direccion_colegio']) && !verificar_datos("/^[A-Za-záéíóúñÑ0-9\s,.°#-]+$/", $datos['direccion_colegio'])) {
    $errores[] = "La dirección del colegio contiene caracteres no válidos.";
}
if (!empty($datos['grado_cursante']) && !verificar_datos("/^[A-Za-záéíóúñÑ0-9\s°]+$/", $datos['grado_cursante'])) {
    $errores[] = "El grado/año no es válido (ej. 4to Grado, 5° Año).";
}

// Hobbie (si se envía uno, el otro también)
if (!empty($datos['nombre_hobbie']) && empty($datos['horario_hobbie'])) {
    $errores[] = "Si ingresa un hobbie, también debe indicar el horario.";
}
if (empty($datos['nombre_hobbie']) && !empty($datos['horario_hobbie'])) {
    $errores[] = "Si ingresa un horario, también debe indicar el nombre del hobbie.";
}

// Regla de negocio: edad vs categoría
if (!empty($datos['atleta_fecha']) && !empty($datos['categoria_atleta'])) {
    $edad = calcularEdad($datos['atleta_fecha']);
    preg_match('/Sub-(\d+)/', $datos['categoria_atleta'], $matches);
    $categoria_num = isset($matches[1]) ? (int)$matches[1] : null;
    if ($categoria_num && $edad > $categoria_num) {
        $errores[] = "La categoría {$datos['categoria_atleta']} es para jugadores de hasta $categoria_num años, pero el atleta tiene $edad años.";
    }
}

// Duplicado de cédula (si cambió)
if (!empty($datos['atleta_cedula']) && $datos['atleta_cedula'] !== $datos['atleta_cedula_original']) {
    $repoAtleta = new RepositoryAtleta();
    $atletaExistente = $repoAtleta->findByCedula($datos['atleta_cedula']);
    if ($atletaExistente && $atletaExistente->getAtletaId() !== $datos['atleta_id']) {
        $errores[] = "Ya existe un atleta registrado con la cédula " . $datos['atleta_cedula'] . ".";
    }
}

// ============================================
// 5. SI HAY ERRORES, MOSTRAR
// ============================================

if (!empty($errores)) {
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
// 6. INICIO DE TRANSACCIÓN Y ACTUALIZACIÓN
// ============================================

try {
    $db = Database::getConnection();
    $db->beginTransaction();

    // Instanciar repositorios
    $repoAtleta = new RepositoryAtleta();
    $repoUniforme = new RepositoryUniforme();
    $repoSalud = new RepositorySalud();
    $repoCategoria = new RepositoryCategoria();
    $repoCategoriaAtleta = new RepositoryCategoriaAtleta();
    $repoColegio = new RepositoryColegio();
    $repoGrado = new RepositoryGrado();
    $repoHobbie = new RepositoryHobbie();
    $repoHobbieAtleta = new RepositoryHobbieAtleta();

    // ---- 6a. Obtener el atleta existente ----
    $atletaExistente = $repoAtleta->findById($datos['atleta_id']);
    if (!$atletaExistente) {
        throw new Exception("El atleta con ID " . $datos['atleta_id'] . " no existe.");
    }

    // ---- 6b. Actualizar Uniforme ----
    $uniforme = $repoUniforme->findById($atletaExistente->getUniformeId());
    if ($uniforme) {
        $uniforme->setFranelaTalla($datos['talla_franela']);
        $uniforme->setFranelaNumero($datos['numero_franela']);
        $uniforme->setShortTalla($datos['talla_short']);
        $uniforme->setZapatosTalla($datos['talla_zapatos']);
        if (!$repoUniforme->update($uniforme)) {
            throw new Exception("Error al actualizar el uniforme.");
        }
    } else {
        // Si no existe uniforme, crearlo (caso excepcional)
        $uniforme = new Uniforme(null, $datos['talla_franela'], $datos['numero_franela'], $datos['talla_short'], $datos['talla_zapatos']);
        if (!$repoUniforme->create($uniforme)) {
            throw new Exception("Error al crear el uniforme.");
        }
        $uniformeId = $uniforme->getId();
        // Actualizar el atleta con el nuevo Uniforme_id
        $atletaExistente->setUniformeId($uniformeId);
        $repoAtleta->update($atletaExistente);
    }

    // ---- 6c. Actualizar Atleta ----
    $atletaExistente->setPrimerNombre($datos['primer_nombre']);
    $atletaExistente->setSegundoNombre($datos['segundo_nombre']);
    $atletaExistente->setPrimerApellido($datos['primer_apellido']);
    $atletaExistente->setSegundoApellido($datos['segundo_apellido']);
    $atletaExistente->setFechaNacimiento($datos['atleta_fecha']);
    $atletaExistente->setSexo($datos['atleta_sexo']);
    $atletaExistente->setCedula($datos['atleta_cedula']);
    $atletaExistente->setPieDominante($datos['pie_dominante']);
    $atletaExistente->setDireccion($datos['atleta_direccion']);
    // El status no se modifica en este formulario
    if (!$repoAtleta->update($atletaExistente)) {
        throw new Exception("Error al actualizar el atleta.");
    }

    // ---- 6d. Actualizar Salud ----
    $salud = $repoSalud->findByAtletaId($datos['atleta_id']);
    if ($salud) {
        $salud->setTipoSangre($datos['tipo_sangre']);
        $salud->setAlergias($datos['alergias'] ?: '');
        $salud->setPadecimiento($datos['padecimiento_cronico'] ?: '');
        $salud->setMedicamentoNoTomar($datos['medicamento_restriccion'] ?: '');
        $salud->setLesionesPrevias($datos['lesiones_previas'] ?: '');
        $salud->setPolizaSeguro($datos['poliza_seguro'] ?: null);
        $salud->setVigencia($datos['vigencia_seguro']);
        $salud->setEmpresa($datos['empresa_aseguradora']);
        $salud->setCobertura($datos['cobertura_contratada']);
        $salud->setNroPoliza($datos['numero_poliza']);
        $salud->setNombreMedico($datos['medico_tratante']);
        $salud->setTelefonoMedico($datos['telefono_medico']);
        if (!$repoSalud->update($salud)) {
            throw new Exception("Error al actualizar la ficha de salud.");
        }
    } else {
        // Si no existe, crear (caso excepcional)
        $salud = new Salud(
            null,
            $datos['tipo_sangre'],
            $datos['alergias'] ?: '',
            $datos['padecimiento_cronico'] ?: '',
            $datos['medicamento_restriccion'] ?: '',
            $datos['lesiones_previas'] ?: '',
            $datos['poliza_seguro'] ?: null,
            $datos['vigencia_seguro'],
            $datos['empresa_aseguradora'],
            $datos['cobertura_contratada'],
            $datos['numero_poliza'],
            $datos['medico_tratante'],
            $datos['telefono_medico'],
            $datos['atleta_id']
        );
        if (!$repoSalud->create($salud)) {
            throw new Exception("Error al crear la ficha de salud.");
        }
    }

    // ---- 6e. Actualizar Categoría ----
    // Buscar o crear categoría
    $categoria = $repoCategoria->findBySubCategoria($datos['categoria_atleta']);
    if (!$categoria) {
        $categoria = new Categoria(null, $datos['categoria_atleta']);
        if (!$repoCategoria->create($categoria)) {
            throw new Exception("Error al crear la categoría.");
        }
        $categoriaId = $categoria->getId();
    } else {
        $categoriaId = $categoria->getId();
    }

    // Verificar si ya existe relación categoría-atleta
    $relacionesCategoria = $repoCategoriaAtleta->findByAtleta($datos['atleta_id']);
    if (!empty($relacionesCategoria)) {
        // Actualizar la primera relación (asumo que solo hay una activa)
        $catAtleta = $relacionesCategoria[0];
        $catAtleta->setAnio($datos['categoria_anio']);
        $catAtleta->setCategoriaId($categoriaId);
        if (!$repoCategoriaAtleta->update($catAtleta)) {
            throw new Exception("Error al actualizar la categoría del atleta.");
        }
    } else {
        // Crear nueva relación
        $catAtleta = new CategoriaAtleta(null, $datos['categoria_anio'], $datos['atleta_id'], $categoriaId);
        if (!$repoCategoriaAtleta->create($catAtleta)) {
            throw new Exception("Error al crear la relación categoría-atleta.");
        }
    }

    // ---- 6f. Actualizar Colegio y Grado ----
    // Buscar o crear colegio
    $colegio = $repoColegio->findByNombre($datos['nombre_colegio']);
    if (!$colegio) {
        $colegio = new Colegio(null, $datos['nombre_colegio'], $datos['direccion_colegio'] ?: '');
        if (!$repoColegio->create($colegio)) {
            throw new Exception("Error al crear el colegio.");
        }
        $colegioId = $colegio->getId();
    } else {
        $colegioId = $colegio->getId();
    }

    // Verificar si ya existe grado para el atleta
    $grados = $repoGrado->findByAtleta($datos['atleta_id']);
    if (!empty($grados)) {
        // Actualizar el primer grado (asumo que solo hay uno activo)
        $grado = $grados[0];
        $grado->setGrado($datos['grado_cursante']);
        $grado->setColegioId($colegioId);
        if (!$repoGrado->update($grado)) {
            throw new Exception("Error al actualizar el grado académico.");
        }
    } else {
        // Crear nuevo grado
        $grado = new Grado(null, $datos['grado_cursante'], $datos['atleta_id'], $colegioId);
        if (!$repoGrado->create($grado)) {
            throw new Exception("Error al crear el grado académico.");
        }
    }

    // ---- 6g. Actualizar Hobbie ----
    // Primero, eliminar relación existente (opcional: eliminar todas y crear la nueva)
    $hobbieRelaciones = $repoHobbieAtleta->findByAtleta($datos['atleta_id']);
    foreach ($hobbieRelaciones as $rel) {
        $repoHobbieAtleta->delete($rel->getId());
    }

    if (!empty($datos['nombre_hobbie']) && !empty($datos['horario_hobbie'])) {
        // Buscar o crear hobbie
        $hobbie = $repoHobbie->findByNombre($datos['nombre_hobbie']);
        if (!$hobbie) {
            $hobbie = new Hobbie(null, $datos['nombre_hobbie']);
            if (!$repoHobbie->create($hobbie)) {
                throw new Exception("Error al crear el hobbie.");
            }
            $hobbieId = $hobbie->getId();
        } else {
            $hobbieId = $hobbie->getId();
        }

        // Crear nueva relación
        $hobbieAtleta = new HobbieAtleta(null, $datos['horario_hobbie'], $datos['atleta_id'], $hobbieId);
        if (!$repoHobbieAtleta->create($hobbieAtleta)) {
            throw new Exception("Error al asociar el hobbie con el atleta.");
        }
    }

    // ---- 6h. Confirmar transacción ----
    $db->commit();

    // ============================================
    // 7. RESPUESTA DE ÉXITO
    // ============================================

    $nombre_completo = trim($datos['primer_nombre'] . ' ' . $datos['segundo_nombre']) . ' ' . trim($datos['primer_apellido'] . ' ' . $datos['segundo_apellido']);
    
    
    $mensaje = "Los datos del atleta han sido actualizados correctamente.";
    $mensaje .= " Atleta: $nombre_completo - Categoría: {$datos['categoria_atleta']}";
    
    // Obtener la URL de referencia (la página desde donde se envió el formulario)
    $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?View=ConsultaAtletas';
    
    // Si el referer ya contiene parámetros, los conservamos y agregamos success y mensaje
    $separator = (strpos($referer, '?') === false) ? '?' : '&';
    $url = $referer . $separator . 'success=1&mensaje=' . urlencode($mensaje);
    
    header("Location: $url");
    exit;


} catch (Exception $e) {
    // Rollback en caso de error
    if (isset($db)) {
        $db->rollBack();
    }
    // Respuesta de error genérico
    $html = '<div class="alert alert-danger py-2 small text-start" role="alert">';
    $html .= '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
    $html .= '<strong>Error al actualizar el atleta:</strong> ' . htmlspecialchars($e->getMessage());
    $html .= '</div>';
    echo $html;
    exit;
}