<?php
// ============================================
// /View/DashboardRepresentante.php
// PANEL DEL REPRESENTANTE - Con datos reales de BD
// ============================================

require_once './Model/Repository/RepositoryAtleta.php';
require_once './Model/Repository/RepositoryRepresentante.php';
require_once './Model/Repository/RepositoryAtletaRepresentante.php';
require_once './Model/Repository/RepositoryCategoria.php';
require_once './Model/Repository/RepositoryCategoriaAtleta.php';
require_once './Model/Repository/RepositoryEntrenamiento.php';
require_once './Model/Repository/RepositoryDesempenioEntrenamiento.php';
require_once './Model/Repository/RepositoryCuotaMensual.php';
require_once './Model/Repository/RepositoryNoticia.php';
require_once './Model/Repository/RepositoryPartido.php';
require_once './Model/Repository/RepositoryConvocado.php';
require_once './Model/Repository/RepositoryCategoriaEntrenamiento.php';
require_once './Model/Repository/RepositoryPagoCuota.php';
require_once './Model/Repository/RepositoryTipoPago.php';

// Verificar sesión
if(!isset($_SESSION['usuario'])) {
    header('Location: index.php?View=Login');
    exit;
}

$cedula_representante = $_SESSION['usuario']['cedula'];

// Instanciar repositorios
$repoAtleta = new RepositoryAtleta();
$repoRepresentante = new RepositoryRepresentante();
$repoAtletaRep = new RepositoryAtletaRepresentante();
$repoCategoria = new RepositoryCategoria();
$repoCategoriaAtleta = new RepositoryCategoriaAtleta();
$repoEntrenamiento = new RepositoryEntrenamiento();
$repoDesempenio = new RepositoryDesempenioEntrenamiento();
$repoCuota = new RepositoryCuotaMensual();
$repoNoticia = new RepositoryNoticia();
$repoPartido = new RepositoryPartido();
$repoConvocado = new RepositoryConvocado();
$repoCatEntrenamiento = new RepositoryCategoriaEntrenamiento();
$repoPagoCuota = new RepositoryPagoCuota();
$repoTipoPago = new RepositoryTipoPago();

// Obtener representante
$representante = $repoRepresentante->findByCedula($cedula_representante);
if(!$representante) {
    echo '<div class="alert alert-danger">Representante no encontrado en el sistema</div>';
    exit;
}

// Obtener atletas vinculados
$atletas = $repoAtleta->findByRepresentante($cedula_representante);

// Si no tiene atletas
if(empty($atletas)) {
    echo '<div class="alert alert-info">No tiene atletas vinculados a su cuenta</div>';
    exit;
}

// Seleccionar atleta
$atleta_seleccionado = isset($_GET['atleta_id']) ? $_GET['atleta_id'] : $atletas[0]->getAtletaId();
$atleta = $repoAtleta->findById($atleta_seleccionado);

if(!$atleta) {
    echo '<div class="alert alert-danger">Atleta no encontrado</div>';
    exit;
}

// Obtener categoría actual del atleta (usando la tabla puente)
$categorias_atleta = $repoCategoriaAtleta->findByAtleta($atleta_seleccionado);
$categoria_nombre = 'Sin categoría';
$categoria_id = null;

if(!empty($categorias_atleta)) {
    $cat_atleta = $categorias_atleta[0];
    $categoria_id = $cat_atleta->getCategoriaId();
    $categoria_obj = $repoCategoria->findById($categoria_id);
    if($categoria_obj) {
        $categoria_nombre = $categoria_obj->getSubCategoria();
    }
}

// Obtener desempeño del atleta en entrenamientos
$desempenios_atleta = $repoDesempenio->findByAtletaId($atleta_seleccionado);
$total_entrenamientos = count($desempenios_atleta);
$total_asistencias = 0;

foreach($desempenios_atleta as $d) {
    if($d->getAsistencia() == 1) {
        $total_asistencias++;
    }
}

