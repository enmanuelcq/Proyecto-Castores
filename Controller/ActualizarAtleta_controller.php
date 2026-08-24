<?php
// ============================================================
// 1. CONFIGURACION INICIAL
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
// 2. DETECTAR AJAX
// ============================================================
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// ============================================================
// 3. VERIFICAR METODO POST
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = 'Metodo no permitido';
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
// 4. RECOGER DATOS
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

// ============================================================
// 4b. MANEJAR ARCHIVOS (FOTO Y DOCUMENTOS)
// ============================================================
$fotoRuta = null;
$cedulaDocRuta = null;
$partidaDocRuta = null;
$errores = [];

if (!empty($_FILES['foto_atleta']['name'])) {
    $fotoTmp = $_FILES['foto_atleta']['tmp_name'];
    $fotoError = $_FILES['foto_atleta']['error'];
    $fotoSize = $_FILES['foto_atleta']['size'];
    if ($fotoError === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['foto_atleta']['name'], PATHINFO_EXTENSION));
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array($ext, $allowedExts)) {
            $errores[] = "Solo se permiten imagenes JPG, PNG, GIF o WebP para la foto.";
        } elseif ($fotoSize > 5 * 1024 * 1024) {
            $errores[] = "La foto no puede superar 5MB.";
        } else {
            $fotoNombre = 'atleta_' . uniqid() . '.' . $ext;
            $uploadDir = dirname(__DIR__) . '/Public/uploads/fotos_atletas/';
            if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
            if (move_uploaded_file($fotoTmp, $uploadDir . $fotoNombre)) {
                $fotoRuta = 'fotos_atletas/' . $fotoNombre;
            }
        }
    }
}

if (!empty($_FILES['documento_cedula']['name'])) {
    $docTmp = $_FILES['documento_cedula']['tmp_name'];
    $docError = $_FILES['documento_cedula']['error'];
    $docSize = $_FILES['documento_cedula']['size'];
    if ($docError === UPLOAD_ERR_OK) {
        $docExt = strtolower(pathinfo($_FILES['documento_cedula']['name'], PATHINFO_EXTENSION));
        if ($docExt !== 'pdf') {
            $errores[] = "Solo se permiten archivos PDF para la cedula.";
        } elseif ($docSize > 5 * 1024 * 1024) {
            $errores[] = "El archivo de cedula no puede superar 5MB.";
        } else {
            $cedulaNombre = 'cedula_' . uniqid() . '.pdf';
            $uploadDocDir = dirname(__DIR__) . '/Public/uploads/documentos/';
            if (!is_dir($uploadDocDir)) { mkdir($uploadDocDir, 0755, true); }
            if (move_uploaded_file($docTmp, $uploadDocDir . $cedulaNombre)) {
                $cedulaDocRuta = 'documentos/' . $cedulaNombre;
            }
        }
    }
}

if (!empty($_FILES['documento_partida']['name'])) {
    $docTmp = $_FILES['documento_partida']['tmp_name'];
    $docError = $_FILES['documento_partida']['error'];
    $docSize = $_FILES['documento_partida']['size'];
    if ($docError === UPLOAD_ERR_OK) {
        $docExt = strtolower(pathinfo($_FILES['documento_partida']['name'], PATHINFO_EXTENSION));
        if ($docExt !== 'pdf') {
            $errores[] = "Solo se permiten archivos PDF para la partida de nacimiento.";
        } elseif ($docSize > 5 * 1024 * 1024) {
            $errores[] = "El archivo de partida de nacimiento no puede superar 5MB.";
        } else {
            $partidaNombre = 'partida_' . uniqid() . '.pdf';
            $uploadDocDir = dirname(__DIR__) . '/Public/uploads/documentos/';
            if (!is_dir($uploadDocDir)) { mkdir($uploadDocDir, 0755, true); }
            if (move_uploaded_file($docTmp, $uploadDocDir . $partidaNombre)) {
                $partidaDocRuta = 'documentos/' . $partidaNombre;
            }
        }
    }
}

// ============================================================
// 5. VALIDACIONES
// ============================================================

// --- Obligatorios ---
$campos_obligatorios = [
    'atleta_id' => 'ID del atleta',
    'primer_nombre' => 'Primer nombre',
    'primer_apellido' => 'Primer apellido',
    'atleta_fecha' => 'Fecha de nacimiento',
    'atleta_sexo' => 'Sexo',
    'pie_dominante' => 'Pie dominante',
    'atleta_direccion' => 'Direccion',
    'talla_franela' => 'Talla de franela',
    'talla_short' => 'Talla de short',
    'talla_zapatos' => 'Talla de zapatos',
    'tipo_sangre' => 'Tipo de sangre',
    'categoria_atleta' => 'Categoria',
    'categoria_anio' => 'Ano de categoria',
    'nombre_colegio' => 'Nombre del colegio',
    'grado_cursante' => 'Grado cursante',
];
foreach ($campos_obligatorios as $campo => $nombre) {
    if (empty($datos[$campo])) {
        $errores[] = "El campo '$nombre' es obligatorio.";
    }
}

