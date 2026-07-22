<?php
// ============================================================
// 1. CONFIGURACIÓN INICIAL
// ============================================================
$root = dirname(__DIR__);
require_once $root . '/Config/main.php';        // Funciones: limpiar_cadena, verificar_datos, etc.
require_once $root . '/Config/Database.php';

// Entidades
require_once $root . '/Model/Entities/Pago.php';
require_once $root . '/Model/Entities/PagoCuota.php';
require_once $root . '/Model/Entities/Operacion.php';

// Repositorios
require_once $root . '/Model/Repository/RepositoryPago.php';
require_once $root . '/Model/Repository/RepositoryPagoCuota.php';
require_once $root . '/Model/Repository/RepositoryOperacion.php';
require_once $root . '/Model/Repository/RepositoryCuotaMensual.php';
require_once $root . '/Model/Repository/RepositoryAtletaRepresentante.php';
require_once $root . '/Model/Repository/RepositoryTipoPago.php';

// ============================================================
// 2. VERIFICAR MÉTODO POST
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php?View=Consulta_atletas&error=' . urlencode('Método no permitido'));
    exit;
}

// ============================================================
// 3. RECOGER Y SANITIZAR DATOS CON limpiar_cadena()
// ============================================================
$atleta_id = limpiar_cadena($_POST['atleta_id'] ?? '');
$tipo_concepto = limpiar_cadena($_POST['tipo_concepto'] ?? '');
$concepto_personalizado = limpiar_cadena($_POST['concepto_personalizado'] ?? '');
$cuota_id = isset($_POST['cuota_id']) && $_POST['cuota_id'] !== '' ? (int)$_POST['cuota_id'] : null;
$monto = isset($_POST['monto']) ? (float)$_POST['monto'] : 0;
$tipo_pago_id = isset($_POST['tipo_pago_id']) ? (int)$_POST['tipo_pago_id'] : 0;
$referencia = limpiar_cadena($_POST['referencia'] ?? '');
$banco = limpiar_cadena($_POST['banco'] ?? '');
$fecha_pago = limpiar_cadena($_POST['fecha_pago'] ?? date('Y-m-d'));

// ============================================================
// 4. VALIDACIONES BÁSICAS
// ============================================================
$errores = [];

if (empty($atleta_id)) $errores[] = "ID del atleta no proporcionado.";
if (empty($tipo_concepto)) $errores[] = "Debe seleccionar un tipo de concepto.";
if ($monto <= 0) $errores[] = "El monto debe ser mayor a 0.";
if ($tipo_pago_id <= 0) $errores[] = "Debe seleccionar un método de pago.";

// Validar concepto personalizado si es "Otro"
if ($tipo_concepto === 'Otro' && empty($concepto_personalizado)) {
    $errores[] = "Debe especificar un concepto personalizado.";
}

// Validar cuota si es mensualidad
if ($tipo_concepto === 'Mensualidad' && !$cuota_id) {
    $errores[] = "Debe seleccionar una cuota para el pago de mensualidad.";
}

// Si hay errores, redirigir
if (!empty($errores)) {
    header('Location: ../index.php?View=Consulta_atletas&error=' . urlencode(implode(' | ', $errores)));
    exit;
}

// ============================================================
// 5. VALIDACIONES DE BANCO Y REFERENCIA
// ============================================================
try {
    // Obtener el tipo de pago para saber si es efectivo
    $repoTipoPago = new RepositoryTipoPago();
    $tipoPago = $repoTipoPago->findById($tipo_pago_id);
    
    if (!$tipoPago) {
        throw new Exception("Tipo de pago no válido.");
    }
    
    $esEfectivo = strtolower(trim($tipoPago->getTipoPago())) === 'efectivo';
    
    // Si NO es efectivo, validar banco y referencia
    if (!$esEfectivo) {
        if (empty($banco)) {
            throw new Exception("Debe seleccionar un banco para pagos no en efectivo.");
        }
        if (empty($referencia)) {
            throw new Exception("La referencia es obligatoria para pagos no en efectivo.");
        }
        
        // Verificar referencia duplicada (mismo tipo_pago + banco + referencia)
        $repoOperacion = new RepositoryOperacion();
        if ($repoOperacion->existsReferencia($tipo_pago_id, $banco, $referencia)) {
            throw new Exception("La referencia '$referencia' ya ha sido utilizada para el banco '$banco'. Por favor, verifica.");
        }
    } else {
        // Si es efectivo, limpiamos campos
        $banco = null;
        $referencia = null;
    }

} catch (Exception $e) {
    header('Location: ../index.php?View=Consulta_atletas&error=' . urlencode($e->getMessage()));
    exit;
}