$promedio_asistencia = $total_entrenamientos > 0 ? round(($total_asistencias / $total_entrenamientos) * 100) : 0;

// Obtener solvencia (cuotas del atleta)
$cuotas = $repoCuota->findByAtleta($atleta_seleccionado);

// Calcular solvencia correctamente - solo meses transcurridos
$mes_actual = date('n'); // Mes actual (1-12)
$anio_actual = date('Y'); // Año actual

$meses_pagados = 0;
$meses_pendientes = 0;
$meses_vencidos = 0;
$meses_parciales = 0;
$saldo_pendiente = 0;

foreach($cuotas as $cuota) {
    $estado = $cuota->getEstatusCuota();
    $monto = $cuota->getMontoEstablecido();
    $mes_cuota = $cuota->getMes();
    $anio_cuota = $cuota->getAnio();
    
    // Solo contar cuotas de meses que ya pasaron
    $es_mes_transcurrido = ($anio_cuota < $anio_actual) || 
                          ($anio_cuota == $anio_actual && $mes_cuota <= $mes_actual);
    
    if (!$es_mes_transcurrido) {
        continue; // Saltar cuotas de meses futuros
    }
    
    // Calcular saldo real de la cuota
    $abonado = $repoPagoCuota->sumAbonadoByCuota($cuota->getId());
    $saldo = $monto - $abonado;
    
    if($estado == 'Pagado' || $saldo <= 0) {
        $meses_pagados++;
    } elseif($estado == 'Vencido') {
        $meses_vencidos++;
        $saldo_pendiente += $saldo;
    } elseif($abonado > 0 && $saldo > 0) {
        // Parcial real: hay abonos registrados pero queda saldo (aunque la BD diga 'Pendiente')
        $meses_parciales++;
        $saldo_pendiente += $saldo;
    } else {
        $meses_pendientes++;
        $saldo_pendiente += $saldo;
    }
}

$total_meses = count($cuotas);
$esta_al_dia = ($meses_pendientes == 0 && $meses_vencidos == 0 && $meses_parciales == 0);

// Obtener partidos del atleta
$partidos_atleta = $repoConvocado->findByAtleta($atleta_seleccionado);
$partidos_jugados = count($partidos_atleta);

// Obtener noticias generales
$noticias = $repoNoticia->findAll();

// Obtener tipos de pago para el formulario
$tipos_pago = $repoTipoPago->findAll();

// Obtener cuotas pendientes para el select del formulario
$cuotas_pendientes = [];
foreach($cuotas as $cuota) {
    $abonado = $repoPagoCuota->sumAbonadoByCuota($cuota->getId());
    $saldo = $cuota->getMontoEstablecido() - $abonado;
    if($saldo > 0) {
        $cuotas_pendientes[] = [
            'id' => $cuota->getId(),
            'mes' => $cuota->getMes(),
            'anio' => $cuota->getAnio(),
            'monto' => $cuota->getMontoEstablecido(),
            'abonado' => $abonado,
            'saldo' => $saldo,
            'estado' => $cuota->getEstatusCuota()
        ];
    }
}

// Obtener convocatorias de entrenamiento para la categoría
$convocatorias_entrenamiento = [];
$entrenamientos = $repoEntrenamiento->findAll();

foreach($entrenamientos as $ent) {
    $cats = $repoCatEntrenamiento->findByEntrenamiento($ent->getEntrenamientoId());
    foreach($cats as $cat) {
        if($cat->getSubCategoria() == $categoria_nombre) {
            $convocatorias_entrenamiento[] = $ent;
            break;
        }
    }
}

// ============================================
// NOTIFICACIONES (noticias + convocatorias)
// ============================================
$visto_ts = isset($_SESSION['rep_notif_visto']) ? (int)$_SESSION['rep_notif_visto'] : 0;

