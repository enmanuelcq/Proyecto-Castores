<?php
// Controller/RegistrarPagoRepresentante_controller.php
// Registra un pago de mensualidad desde el panel del representante
// El pago queda como "Pendiente" hasta que la secretaría lo confirme

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . '/Castores3/';
$root = dirname(__DIR__);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario sea representante
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['id_rol'] != 4) {
    header('Location: ' . $baseUrl . 'index.php?View=Login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante');
    exit;
}

// Validar CSRF
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante&error=' . urlencode('Token CSRF inválido'));
    exit;
}

require_once $root . '/Config/main.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';
require_once $root . '/Model/Repository/RepositoryCuotaMensual.php';
require_once $root . '/Model/Repository/RepositoryPago.php';
require_once $root . '/Model/Repository/RepositoryPagoCuota.php';
require_once $root . '/Model/Repository/RepositoryOperacion.php';
require_once $root . '/Model/Repository/RepositoryTipoPago.php';
require_once $root . '/Model/Entities/Pago.php';
require_once $root . '/Model/Entities/PagoCuota.php';
require_once $root . '/Model/Entities/Operacion.php';

// Obtener datos del formulario
$atleta_id = limpiar_cadena($_POST['atleta_id'] ?? '');
$cuota_id = isset($_POST['cuota_id']) ? (int)$_POST['cuota_id'] : 0;
$monto = isset($_POST['monto']) ? (float)$_POST['monto'] : 0;
$tipo_pago_id = isset($_POST['tipo_pago_id']) ? (int)$_POST['tipo_pago_id'] : 0;
$referencia = limpiar_cadena($_POST['referencia'] ?? '');
$banco = limpiar_cadena($_POST['banco'] ?? '');
$fecha_pago = limpiar_cadena($_POST['fecha_pago'] ?? date('Y-m-d'));

$cedula_representante = $_SESSION['usuario']['cedula'];

// URL base de redirección: siempre de vuelta a "mi atleta" del panel del representante
// IMPORTANTE: Atleta_id es varchar, NUNCA convertirlo a (int)
$redirectAtleta = $baseUrl . 'index.php?View=DashboardRepresentante&atleta_id=' . urlencode($atleta_id);

// Validaciones
if (empty($atleta_id)) {
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante&error=' . urlencode('ID del atleta no proporcionado'));
    exit;
}

if ($cuota_id <= 0) {
    header('Location: ' . $redirectAtleta . '&error=' . urlencode('Debe seleccionar una cuota'));
    exit;
}

if ($monto <= 0) {
    header('Location: ' . $redirectAtleta . '&error=' . urlencode('El monto debe ser mayor a 0'));
    exit;
}

if ($tipo_pago_id <= 0) {
    header('Location: ' . $redirectAtleta . '&error=' . urlencode('Debe seleccionar un método de pago'));
    exit;
}

// Verificar que el atleta existe y pertenece al representante
$repoAtleta = new RepositoryAtleta();
$atleta = $repoAtleta->findById($atleta_id);
if (!$atleta) {
    header('Location: ' . $redirectAtleta . '&error=' . urlencode('Atleta no encontrado'));
    exit;
}

// Verificar que la cuota existe y tiene saldo
$repoCuota = new RepositoryCuotaMensual();
$repoPago = new RepositoryPago();
$repoOperacion = new RepositoryOperacion();
$cuota = $repoCuota->findById($cuota_id);
if (!$cuota) {
    header('Location: ' . $redirectAtleta . '&error=' . urlencode('Cuota no encontrada'));
    exit;
}

$repoPagoCuota = new RepositoryPagoCuota();
$abonado = $repoPagoCuota->sumAbonadoByCuota($cuota_id);
$saldo = $cuota->getMontoEstablecido() - $abonado;

if ($saldo <= 0) {
    header('Location: ' . $redirectAtleta . '&error=' . urlencode('Esta cuota ya está pagada'));
    exit;
}

// Validar tipo de pago
$repoTipoPago = new RepositoryTipoPago();
$tipoPago = $repoTipoPago->findById($tipo_pago_id);
if (!$tipoPago) {
    header('Location: ' . $redirectAtleta . '&error=' . urlencode('Tipo de pago no válido'));
    exit;
}

// Si no es efectivo, validar banco y referencia
$esEfectivo = strtolower(trim($tipoPago->getTipoPago())) === 'efectivo';
if (!$esEfectivo) {
    if (empty($banco)) {
        header('Location: ' . $redirectAtleta . '&error=' . urlencode('Debe seleccionar un banco'));
        exit;
    }
    if (empty($referencia)) {
        header('Location: ' . $redirectAtleta . '&error=' . urlencode('La referencia es obligatoria'));
        exit;
    }
    
    // Verificar referencia duplicada
    if ($repoOperacion->existsReferencia($tipo_pago_id, $banco, $referencia)) {
        header('Location: ' . $redirectAtleta . '&error=' . urlencode('Esta referencia ya fue utilizada'));
        exit;
    }
}

// Iniciar transacción
try {
    $db = Database::getConnection();
    $db->beginTransaction();

    // Crear el pago con estatus Pendiente (pendiente de confirmación por secretaría)
    $concepto = 'Mensualidad de ' . $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido()
        . ' (Rep. cédula ' . $cedula_representante . ')';
    $pago = new Pago(
        null,
        $monto,
        $concepto,
        $cedula_representante,
        $fecha_pago . ' ' . date('H:i:s'),
        'Pendiente' // Estado pendiente hasta que la secretaría lo confirme
    );

    if (!$repoPago->create($pago)) {
        throw new Exception("Error al crear el pago");
    }
    $pagoId = $pago->getId();

    // Asociar pago a la cuota
    $pagoCuota = new PagoCuota(null, $pagoId, $cuota_id, $monto);
    if (!$repoPagoCuota->create($pagoCuota)) {
        throw new Exception("Error al asociar el pago a la cuota");
    }

    // Actualizar estado de la cuota (temporalmente hasta confirmación)
    if ($abonado + $monto >= $cuota->getMontoEstablecido()) {
        $repoCuota->updateEstado($cuota_id, 'Parcial'); // Queda como Parcial hasta confirmación
    } else {
        $repoCuota->updateEstado($cuota_id, 'Parcial');
    }

    // Registrar operación (método de pago)
    $operacion = new Operacion(
        null,
        $monto,
        $pagoId,
        $tipo_pago_id,
        $esEfectivo ? null : $referencia,
        $esEfectivo ? null : $banco
    );
    if (!$repoOperacion->create($operacion)) {
        throw new Exception("Error al registrar la operación de pago");
    }

    $db->commit();

    // Redirigir de vuelta a "Mi Atleta" del panel del representante (nunca a Consulta_atletas)
    header('Location: ' . $redirectAtleta
        . '&success=' . urlencode('Pago registrado correctamente. Queda pendiente de confirmación por la secretaría.'));
    exit;

} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    header('Location: ' . $redirectAtleta . '&error=' . urlencode($e->getMessage()));
    exit;
}
