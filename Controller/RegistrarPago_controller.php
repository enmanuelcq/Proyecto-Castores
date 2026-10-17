<?php
// Controller/RegistrarPago_controller.php
// Registra un pago de solvencia para un atleta

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . '/Castores3/';
$root = dirname(__DIR__);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante');
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante&error=' . urlencode('Token CSRF inválido'));
    exit;
}

require_once $root . '/Config/main.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';
require_once $root . '/Model/Repository/RepositoryCuotaMensual.php';
require_once $root . '/Model/Repository/RepositoryPago.php';
require_once $root . '/Model/Repository/RepositoryOperacion.php';
require_once $root . '/Model/Repository/RepositoryTipoPago.php';

// Obtener datos del formulario
$atleta_id = limpiar_cadena($_POST['atleta_id'] ?? '');
$monto = floatval($_POST['monto'] ?? 0);
$concepto = limpiar_cadena($_POST['concepto'] ?? 'Mensualidad');
$cedula_representante = $_SESSION['usuario']['cedula'] ?? '';

// Validaciones
if (empty($atleta_id) || $monto <= 0) {
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante&error=' . urlencode('Datos incompletos'));
    exit;
}

// Verificar que el atleta existe
$repoAtleta = new RepositoryAtleta();
$atleta = $repoAtleta->findById($atleta_id);

if (!$atleta) {
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante&error=' . urlencode('Atleta no encontrado'));
    exit;
}

// Registrar el pago
$repoPago = new RepositoryPago();
$pago = new Pago(
    0,
    $monto,
    $concepto,
    $cedula_representante,
    date('Y-m-d H:i:s'),
    'Completado'
);

if ($repoPago->create($pago)) {
    // Actualizar la cuota más antigua pendiente del atleta
    $repoCuota = new RepositoryCuotaMensual();
    $cuotas = $repoCuota->findByAtleta($atleta_id);
    
    foreach ($cuotas as $cuota) {
        if ($cuota->getEstatusCuota() == 'Pendiente' || $cuota->getEstatusCuota() == 'Vencido') {
            $repoCuota->updateEstado($cuota->getId(), 'Pagado');
            break;
        }
    }
    
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante&success=' . urlencode('Pago registrado correctamente'));
} else {
    header('Location: ' . $baseUrl . 'index.php?View=DashboardRepresentante&error=' . urlencode('Error al registrar el pago'));
}
exit;
