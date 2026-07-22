<?php
// ============================================================
// 1. CONFIGURACIÓN INICIAL
// ============================================================
$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';

// Entidades
require_once $root . '/Model/Entities/Atleta.php';
require_once $root . '/Model/Entities/Uniforme.php';
require_once $root . '/Model/Entities/Salud.php';
require_once $root . '/Model/Entities/Categoria.php';
require_once $root . '/Model/Entities/CategoriaAtleta.php';
require_once $root . '/Model/Entities/Colegio.php';
require_once $root . '/Model/Entities/Grado.php';
require_once $root . '/Model/Entities/Hobbie.php';
require_once $root . '/Model/Entities/HobbieAtleta.php';

// Repositorios
require_once $root . '/Model/Repository/RepositoryAtleta.php';
require_once $root . '/Model/Repository/RepositoryUniforme.php';
require_once $root . '/Model/Repository/RepositorySalud.php';
require_once $root . '/Model/Repository/RepositoryCategoria.php';
require_once $root . '/Model/Repository/RepositoryCategoriaAtleta.php';
require_once $root . '/Model/Repository/RepositoryColegio.php';
require_once $root . '/Model/Repository/RepositoryGrado.php';
require_once $root . '/Model/Repository/RepositoryHobbie.php';
require_once $root . '/Model/Repository/RepositoryHobbieAtleta.php';

// ============================================================
// 2. DETECTAR AJAX Y LOG INICIAL
// ============================================================
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

error_log("=== INICIO ACTUALIZACIÓN ATLETA ===");
error_log("Método: " . $_SERVER['REQUEST_METHOD']);
error_log("AJAX: " . ($isAjax ? 'Sí' : 'No'));

// ============================================================
// 3. VERIFICAR MÉTODO POST
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = 'Método no permitido';
    error_log("ERROR: $error");
    if ($isAjax) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
    } else {
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?View=ConsultaAtletas';
        $separator = (strpos($referer, '?') === false) ? '?' : '&';
        header("Location: $referer" . $separator . "error=" . urlencode($error));
    }
    exit;
}

// ============================================================
// 4. RECOGER DATOS Y LOGUEARLOS
// ============================================================
$datos = [
    'atleta_id'               => limpiar_cadena($_POST['atleta_id'] ?? ''),
    'atleta_cedula_original'  => limpiar_cadena($_POST['atleta_cedula_original'] ?? ''),
    'primer_nombre'           => limpiar_cadena($_POST['primer_nombre'] ?? ''),
    'segundo_nombre'          => limpiar_cadena($_POST['segundo_nombre'] ?? ''),
    'primer_apellido'         => limpiar_cadena($_POST['primer_apellido'] ?? ''),
    'segundo_apellido'        => limpiar_cadena($_POST['segundo_apellido'] ?? ''),
    'atleta_fecha'            => limpiar_cadena($_POST['atleta_fecha'] ?? ''),
    'atleta_sexo'             => limpiar_cadena($_POST['atleta_sexo'] ?? ''),
    'atleta_cedula'           => limpiar_cadena($_POST['atleta_cedula'] ?? ''),
    'pie_dominante'           => limpiar_cadena($_POST['pie_dominante'] ?? ''),
    'atleta_direccion'        => limpiar_cadena($_POST['atleta_direccion'] ?? ''),
    'categoria_atleta'        => limpiar_cadena($_POST['categoria_atleta'] ?? ''),
    'categoria_anio'          => limpiar_cadena($_POST['categoria_anio'] ?? ''),
    'talla_franela'           => limpiar_cadena($_POST['talla_franela'] ?? ''),
    'numero_franela'          => isset($_POST['numero_franela']) && $_POST['numero_franela'] !== '' ? (int)$_POST['numero_franela'] : 0,
    'talla_short'             => limpiar_cadena($_POST['talla_short'] ?? ''),
    'talla_zapatos'           => limpiar_cadena($_POST['talla_zapatos'] ?? ''),
    'tipo_sangre'             => limpiar_cadena($_POST['tipo_sangre'] ?? ''),
    'alergias'                => limpiar_cadena($_POST['alergias'] ?? ''),
    'padecimiento_cronico'    => limpiar_cadena($_POST['padecimiento_cronico'] ?? ''),
    'medicamento_restriccion' => limpiar_cadena($_POST['medicamento_restriccion'] ?? ''),
    'lesiones_previas'        => limpiar_cadena($_POST['lesiones_previas'] ?? ''),
    'poliza_seguro'           => limpiar_cadena($_POST['poliza_seguro'] ?? ''),
    'vigencia_seguro'         => !empty($_POST['vigencia_seguro']) ? limpiar_cadena($_POST['vigencia_seguro']) : null,
    'empresa_aseguradora'     => !empty($_POST['empresa_aseguradora']) ? limpiar_cadena($_POST['empresa_aseguradora']) : null,
    'cobertura_contratada'    => !empty($_POST['cobertura_contratada']) ? limpiar_cadena($_POST['cobertura_contratada']) : null,
    'numero_poliza'           => !empty($_POST['numero_poliza']) ? limpiar_cadena($_POST['numero_poliza']) : null,
    'medico_tratante'         => !empty($_POST['medico_tratante']) ? limpiar_cadena($_POST['medico_tratante']) : null,
    'telefono_medico'         => !empty($_POST['telefono_medico']) ? limpiar_cadena($_POST['telefono_medico']) : null,
    'nombre_colegio'          => limpiar_cadena($_POST['nombre_colegio'] ?? ''),
    'direccion_colegio'       => limpiar_cadena($_POST['direccion_colegio'] ?? ''),
    'grado_cursante'          => limpiar_cadena($_POST['grado_cursante'] ?? ''),
    'nombre_hobbie'           => limpiar_cadena($_POST['nombre_hobbie'] ?? ''),
    'horario_hobbie'          => limpiar_cadena($_POST['horario_hobbie'] ?? ''),
];