$lista_notificaciones = [];
foreach($noticias as $n) {
    $fechaN = (string)$n->getFechaPublicacion();
    $lista_notificaciones[] = [
        'tipo'   => 'noticia',
        'titulo' => $n->getTitulo(),
        'fecha'  => $fechaN,
        'nueva'  => ($fechaN !== '' && strtotime($fechaN) > $visto_ts),
    ];
}
foreach($convocatorias_entrenamiento as $conv) {
    $fechaC = (string)$conv->getFechaEntrenamiento();
    $lista_notificaciones[] = [
        'tipo'   => 'convocatoria',
        'titulo' => 'Convocatoria: ' . $conv->getTipoEntrenamiento(),
        'fecha'  => $fechaC,
        'nueva'  => ($fechaC !== '' && strtotime($fechaC) > $visto_ts),
    ];
}

// Ordenar por fecha: más recientes primero
usort($lista_notificaciones, function($a, $b) {
    $fa = $a['fecha'] !== '' ? strtotime($a['fecha']) : 0;
    $fb = $b['fecha'] !== '' ? strtotime($b['fecha']) : 0;
    return $fb <=> $fa;
});

$nuevas_count = count(array_filter($lista_notificaciones, function($x) { return $x['nueva']; }));

// Si abrió las notificaciones, marcarlas como vistas (afecta la próxima recarga)
if(isset($_GET['notificaciones'])) {
    $_SESSION['rep_notif_visto'] = time();
}

function formatearFecha($fecha) {
    if(!$fecha) return 'N/A';
    return date('d/m/Y', strtotime($fecha));
}

function formatearMonto($monto) {
    return '$' . number_format((float)$monto, 2, ',', '.');
}
?>

