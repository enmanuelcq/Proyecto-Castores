<?php
$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Model/Repository/RepositoryConfiguracion.php';

$repoConfig = new RepositoryConfiguracion();
$montoUSD = $repoConfig->getMontoMensualUSD();
$tasa = $repoConfig->getTasaBCV();
$montoBs = $repoConfig->getMontoMensualBs();
$ultimaActualizacion = $repoConfig->getValor('ultima_actualizacion_tasa');
?>

<div class="container py-4">
    <!-- ===== TÍTULO ===== -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark m-0">⚙️ Configuración de Cuotas</h2>
            <p class="text-muted m-0">Gestiona el monto mensual y la tasa de cambio</p>
        </div>
        <span class="badge bg-primary bg-opacity-10 text-primary p-2 px-3 rounded-pill">
            <i class="bi bi-clock me-1"></i>
            Última act: <?php echo $ultimaActualizacion ? date('d/m/Y H:i', strtotime($ultimaActualizacion)) : 'Nunca'; ?>
        </span>
    </div>

    <!-- ===== MENSAJES ===== -->
    <?php if(isset($_GET['success']) && isset($_GET['mensaje'])): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <span><?php echo htmlspecialchars($_GET['mensaje']); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
            <span><?php echo htmlspecialchars($_GET['error']); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== CARD PRINCIPAL ===== -->
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-lg-5">
            <form action="Controller/GuardarConfiguracionController.php" method="POST">
                
                <!-- FILA 1: Monto USD + Tasa BCV -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-currency-dollar text-primary me-1"></i>
                                Monto mensual (USD)
                            </label>
                            <input type="number" step="0.01" name="monto_usd" 
                                   class="form-control form-control-lg rounded-3" 
                                   value="<?php echo number_format($montoUSD, 2, '.', ''); ?>" 
                                   required>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Este es el valor en dólares que se multiplicará por la tasa del BCV
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-graph-up-arrow text-warning me-1"></i>
                                Tasa BCV actual
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control rounded-start-3" 
                                       value="<?php echo number_format($tasa, 2); ?>" disabled>
                                <span class="input-group-text bg-light rounded-end-3">Bs/USD</span>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-clock me-1"></i>
                                Última actualización: <?php echo $ultimaActualizacion ? date('d/m/Y H:i:s', strtotime($ultimaActualizacion)) : 'Nunca'; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FILA 2: Monto Bs + Botones -->
                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-cash-stack text-success me-1"></i>
                                Monto en bolívares (calculado)
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="text" class="form-control rounded-start-3" 
                                       value="<?php echo number_format($montoBs, 2); ?>" disabled>
                                <span class="input-group-text bg-light rounded-end-3">Bs</span>
                            </div>
                            <div class="form-text">
                                <i class="bi bi-calculator me-1"></i>
                                Se calcula automáticamente: USD × Tasa
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="d-flex flex-wrap gap-2 w-100">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1 rounded-3">
                                <i class="bi bi-save me-2"></i> Guardar
                            </button>
                            <a href="Controller/ActualizarTasaController.php" class="btn btn-warning btn-lg flex-grow-1 rounded-3">
                                <i class="bi bi-arrow-repeat me-2"></i> Actualizar tasa
                            </a>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- ===== FOOTER INFORMATIVO ===== -->
    <div class="mt-4 p-3 bg-light rounded-3 border">
        <div class="d-flex flex-wrap align-items-center gap-3">
            <div class="d-flex align-items-center">
                <span class="badge bg-success me-2">●</span>
                <small class="text-muted">Tasa actualizada automáticamente todos los días</small>
            </div>
            <div class="d-flex align-items-center">
                <span class="badge bg-info me-2">●</span>
                <small class="text-muted">Las cuotas pendientes se actualizan con la nueva tasa</small>
            </div>
        </div>
    </div>
</div>

<script>
// Script para cerrar alertas automáticamente después de 5 segundos
document.addEventListener('DOMContentLoaded', function() {
    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            var closeButton = alert.querySelector('.btn-close');
            if (closeButton) closeButton.click();
        }, 5000);
    });
});
</script>