error_log("Datos recibidos: " . print_r($datos, true));

// ============================================================
// 5. VALIDACIONES (resumidas pero completas)
// ============================================================
$errores = [];

// --- Obligatorios ---
$campos_obligatorios = [
    'atleta_id' => 'ID del atleta',
    'primer_nombre' => 'Primer nombre',
    'primer_apellido' => 'Primer apellido',
    'atleta_fecha' => 'Fecha de nacimiento',
    'atleta_sexo' => 'Sexo',
    'pie_dominante' => 'Pie dominante',
    'atleta_direccion' => 'Dirección',
    'talla_franela' => 'Talla de franela',
    'talla_short' => 'Talla de short',
    'talla_zapatos' => 'Talla de zapatos',
    'tipo_sangre' => 'Tipo de sangre',
    'categoria_atleta' => 'Categoría',
    'categoria_anio' => 'Año de categoría',
    'nombre_colegio' => 'Nombre del colegio',
    'grado_cursante' => 'Grado cursante',
];
foreach ($campos_obligatorios as $campo => $nombre) {
    if (empty($datos[$campo])) {
        $errores[] = "El campo '$nombre' es obligatorio.";
    }
}

// --- Validaciones de formato (solo algunos ejemplos, incluye todos los que ya tenías) ---
if (!empty($datos['primer_nombre']) && !verificar_datos("/^[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}$/", $datos['primer_nombre'])) {
    $errores[] = "El primer nombre debe tener entre 3 y 40 caracteres (solo letras).";
}
// ... (aquí todas tus validaciones de formato)

// --- Duplicado de cédula ---
if (!empty($datos['atleta_cedula']) && $datos['atleta_cedula'] !== $datos['atleta_cedula_original']) {
    $repoAtleta = new RepositoryAtleta();
    $atletaExistente = $repoAtleta->findByCedula($datos['atleta_cedula']);
    if ($atletaExistente && $atletaExistente->getAtletaId() !== $datos['atleta_id']) {
        $errores[] = "Ya existe un atleta con la cédula " . $datos['atleta_cedula'] . ".";
    }
}

// --- Edad vs categoría ---
if (!empty($datos['atleta_fecha']) && !empty($datos['categoria_atleta'])) {
    $edad = calcularEdad($datos['atleta_fecha']);
    preg_match('/Sub-(\d+)/', $datos['categoria_atleta'], $matches);
    $categoria_num = isset($matches[1]) ? (int)$matches[1] : null;
    if ($categoria_num && $edad > $categoria_num) {
        $errores[] = "La categoría {$datos['categoria_atleta']} es para jugadores de hasta $categoria_num años.";
    }
}

