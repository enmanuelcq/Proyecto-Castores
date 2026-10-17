<?php
// Controller/ConfirmarPago_controller.php
// Permite a la secretaría confirmar los pagos cargados por los representantes

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . '/Castores3/';
$root = dirname(__DIR__);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar que el usuario sea secretaría o admin
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['id_rol'] != 2 && $_SESSION['usuario']['id_rol'] != 1)) {
    header('Location: ' . $baseUrl . 'index.php?View=Login');
    exit;
}

require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Repository/RepositoryPago.php';
require_once $root . '/Model/Repository/RepositoryPagoCuota.php';
require_once $root . '/Model/Repository/RepositoryCuotaMensual.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . 'index.php?View=Consulta_atletas');
    exit;
}

// Validar CSRF
if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ' . $baseUrl . 'index.php?View=Consulta_atletas&error=' . urlencode('Token CSRF inválido'));
    exit;
}

$pago_id = isset($_POST['pago_id']) ? (int)$_POST['pago_id'] : 0;

if ($pago_id <= 0) {
    header('Location: ' . $baseUrl . 'index.php?View=Consulta_atletas&error=' . urlencode('Pago no válido'));
    exit;
}

// Obtener el pago
$repoPago = new RepositoryPago();
$pago = $repoPago->findById($pago_id);

if (!$pago) {
    header('Location: ' . $baseUrl . 'index.php?View=Consulta_atletas&error=' . urlencode('Pago no encontrado'));
    exit;
}

// Verificar que el pago esté pendiente
if ($pago->getEstatusPago() !== 'Pendiente') {
    header('Location: ' . $baseUrl . 'index.php?View=Consulta_atletas&error=' . urlencode('Este pago ya fue procesado'));
    exit;
}

// Confirmar el pago
try {
    $db = Database::getConnection();
    $db->beginTransaction();

    // Actualizar estatus del pago a Completado
    $repoPago->updateEstatus($pago_id, 'Completado');

    // Obtener las cuotas asociadas a este pago
    $repoPagoCuota = new RepositoryPagoCuota();
    $pagoCuotas = $repoPagoCuota->findByPago($pago_id);

    $repoCuota = new RepositoryCuotaMensual();

    $atletaIdRedir = '';
    foreach ($pagoCuotas as $pagoCuota) {
        $cuota_id = $pagoCuota->getCuotaId();
        $cuota = $repoCuota->findById($cuota_id);
        
        if ($cuota) {
            if ($atletaIdRedir === '') {
                $atletaIdRedir = $cuota->getAtletaId(); // para reabrir la solvencia del atleta
            }
            // Calcular total abonado
            $abonadoTotal = $repoPagoCuota->sumAbonadoByCuota($cuota_id);
            
            if ($abonadoTotal >= $cuota->getMontoEstablecido()) {
                $repoCuota->updateEstado($cuota_id, 'Pagado');
            } else {
                $repoCuota->updateEstado($cuota_id, 'Parcial');
            }
        }
    }

    $db->commit();

    // Redirigir de vuelta indicando el atleta para reabrir su modal de solvencia con datos frescos
    $urlRedir = $baseUrl . 'index.php?View=Consulta_atletas&success=1&mensaje='
        . urlencode('Pago confirmado correctamente. La mensualidad quedó registrada como abonada.');
    if ($atletaIdRedir !== '') {
        $urlRedir .= '&abrir_solvencia=' . urlencode($atletaIdRedir);
    }
    header('Location: ' . $urlRedir);
    exit;

} catch (Exception $e) {
    if (isset($db)) {
        $db->rollBack();
    }
    header('Location: ' . $baseUrl . 'index.php?View=Consulta_atletas&error=' . urlencode($e->getMessage()));
    exit;
}