// --- Validaciones de formato ---
if (!empty($datos['primer_nombre']) && !verificar_datos("/^[A-Za-záéíóúÁÉÍÓÚñÑ ]{3,40}$/", $datos['primer_nombre'])) {
    $errores[] = "El primer nombre debe tener entre 3 y 40 caracteres (solo letras).";
}

// --- Duplicado de cedula ---
if (!empty($datos['atleta_cedula']) && $datos['atleta_cedula'] !== $datos['atleta_cedula_original']) {
    $repoAtleta = new RepositoryAtleta();
    $atletaExistente = $repoAtleta->findByCedula($datos['atleta_cedula']);
    if ($atletaExistente && $atletaExistente->getAtletaId() !== $datos['atleta_id']) {
        $errores[] = "Ya existe un atleta con la cedula " . $datos['atleta_cedula'] . ".";
    }
}

// --- Edad vs categoria ---
if (!empty($datos['atleta_fecha']) && !empty($datos['categoria_atleta'])) {
    $edad = calcularEdad($datos['atleta_fecha']);
    preg_match('/Sub-(\d+)/', $datos['categoria_atleta'], $matches);
    $categoria_num = isset($matches[1]) ? (int)$matches[1] : null;
    if ($categoria_num && $edad > $categoria_num) {
        $errores[] = "La categoria {$datos['categoria_atleta']} es para jugadores de hasta $categoria_num anos.";
    }
}