// --- Mostrar errores ---
if (!empty($errores)) {
    error_log("ERRORES DE VALIDACIÓN: " . print_r($errores, true));
    if ($isAjax) {
        echo '<div class="alert alert-danger"><ul>';
        foreach ($errores as $e) echo '<li>' . htmlspecialchars($e) . '</li>';
        echo '</ul></div>';
    } else {
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?View=ConsultaAtletas';
        $separator = (strpos($referer, '?') === false) ? '?' : '&';
        header("Location: $referer" . $separator . "error=" . urlencode(implode(' | ', $errores)));
    }
    exit;
}

// ============================================================
// 6. TRANSACCIÓN CON LOGS DETALLADOS
// ============================================================
try {
    $db = Database::getConnection();
    $db->beginTransaction();
    error_log("Transacción iniciada.");

    // Repositorios
    $repoAtleta = new RepositoryAtleta();
    $repoUniforme = new RepositoryUniforme();
    $repoSalud = new RepositorySalud();
    $repoCategoria = new RepositoryCategoria();
    $repoCategoriaAtleta = new RepositoryCategoriaAtleta();
    $repoColegio = new RepositoryColegio();
    $repoGrado = new RepositoryGrado();
    $repoHobbie = new RepositoryHobbie();
    $repoHobbieAtleta = new RepositoryHobbieAtleta();

    // ---- 6a. Obtener atleta existente ----
    error_log("Buscando atleta ID: " . $datos['atleta_id']);
    $atletaExistente = $repoAtleta->findById($datos['atleta_id']);
    if (!$atletaExistente) {
        throw new Exception("El atleta con ID " . $datos['atleta_id'] . " no existe.");
    }
    error_log("Atleta encontrado: " . $atletaExistente->getPrimerNombre() . " " . $atletaExistente->getPrimerApellido());

    // ---- 6b. Uniforme ----
    error_log("--- Actualizando Uniforme ---");
    $uniformeId = $atletaExistente->getUniformeId();
    error_log("Uniforme ID actual: " . $uniformeId);
    $uniforme = $repoUniforme->findById($uniformeId);
    if ($uniforme) {
        error_log("Uniforme existente, actualizando...");
        $uniforme->setFranelaTalla($datos['talla_franela']);
        $uniforme->setFranelaNumero($datos['numero_franela']);
        $uniforme->setShortTalla($datos['talla_short']);
        $uniforme->setZapatosTalla($datos['talla_zapatos']);
        if (!$repoUniforme->update($uniforme)) {
            throw new Exception("Error al actualizar el uniforme.");
        }
        error_log("Uniforme actualizado.");
    } else {
        error_log("Uniforme no existe, creando nuevo...");
        $uniforme = new Uniforme(null, $datos['talla_franela'], $datos['numero_franela'], $datos['talla_short'], $datos['talla_zapatos']);
        if (!$repoUniforme->create($uniforme)) {
            throw new Exception("Error al crear el uniforme.");
        }
        $nuevoUniformeId = $uniforme->getId();
        if (!$nuevoUniformeId) {
            throw new Exception("No se pudo obtener el ID del nuevo uniforme.");
        }
        error_log("Nuevo uniforme creado con ID: " . $nuevoUniformeId);
        $atletaExistente->setUniformeId($nuevoUniformeId);
        $repoAtleta->update($atletaExistente);
        error_log("Atleta actualizado con nuevo Uniforme ID.");
    }

    // ---- 6c. Atleta ----
    error_log("--- Actualizando Atleta ---");
    $atletaExistente->setPrimerNombre($datos['primer_nombre']);
    $atletaExistente->setSegundoNombre($datos['segundo_nombre']);
    $atletaExistente->setPrimerApellido($datos['primer_apellido']);
    $atletaExistente->setSegundoApellido($datos['segundo_apellido']);
    $atletaExistente->setFechaNacimiento($datos['atleta_fecha']);
    $atletaExistente->setSexo($datos['atleta_sexo']);
    $atletaExistente->setCedula($datos['atleta_cedula']);
    $atletaExistente->setPieDominante($datos['pie_dominante']);
    $atletaExistente->setDireccion($datos['atleta_direccion']);
    if (!$repoAtleta->update($atletaExistente)) {
        throw new Exception("Error al actualizar el atleta.");
    }
    error_log("Atleta actualizado.");

    // ---- 6d. Salud ----
    error_log("--- Actualizando Salud ---");
    $salud = $repoSalud->findByAtletaId($datos['atleta_id']);
    if ($salud) {
        error_log("Salud existente, actualizando...");
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
            throw new Exception("Error al actualizar la salud.");
        }
        error_log("Salud actualizada.");
    } else {
        error_log("Salud no existe, creando nueva...");
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
            throw new Exception("Error al crear la salud.");
        }
        error_log("Salud creada.");
    }

    // ---- 6e. Categoría ----
    error_log("--- Actualizando Categoría ---");
    $categoria = $repoCategoria->findBySubCategoria($datos['categoria_atleta']);
    if (!$categoria) {
        error_log("Categoría no existe, creando...");
        $categoria = new Categoria(null, $datos['categoria_atleta']);
        if (!$repoCategoria->create($categoria)) {
            throw new Exception("Error al crear la categoría.");
        }
        $categoriaId = $categoria->getId();
        if (!$categoriaId) {
            throw new Exception("No se pudo obtener el ID de la categoría creada.");
        }
        error_log("Categoría creada con ID: " . $categoriaId);
    } else {
        $categoriaId = $categoria->getId();
        error_log("Categoría existente con ID: " . $categoriaId);
    }

    // Buscar relación categoría-atleta