<div class="container contenido">

    <!-- ===== MENSAJES DE ÉXITO/ERROR ===== -->
    <?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?php echo htmlspecialchars($_GET['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?php echo htmlspecialchars($_GET['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <!-- ===== HEADER ===== -->
    <div class="page-header">
        <div>
            <h4>
                <i class="bi bi-person-badge me-2"></i>
                Panel de Representante
            </h4>
            <p class="text-muted m-0 small">
                <?php echo htmlspecialchars($representante->getNombre() . ' ' . $representante->getApellido()); ?>
                <span class="text-muted ms-2">|</span>
                <span class="text-muted small">
                    <i class="bi bi-people me-1"></i>
                    <?php echo count($atletas); ?> atleta(s) vinculado(s)
                </span>
            </p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <!-- Botón Notificaciones -->
            <a href="?View=DashboardRepresentante&atleta_id=<?php echo $atleta_seleccionado; ?>&notificaciones=1"
               class="btn btn-outline-warning position-relative">
                <i class="bi bi-bell me-1"></i>Notificaciones
                <?php if($nuevas_count > 0): ?>
                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                    <?php echo $nuevas_count; ?><span class="visually-hidden">notificaciones nuevas</span>
                </span>
                <?php endif; ?>
            </a>
            <span class="text-muted small">
                <i class="bi bi-calendar3 me-1"></i>
                <?php echo date('d/m/Y'); ?>
            </span>
        </div>
    </div>

    <!-- ===== SELECTOR DE ATLETAS ===== -->
    <?php if(count($atletas) > 1): ?>
    <div class="selector-atletas">
        <?php foreach($atletas as $a): ?>
        <a href="?View=DashboardRepresentante&atleta_id=<?php echo $a->getAtletaId(); ?>" 
           class="btn-atleta <?php echo ($a->getAtletaId() == $atleta_seleccionado) ? 'active' : ''; ?>">
            <?php echo htmlspecialchars($a->getPrimerNombre() . ' ' . $a->getPrimerApellido()); ?>
            <small class="d-block text-muted" style="font-size:0.6rem;">
                <?php echo $categoria_nombre; ?>
            </small>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ===== PERFIL DEL ATLETA ===== -->
    <?php if($atleta): ?>
    <div class="perfil-atleta">
        <div class="perfil-avatar">
            <?php echo strtoupper(substr($atleta->getPrimerNombre(), 0, 1) . substr($atleta->getPrimerApellido(), 0, 1)); ?>
        </div>
        <div class="perfil-info">
            <h3><?php echo htmlspecialchars($atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido()); ?></h3>
            <div class="detalles">
                <span><i class="bi bi-tag"></i> <?php echo $categoria_nombre; ?></span>
                <span><i class="bi bi-person-badge"></i> <?php echo htmlspecialchars($atleta->getCedula()); ?></span>
                <span><i class="bi bi-calendar3"></i> <?php echo formatearFecha($atleta->getFechaNacimiento()); ?></span>
                <span><i class="bi bi-trophy"></i> <?php echo $partidos_jugados; ?> partidos</span>
                <span>
                    <i class="bi bi-cash-stack"></i> 
                    <?php echo $esta_al_dia ? 'Al día' : 'Con deuda'; ?>
                </span>
            </div>
        </div>
    </div>

    <!-- ===== ESTADÍSTICAS GENERALES ===== -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(13,110,253,0.12); color: #0d6efd;">
                    <i class="bi bi-trophy"></i>
                </div>
                <span class="valor"><?php echo $partidos_jugados; ?></span>
                <p class="etiqueta">Partidos Jugados</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(25,135,84,0.12); color: #198754;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <span class="valor"><?php echo $total_asistencias; ?></span>
                <p class="etiqueta">Asist. Entrenamientos</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(13,202,240,0.12); color: #0dcaf0;">
                    <i class="bi bi-graph-up"></i>
                </div>
                <span class="valor"><?php echo $promedio_asistencia; ?>%</span>
                <p class="etiqueta">Promedio Asistencia</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(255,193,7,0.12); color: #ffc107;">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <span class="valor"><?php echo $meses_pagados; ?></span>
                <p class="etiqueta">Meses Pagados</p>
            </div>
        </div>
    </div>

    <!-- ===== SOLVENCIA DEL ATLETA ===== -->
    <h5 class="seccion-titulo">
        <i class="bi bi-cash-stack text-success"></i>
        Estado de Solvencia
    </h5>

    <div class="row g-3 mb-3">
        <div class="col-md-3 col-6">
            <div class="stat-card text-center" style="border-left: 3px solid #198754;">
                <span class="valor" style="font-size:1.4rem;color:#198754;"><?php echo $meses_pagados; ?></span>
                <p class="etiqueta">Pagados</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card text-center" style="border-left: 3px solid #dc3545;">
                <span class="valor" style="font-size:1.4rem;color:#dc3545;"><?php echo $meses_pendientes; ?></span>
                <p class="etiqueta">Pendientes</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card text-center" style="border-left: 3px solid #ffc107;">
                <span class="valor" style="font-size:1.4rem;color:#ffc107;"><?php echo $meses_parciales; ?></span>
                <p class="etiqueta">Parciales</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card text-center" style="border-left: 3px solid #6c757d;">
                <span class="valor" style="font-size:1.4rem;color:#6c757d;"><?php echo $meses_vencidos; ?></span>
                <p class="etiqueta">Vencidos</p>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-3">
        <div class="col-12">
            <div class="stat-card text-center" style="border-left: 3px solid #dc3545;">
                <span class="valor" style="font-size:1.4rem;color:#dc3545;">
                    <?php echo formatearMonto($saldo_pendiente); ?>
                </span>
                <p class="etiqueta">Saldo Pendiente Total</p>
            </div>
        </div>
    </div>

    <!-- ===== BOTONES DE ACCIÓN ===== -->
    <div class="btn-group-acciones">
        <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarPago">
            <i class="bi bi-plus-circle me-1"></i>Registrar Pago
        </button>
        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalVerMas">
            <i class="bi bi-person-badge me-1"></i>Ver Expediente Completo
        </button>
    </div>

    <!-- ===== ÚLTIMOS PARTIDOS ===== -->
    <h5 class="seccion-titulo">
        <i class="bi bi-trophy text-warning"></i>
        Últimos Partidos
    </h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <?php if(empty($partidos_atleta)): ?>
            <div class="text-center text-muted py-3">
                <i class="bi bi-trophy me-2"></i>
                No hay partidos registrados
            </div>
            <?php else: ?>
            <?php foreach(array_slice($partidos_atleta, 0, 3) as $partido): ?>
            <div class="prueba-mini-card">
                <div class="info">
                    <i class="bi bi-trophy" style="color: #ffc107;"></i>
                    <span class="nombre">vs <?php echo htmlspecialchars($partido['equipo_rival'] ?? 'Rival'); ?></span>
                    <span class="fecha"><?php echo formatearFecha($partido['fecha_partido']); ?></span>
                    <span class="badge-mini" style="background: <?php echo $partido['estado'] == 'Finalizado' ? '#198754' : '#ffc107'; ?>; color: white;">
                        <?php echo $partido['estado']; ?>
                    </span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="valor" style="font-size:0.9rem;">
                        <?php echo $partido['goles_favor']; ?> - <?php echo $partido['goles_contra']; ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- ===== NOTICIAS Y CONVOCATORIAS ===== -->
    <h5 class="seccion-titulo">
        <i class="bi bi-bell text-info"></i>
        Noticias y Convocatorias
    </h5>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <h6 class="text-muted mb-2">Noticias Generales</h6>
            <?php if(empty($noticias)): ?>
            <div class="text-center text-muted py-3">
                <i class="bi bi-newspaper me-2"></i>
                No hay noticias disponibles
            </div>
            <?php else: ?>
            <?php foreach(array_slice($noticias, 0, 3) as $noticia): ?>
            <div class="prueba-mini-card">
                <div class="info">
                    <i class="bi bi-newspaper" style="color: #0dcaf0;"></i>
                    <span class="nombre"><?php echo htmlspecialchars($noticia->getTitulo()); ?></span>
                    <span class="fecha"><?php echo formatearFecha($noticia->getFechaPublicacion()); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <h6 class="text-muted mb-2">Convocatorias (<?php echo $categoria_nombre; ?>)</h6>
            <?php if(empty($convocatorias_entrenamiento)): ?>
            <div class="text-center text-muted py-3">
                <i class="bi bi-calendar-event me-2"></i>
                No hay convocatorias programadas
            </div>
            <?php else: ?>
            <?php foreach(array_slice($convocatorias_entrenamiento, 0, 3) as $conv): ?>
            <div class="prueba-mini-card">
                <div class="info">
                    <i class="bi bi-calendar-event" style="color: #ffc107;"></i>
                    <span class="nombre"><?php echo htmlspecialchars($conv->getTipoEntrenamiento()); ?></span>
                    <span class="fecha"><?php echo formatearFecha($conv->getFechaEntrenamiento()); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <?php else: ?>
    <div class="alert alert-warning text-center">
        <i class="bi bi-exclamation-triangle me-2"></i>
        No se encontró información del atleta
    </div>
    <?php endif; ?>

</div>

<!-- ===== MODALES ===== -->

<!-- Modal: Registrar Pago de Mensualidad (simplificado para representante) -->
<div class="modal fade" id="modalRegistrarPago" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle me-2"></i>Pagar Mensualidad
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="Controller/RegistrarPagoRepresentante_controller.php" method="POST" id="formRegistrarPago">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="atleta_id" value="<?php echo $atleta_seleccionado; ?>">
                     
                    <!-- Selección de cuota -->
                    <div class="mb-3">
                        <label class="form-label">Mes a Pagar</label>
                        <select class="form-select" name="cuota_id" id="cuotaSelect" required>
                            <option value="">Seleccionar...</option>
                            <?php foreach($cuotas_pendientes as $cuota): ?>
                            <option value="<?php echo $cuota['id']; ?>">
                                Mes <?php echo $cuota['mes']; ?>/
                                <?php echo $cuota['anio']; ?> - Saldo: $
                                <?php echo number_format($cuota['saldo'], 2); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Monto -->
                    <div class="mb-3">
                        <label class="form-label">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" name="monto" step="0.01" required>
                        </div>
                    </div>

                    <!-- Tipo de Pago -->
                    <div class="mb-3">
                        <label class="form-label">Método de Pago</label>
                        <select class="form-select" name="tipo_pago_id" id="tipoPago" required onchange="toggleCamposPago()">
                            <option value="">Seleccionar...</option>
                            <?php foreach($tipos_pago as $tipo): ?>
                            <option value="<?php echo $tipo->getId(); ?>">
                                <?php echo $tipo->getTipoPago(); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Banco -->
                    <div class="mb-3" id="bancoDiv" style="display:none;">
                        <label class="form-label">Banco</label>
                        <select class="form-select" name="banco" id="bancoSelect">
                            <option value="">Seleccionar...</option>
                            <option value="Banesco">Banesco</option>
                            <option value="Mercantil">Mercantil</option>
                            <option value="Provincial">Provincial</option>
                            <option value="Venezuela">Banco de Venezuela</option>
                            <option value="BOD">BOD</option>
                            <option value="Banco Nacional de Crédito">BNC</option>
                            <option value="Banco Exterior">Banco Exterior</option>
                            <option value="BBVA Provincial">BBVA Provincial</option>
                            <option value="Banco del Tesoro">Banco del Tesoro</option>
                            <option value="Banco Plaza">Banco Plaza</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <!-- Referencia -->
                    <div class="mb-3">
                        <label class="form-label">Referencia</label>
                        <input type="text" class="form-control" name="referencia" id="referenciaInput" placeholder="N° de transferencia, cheque, etc.">
                    </div>

                    <!-- Fecha de Pago -->
                    <div class="mb-3">
                        <label class="form-label">Fecha de Pago</label>
                        <input type="date" class="form-control" name="fecha_pago" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check-circle me-2"></i>Registrar Pago
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ver Expediente Completo -->
<div class="modal fade" id="modalVerMas" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content custom-modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-badge me-2"></i>Expediente del Atleta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php if($atleta): ?>
                <div class="atleta-header-modal">
                    <div class="atleta-avatar-modal">
                        <?php echo strtoupper(substr($atleta->getPrimerNombre(), 0, 1) . substr($atleta->getPrimerApellido(), 0, 1)); ?>
                    </div>
                    <div class="atleta-info-modal">
                        <h4><?php echo htmlspecialchars($atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido()); ?></h4>
                        <div class="atleta-badges">
                            <span class="badge bg-primary"><?php echo $categoria_nombre; ?></span>
                            <span class="badge bg-secondary"><?php echo htmlspecialchars($atleta->getCedula()); ?></span>
                            <span class="badge bg-<?php echo $esta_al_dia ? 'success' : 'danger'; ?>">
                                <?php echo $esta_al_dia ? 'Al día' : 'Con deuda'; ?>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-section-card">
                            <h6 class="section-card-title"><i class="bi bi-person me-1"></i>Información Personal</h6>
                            <div class="info-item"><span class="label-title">Nombre:</span> <span class="value"><?php echo htmlspecialchars($atleta->getPrimerNombre() . ' ' . ($atleta->getSegundoNombre() ? $atleta->getSegundoNombre() . ' ' : '') . $atleta->getPrimerApellido() . ' ' . ($atleta->getSegundoApellido() ? $atleta->getSegundoApellido() : '')); ?></span></div>
                            <div class="info-item"><span class="label-title">Fecha Nac.:</span> <span class="value"><?php echo formatearFecha($atleta->getFechaNacimiento()); ?></span></div>
                            <div class="info-item"><span class="label-title">Sexo:</span> <span class="value"><?php echo htmlspecialchars($atleta->getSexo()); ?></span></div>
                            <div class="info-item"><span class="label-title">Cédula:</span> <span class="value"><?php echo htmlspecialchars($atleta->getCedula()); ?></span></div>
                            <div class="info-item"><span class="label-title">Pie Dom.:</span> <span class="value"><?php echo htmlspecialchars($atleta->getPieDominante()); ?></span></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-section-card">
                            <h6 class="section-card-title"><i class="bi bi-cash-stack me-1"></i>Resumen Financiero</h6>
                            <div class="info-item"><span class="label-title">Meses Pagados:</span> <span class="value text-success"><?php echo $meses_pagados; ?></span></div>
                            <div class="info-item"><span class="label-title">Pendientes:</span> <span class="value text-danger"><?php echo $meses_pendientes; ?></span></div>
                            <div class="info-item"><span class="label-title">Parciales:</span> <span class="value text-warning"><?php echo $meses_parciales; ?></span></div>
                            <div class="info-item"><span class="label-title">Vencidos:</span> <span class="value text-muted"><?php echo $meses_vencidos; ?></span></div>
                            <div class="info-item"><span class="label-title">Saldo Pendiente:</span> <span class="value text-danger"><?php echo formatearMonto($saldo_pendiente); ?></span></div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function toggleCamposPago() {
    const tipoPagoId = document.getElementById('tipoPago').value;
    const bancoDiv = document.getElementById('bancoDiv');
    const referenciaInput = document.getElementById('referenciaInput');
    
    // Obtener el texto de la opción seleccionada
    const tipoPagoText = document.getElementById('tipoPago').options[document.getElementById('tipoPago').selectedIndex].text.toLowerCase();
    
    if (tipoPagoText === 'efectivo') {
        bancoDiv.style.display = 'none';
        referenciaInput.required = false;
    } else {
        bancoDiv.style.display = 'block';
        referenciaInput.required = true;
    }
}
</script>

<!-- ===== MODAL: NOTIFICACIONES ===== -->
<div class="modal fade" id="modalNotificaciones" tabindex="-1" aria-labelledby="tituloNotificaciones" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content custom-modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="tituloNotificaciones">
                    <i class="bi bi-bell me-2"></i>Notificaciones
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php if(empty($lista_notificaciones)): ?>
                <div class="text-center py-5 text-white-50">
                    <i class="bi bi-bell-slash display-4 mb-3"></i>
                    <p>No hay notificaciones disponibles</p>
                </div>
                <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach($lista_notificaciones as $notif): ?>
                    <div class="list-group-item bg-transparent d-flex align-items-start gap-3 px-2 py-3"
                         style="border-color: rgba(255,255,255,0.08);">
                        <?php if($notif['tipo'] == 'noticia'): ?>
                        <i class="bi bi-newspaper fs-4" style="color:#0dcaf0;"></i>
                        <?php else: ?>
                        <i class="bi bi-calendar-event fs-4" style="color:#ffc107;"></i>
                        <?php endif; ?>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center gap-2">
                                <strong class="text-white"><?php echo htmlspecialchars($notif['titulo']); ?></strong>
                                <?php if($notif['nueva']): ?>
                                <span class="badge bg-danger">Nueva</span>
                                <?php endif; ?>
                            </div>
                            <span class="badge bg-secondary mt-1"
                                  style="background: <?php echo $notif['tipo'] == 'noticia' ? '#0d6efd' : '#198754'; ?> !important;">
                                <?php echo $notif['tipo'] == 'noticia' ? 'Noticia' : 'Convocatoria'; ?>
                            </span>
                        </div>
                        <span class="text-white-50 small"><i class="bi bi-calendar3 me-1"></i><?php echo formatearFecha($notif['fecha']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="./Public/Js/DashboardRepresentante.js"></script>

<?php if(isset($_GET['notificaciones'])): ?>
<script>
window.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('modalNotificaciones');
    if (modal && typeof bootstrap !== 'undefined') {
        new bootstrap.Modal(modal).show();
    }
});
</script>
<?php endif; ?>