// --- Mostrar errores ---
if (!empty($errores)) {
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
// 6. TRANSACCION
// ============================================================
try {
    $db = Database::getConnection();
    $db->beginTransaction();

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
    $atletaExistente = $repoAtleta->findById($datos['atleta_id']);
    if (!$atletaExistente) {
        throw new Exception("El atleta con ID " . $datos['atleta_id'] . " no existe.");
    }

    // ---- 6b. Uniforme ----
    $uniformeId = $atletaExistente->getUniformeId();
    $uniforme = $repoUniforme->findById($uniformeId);
    if ($uniforme) {
        $uniforme->setFranelaTalla($datos['talla_franela']);
        $uniforme->setFranelaNumero($datos['numero_franela']);
        $uniforme->setShortTalla($datos['talla_short']);
        $uniforme->setZapatosTalla($datos['talla_zapatos']);
        if (!$repoUniforme->update($uniforme)) {
            throw new Exception("Error al actualizar el uniforme.");
        }
    } else {
        $uniforme = new Uniforme(null, $datos['talla_franela'], $datos['numero_franela'], $datos['talla_short'], $datos['talla_zapatos']);
        if (!$repoUniforme->create($uniforme)) {
            throw new Exception("Error al crear el uniforme.");
        }
        $nuevoUniformeId = $uniforme->getId();
        if (!$nuevoUniformeId) {
            throw new Exception("No se pudo obtener el ID del nuevo uniforme.");
        }
        $atletaExistente->setUniformeId($nuevoUniformeId);
        $repoAtleta->update($atletaExistente);
    }

    // ---- 6c. Atleta ----
    $atletaExistente->setPrimerNombre($datos['primer_nombre']);
    $atletaExistente->setSegundoNombre($datos['segundo_nombre']);
    $atletaExistente->setPrimerApellido($datos['primer_apellido']);
    $atletaExistente->setSegundoApellido($datos['segundo_apellido']);
    $atletaExistente->setFechaNacimiento($datos['atleta_fecha']);
    $atletaExistente->setSexo($datos['atleta_sexo']);
    $atletaExistente->setCedula($datos['atleta_cedula']);
    $atletaExistente->setPieDominante($datos['pie_dominante']);
    $atletaExistente->setDireccion($datos['atleta_direccion']);
    if ($fotoRuta !== null) {
        $atletaExistente->setFoto($fotoRuta);
    }
    if ($cedulaDocRuta !== null) {
        $atletaExistente->setDocumentoCedula($cedulaDocRuta);
    }
    if ($partidaDocRuta !== null) {
        $atletaExistente->setDocumentoPartida($partidaDocRuta);
    }
    if (!$repoAtleta->update($atletaExistente)) {
        throw new Exception("Error al actualizar el atleta.");
    }

    // ---- 6d. Salud ----
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
            throw new Exception("Error al actualizar la salud.");
        }
    } else {
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
    }

    // ---- 6e. Categoria ----
    $categoria = $repoCategoria->findBySubCategoria($datos['categoria_atleta']);
    if (!$categoria) {
        $categoria = new Categoria(null, $datos['categoria_atleta']);
        if (!$repoCategoria->create($categoria)) {
            throw new Exception("Error al crear la categoria.");
        }
        $categoriaId = $categoria->getId();
        if (!$categoriaId) {
            throw new Exception("No se pudo obtener el ID de la categoria creada.");
        }
    } else {
        $categoriaId = $categoria->getId();
    }

    $relacionesCategoria = $repoCategoriaAtleta->findByAtleta($datos['atleta_id']);
    if (!empty($relacionesCategoria)) {
        $catAtleta = $relacionesCategoria[0];
        $catAtleta->setAnio($datos['categoria_anio']);
        $catAtleta->setCategoriaId($categoriaId);
        if (!$repoCategoriaAtleta->update($catAtleta)) {
            throw new Exception("Error al actualizar la categoria del atleta.");
        }
    } else {
        $catAtleta = new CategoriaAtleta(null, $datos['categoria_anio'], $datos['atleta_id'], $categoriaId);
        if (!$repoCategoriaAtleta->create($catAtleta)) {
            throw new Exception("Error al crear la relacion categoria-atleta.");
        }
    }

    // ---- 6f. Colegio y Grado ----
    $colegio = $repoColegio->findByNombre($datos['nombre_colegio']);

    if (!$colegio) {
        $colegio = new Colegio(null, $datos['nombre_colegio'], $datos['direccion_colegio'] ?: '');
        if (!$repoColegio->create($colegio)) {
            throw new Exception("Error al crear el colegio.");
        }
        $colegioId = (int) $db->lastInsertId();
        if ($colegioId == 0) {
            throw new Exception("No se pudo obtener el ID del colegio creado.");
        }
        $colegio->setId($colegioId);
    } else {
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
        $grado = $grados[0];
        $grado->setGrado($datos['grado_cursante']);
        $grado->setColegioId($colegioId);
        if (!$repoGrado->update($grado)) {
            throw new Exception("Error al actualizar el grado.");
        }
    } else {
        $grado = new Grado(null, $datos['grado_cursante'], $datos['atleta_id'], $colegioId);
        if (!$repoGrado->create($grado)) {
            throw new Exception("Error al crear el grado.");
        }
    }

    // ---- 6g. Hobbie ----
    $hobbieRelaciones = $repoHobbieAtleta->findByAtleta($datos['atleta_id']);
    foreach ($hobbieRelaciones as $rel) {
        $repoHobbieAtleta->delete($rel->getId());
    }

    if (!empty($datos['nombre_hobbie']) && !empty($datos['horario_hobbie'])) {
        $hobbie = $repoHobbie->findByNombre($datos['nombre_hobbie']);
        if (!$hobbie) {
            $hobbie = new Hobbie(null, $datos['nombre_hobbie']);
            if (!$repoHobbie->create($hobbie)) {
                throw new Exception("Error al crear el hobbie.");
            }
            $hobbieId = (int) $db->lastInsertId();
            if ($hobbieId == 0) {
                throw new Exception("No se pudo obtener el ID del hobbie.");
            }
            $hobbie->setId($hobbieId);
        } else {
            $hobbieId = $hobbie->getId();
        }

        $hobbieAtleta = new HobbieAtleta(null, $datos['horario_hobbie'], $datos['atleta_id'], $hobbieId);
        if (!$repoHobbieAtleta->create($hobbieAtleta)) {
            throw new Exception("Error al asociar el hobbie con el atleta.");
        }
    }

    // ---- Commit ----
    $db->commit();

    // ============================================================
    // 7. RESPUESTA EXITOSA
    // ============================================================
    $nombre_completo = trim($datos['primer_nombre'] . ' ' . $datos['segundo_nombre']) . ' ' . trim($datos['primer_apellido'] . ' ' . $datos['segundo_apellido']);
    $mensaje = "Atleta actualizado: $nombre_completo - Categoria: {$datos['categoria_atleta']}";

    if ($isAjax) {
        echo '<div class="alert alert-success py-2 small text-start" role="alert">';
        echo '<i class="bi bi-check-circle-fill me-2"></i>';
        echo '<strong>¡Actualizacion exitosa!</strong> ' . htmlspecialchars($mensaje);
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
    }

    $errorMsg = "Error: " . $e->getMessage();

    if ($isAjax) {
        echo '<div class="alert alert-danger py-2 small text-start" role="alert">';
        echo '<i class="bi bi-exclamation-triangle-fill me-2"></i>';
        echo '<strong>' . htmlspecialchars($errorMsg) . '</strong>';
        echo '</div>';
    } else {
        $referer = $_SERVER['HTTP_REFERER'] ?? 'index.php?View=ConsultaAtletas';
        $separator = (strpos($referer, '?') === false) ? '?' : '&';
        header("Location: $referer" . $separator . "error=" . urlencode($errorMsg));
    }
    exit;
}