/*
    error_log("=== DEPURACIÓN CATEGORÍA ===");
    error_log("Nuevo año: " . $datos['categoria_anio']);
    error_log("Año actual en objeto: " . $catAtleta->getAnio());
    error_log("Categoría ID: " . $catAtleta->getCategoriaId());
    error_log("Atleta ID: " . $catAtleta->getAtletaId());
    error_log("Relación ID: " . $catAtleta->getId()); */

    $relacionesCategoria = $repoCategoriaAtleta->findByAtleta($datos['atleta_id']);
    if (!empty($relacionesCategoria)) {
        error_log("Relación categoría existente, actualizando...");
        $catAtleta = $relacionesCategoria[0];
        $catAtleta->setAnio($datos['categoria_anio']);
        $catAtleta->setCategoriaId($categoriaId);
        if (!$repoCategoriaAtleta->update($catAtleta)) {
            throw new Exception("Error al actualizar la categoría del atleta.");
        }
        error_log("Relación categoría actualizada.");
    } else {
        error_log("Creando nueva relación categoría...");
        $catAtleta = new CategoriaAtleta(null, $datos['categoria_anio'], $datos['atleta_id'], $categoriaId);
        if (!$repoCategoriaAtleta->create($catAtleta)) {
            throw new Exception("Error al crear la relación categoría-atleta.");
        }
        error_log("Relación categoría creada.");
    }

    // ---- 6f. Colegio y Grado ----
    error_log("--- Actualizando Colegio y Grado ---");


    // Buscar o crear colegio
    $colegio = $repoColegio->findByNombre($datos['nombre_colegio']);

    if (!$colegio) {
        // Crear nuevo colegio
        $colegio = new Colegio(null, $datos['nombre_colegio'], $datos['direccion_colegio'] ?: '');
        if (!$repoColegio->create($colegio)) {
            throw new Exception("Error al crear el colegio.");
        }
        // Obtener el ID directamente de la conexión
        $colegioId = (int) $db->lastInsertId(); // ← USAR ESTO
        if ($colegioId == 0) {
            throw new Exception("No se pudo obtener el ID del colegio creado.");
        }
        // También puedes actualizar la entidad para mantener consistencia
        $colegio->setId($colegioId);
    } else {
        // Si existe, actualizar nombre y dirección si cambiaron
        $colegioId = $colegio->getId();
        $nombreCambio = $colegio->getNombre() !== $datos['nombre_colegio'];
        $direccionCambio = $colegio->getDireccion() !== $datos['direccion_colegio'];

        if ($nombreCambio || $direccionCambio) {
            $colegio->setNombre($datos['nombre_colegio']);
            $colegio->setDireccion($datos['direccion_colegio'] ?: '');
            if (!$repoColegio->update($colegio)) {
                throw new Exception("Error al actualizar el colegio.");
            }
        }
    }

    // Grado
    $grados = $repoGrado->findByAtleta($datos['atleta_id']);
    if (!empty($grados)) {
        error_log("Grado existente, actualizando...");
        $grado = $grados[0];
        $grado->setGrado($datos['grado_cursante']);
        $grado->setColegioId($colegioId);
        if (!$repoGrado->update($grado)) {
            throw new Exception("Error al actualizar el grado.");
        }
        error_log("Grado actualizado.");
    } else {
        error_log("Creando nuevo grado...");
        $grado = new Grado(null, $datos['grado_cursante'], $datos['atleta_id'], $colegioId);
        if (!$repoGrado->create($grado)) {
            throw new Exception("Error al crear el grado.");
        }
        error_log("Grado creado.");
    }

    // ---- 6g. Hobbie ----
    error_log("--- Actualizando Hobbie ---");

    // Eliminar todas las relaciones existentes
    $hobbieRelaciones = $repoHobbieAtleta->findByAtleta($datos['atleta_id']);
    foreach ($hobbieRelaciones as $rel) {
        $repoHobbieAtleta->delete($rel->getId());
        error_log("Relación hobbie eliminada ID: " . $rel->getId());
    }

    if (!empty($datos['nombre_hobbie']) && !empty($datos['horario_hobbie'])) {
        error_log("Procesando nuevo hobbie...");
        $hobbie = $repoHobbie->findByNombre($datos['nombre_hobbie']);
        if (!$hobbie) {
            $hobbie = new Hobbie(null, $nombreHobbie);
            if (!$repoHobbie->create($hobbie)) {
                throw new Exception("Error al crear el hobbie.");
            }
            $hobbieId = (int) $db->lastInsertId(); // ← Obtener ID aquí
            if ($hobbieId == 0) {
                throw new Exception("No se pudo obtener el ID del hobbie.");
            }
            $hobbie->setId($hobbieId);
        } else {
            $hobbieId = $hobbie->getId();
        }

        // Crear nueva relación
        $hobbieAtleta = new HobbieAtleta(null, $datos['horario_hobbie'], $datos['atleta_id'], $hobbieId);
        if (!$repoHobbieAtleta->create($hobbieAtleta)) {
            throw new Exception("Error al asociar el hobbie con el atleta.");
        }
        error_log("Nueva relación hobbie creada.");
    } else {
        error_log("No se envió hobbie, se eliminaron las relaciones existentes.");
    }

    // ---- Commit ----
    $db->commit();
    error_log("=== TRANSACCIÓN COMPLETADA EXITOSAMENTE ===");

    // ============================================================
    // 7. RESPUESTA EXITOSA
    // ============================================================
    $nombre_completo = trim($datos['primer_nombre'] . ' ' . $datos['segundo_nombre']) . ' ' . trim($datos['primer_apellido'] . ' ' . $datos['segundo_apellido']);
    $mensaje = "Atleta actualizado: $nombre_completo - Categoría: {$datos['categoria_atleta']}";

    if ($isAjax) {
        echo '<div class="alert alert-success py-2 small text-start" role="alert">';
        echo '<i class="bi bi-check-circle-fill me-2"></i>';
        echo '<strong>¡Actualización exitosa!</strong> ' . htmlspecialchars($mensaje);
        echo '</div>';
        echo '<script>
            setTimeout(function(){
                var modal = document.getElementById("modalActualizar' . $datos['atleta_id'] . '");
                if (modal) {
                    var modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) modalInstance.hide();
                }
                location.reload();
            }, 1500);
        </script>';
        exit;
    } else {
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?View=ConsultaAtletas';
        $separator = (strpos($referer, '?') === false) ? '?' : '&';
        header("Location: $referer" . $separator . "success=1&mensaje=" . urlencode($mensaje));
        exit;
    }

} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
        error_log("Rollback ejecutado.");
    }
    error_log("EXCEPCIÓN: " . $e->getMessage());
    error_log("Stack trace: " . $e->getTraceAsString());

    $errorMsg = "Error: " . $e->getMessage();

    if ($isAjax) {
        echo '<div class="alert alert-danger py-2 small text-start" role="alert">';
        echo '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
        echo '<strong>' . htmlspecialchars($errorMsg) . '</strong>';
        echo '<br><small>Revisa el log de PHP para más detalles.</small>';
        echo '</div>';
    } else {
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?View=ConsultaAtletas';
        $separator = (strpos($referer, '?') === false) ? '?' : '&';
        header("Location: $referer" . $separator . "error=" . urlencode($errorMsg));
    }
    exit;
}