// ============================================================
// 6. CONSTRUIR CONCEPTO
// ============================================================
if ($tipo_concepto === 'Mensualidad') {
    $concepto = 'Pago de mensualidad - Atleta ' . $atleta_id;
} elseif ($tipo_concepto === 'Otro') {
    $concepto = $concepto_personalizado . ' - Atleta ' . $atleta_id;
} else {
    $concepto = 'Pago de ' . $tipo_concepto . ' - Atleta ' . $atleta_id;
}

// ============================================================
// 7. PROCESAR PAGO (TRANSACCIÓN)
// ============================================================
try {
    $db = Database::getConnection();
    $db->beginTransaction();

    // --- 7a. Obtener representante del atleta ---
    $repoAtletaRep = new RepositoryAtletaRepresentante();
    $relaciones = $repoAtletaRep->findByAtleta($atleta_id);
    if (empty($relaciones)) {
        throw new Exception("El atleta no tiene representante asociado.");
    }
    $cedulaRepresentante = $relaciones[0]->getCedulaRepresentante();

    // --- 7b. Crear el pago (cabecera) ---
    $repoPago = new RepositoryPago();
    $pago = new Pago(
        null,
        $monto,
        $concepto,
        $cedulaRepresentante,
        $fecha_pago . ' ' . date('H:i:s'),
        'Completado'
    );
    if (!$repoPago->create($pago)) {
        throw new Exception("Error al crear el pago.");
    }
    $pagoId = $pago->getId();
    if (!$pagoId) {
        throw new Exception("No se pudo obtener el ID del pago.");
    }

    // --- 7c. Asociar pago a la cuota (si es mensualidad) ---
    if ($tipo_concepto === 'Mensualidad' && $cuota_id) {
        $repoPagoCuota = new RepositoryPagoCuota();
        $pagoCuota = new PagoCuota(null, $pagoId, $cuota_id, $monto);
        if (!$repoPagoCuota->create($pagoCuota)) {
            throw new Exception("Error al asociar el pago a la cuota.");
        }

        // Actualizar estado de la cuota
        $repoCuota = new RepositoryCuotaMensual();
        $cuota = $repoCuota->findById($cuota_id);
        if ($cuota) {
            $abonadoTotal = $repoPagoCuota->sumAbonadoByCuota($cuota_id);
            if ($abonadoTotal >= $cuota->getMontoEstablecido()) {
                $repoCuota->updateEstado($cuota_id, 'Pagado');
            } else {
                $repoCuota->updateEstado($cuota_id, 'Parcial');
            }
        }
    }

    // --- 7d. Registrar operación (método de pago) ---
    $repoOperacion = new RepositoryOperacion();
    $operacion = new Operacion(
        null,
        $monto,
        $pagoId,
        $tipo_pago_id,
        $referencia,
        $banco
    );
    if (!$repoOperacion->create($operacion)) {
        throw new Exception("Error al registrar la operación de pago.");
    }

    // --- 7e. Confirmar transacción ---
    $db->commit();

    // ============================================================
    // 8. REDIRIGIR CON MENSAJE DE ÉXITO
    // ============================================================
    header('Location: ../index.php?View=Consulta_atletas&success=1&mensaje=' . urlencode('Pago registrado correctamente'));
    exit;

} catch (Exception $e) {
    // Rollback en caso de error
    if (isset($db)) {
        $db->rollBack();
    }
    header('Location: ../index.php?View=Consulta_atletas&error=' . urlencode($e->getMessage()));
    exit;
}