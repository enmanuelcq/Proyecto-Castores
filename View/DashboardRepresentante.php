<?php
// ============================================
// /vistas/representante_dashboard.php
// DASHBOARD DEL REPRESENTANTE
// ============================================

require_once './Model/Atletas.php';
require_once './Model/Solvencia.php';
require_once './Model/Representantes.php';
require_once './Model/Pruebas.php';
require_once './Model/Participaciones_partidos.php';
require_once './Model/ReportesAdministrador.php';
require_once './Model/Noticias.php';

// Obtener cédula del representante desde sesión o URL
if(isset($_SESSION['usuario']['usuario_cedula']) && ($_SESSION['usuario']['usuario_rol'] === 'Representante')) {
    $cedula_representante = $_SESSION['usuario']['usuario_cedula'];
} else {
    echo '<div class="alert alert-warning">No se ha identificado al representante</div>';
    exit();
}

// Buscar representante
$representante = buscarRepresentantePorCedula($cedula_representante);
if(!$representante) {
    echo '<div class="alert alert-danger">Representante no encontrado</div>';
    exit();
}

// Obtener atletas del representante
$atletas_representante = obtenerAtletasPorRepresentante($cedula_representante, $todos_los_atletas);

if(empty($atletas_representante)) {
    echo '<div class="alert alert-info">No tienes atletas asociados a tu cuenta</div>';
    exit();
}

// Seleccionar atleta
$atleta_seleccionado = isset($_GET['Atleta']) ? $_GET['Atleta'] : $atletas_representante[0]['atleta_cedula'];
$atleta = buscarAtletaPorCedula($atleta_seleccionado, $todos_los_atletas);

// Si solo hay un atleta, seleccionarlo automáticamente
if(count($atletas_representante) == 1) {
    $atleta_seleccionado = $atletas_representante[0]['atleta_cedula'];
    $atleta = $atletas_representante[0];
}

// Obtener solvencia del atleta
$solvencia_atleta = obtenerSolvenciaAtleta($atleta_seleccionado, $todas_las_solvencias);
$meses_pendientes = contarMesesPendientesAtleta($atleta_seleccionado, $todas_las_solvencias);
$meses_activos = obtenerMesesActivosReportes();

// Calcular estadísticas de solvencia
$pagados = 0;
$pendientes = 0;
$revision = 0;
$por_vencer = 0;

if($solvencia_atleta) {
    foreach($solvencia_atleta as $clave => $info_mes) {
        if($clave !== 'atleta_cedula') {
            if(in_array($clave, $meses_activos)) {
                if($info_mes['estado'] == 'Pagado') $pagados++;
                elseif($info_mes['estado'] == 'Revision') $revision++;
                elseif($info_mes['estado'] == 'Pendiente' || $info_mes['estado'] == '') $pendientes++;
            } else {
                $por_vencer++;
            }
        }
    }
}

// Obtener estadísticas del atleta (partidos)
$resumen_partidos = resumenPartidosAtleta($atleta_seleccionado, $todas_las_participaciones, $todos_los_partidos);
$total_partidos = $resumen_partidos['total_partidos'];
$total_goles = $resumen_partidos['goles'];
$total_asistencias = $resumen_partidos['asistencias'];
$partidos_jugados = $resumen_partidos['partidos'] ?? [];

// Obtener estadísticas de pruebas
$pruebas_atleta = obtenerPruebasPorAtleta($atleta_seleccionado, $todas_las_pruebas);
$total_pruebas = 0;
$ultima_prueba = null;
foreach($pruebas_atleta as $tipo => $pruebas) {
    $total_pruebas += count($pruebas);
    if(!empty($pruebas)) {
        usort($pruebas, function($a, $b) {
            $fecha_a = isset($a['fecha']) ? $a['fecha'] : (isset($a['pruebaf_fecha']) ? $a['pruebaf_fecha'] : (isset($a['pruebav_fecha']) ? $a['pruebav_fecha'] : ''));
            $fecha_b = isset($b['fecha']) ? $b['fecha'] : (isset($b['pruebaf_fecha']) ? $b['pruebaf_fecha'] : (isset($b['pruebav_fecha']) ? $b['pruebav_fecha'] : ''));
            return strtotime($fecha_b) - strtotime($fecha_a);
        });
        $ultima_prueba = $pruebas[0];
    }
}



function formatearFecha($fecha) {
    if(!$fecha) return 'N/A';
    return date('d/m/Y', strtotime($fecha));
}


?>

<div class="container contenido">
    
    <!-- ===== HEADER ===== -->
    <div class="page-header">
        <div>
            <h4>
                <i class="bi bi-person-badge me-2"></i>
                Panel de Representante
            </h4>
            <p class="text-white-50 m-0 small">
                <?php echo $representante['usuario_nombre'] . ' ' . $representante['usuario_apellido']; ?>
                <span class="text-white-50 ms-2">|</span>
                <span class="text-white-50 small">
                    <i class="bi bi-people me-1"></i>
                    <?php echo count($atletas_representante); ?> atletas asociados
                </span>
            </p>
        </div>
        <div>
            <span class="text-white-50 small">
                <i class="bi bi-calendar3 me-1"></i>
                <?php echo date('d/m/Y'); ?>
            </span>
        </div>
    </div>

    <!-- ===== SELECTOR DE ATLETAS ===== -->
    <?php if(count($atletas_representante) > 1): ?>
    <div class="selector-atletas">
        <?php foreach($atletas_representante as $a): ?>
        <a href="?View=Representante&Representante=<?php echo $cedula_representante; ?>&Atleta=<?php echo $a['atleta_cedula']; ?>" 
           class="btn-atleta <?php echo ($a['atleta_cedula'] == $atleta_seleccionado) ? 'active' : ''; ?>">
            <?php echo $a['atleta_primer_nombre'] . ' ' . $a['atleta_primer_apellido']; ?>
            <small class="d-block text-white-50" style="font-size:0.6rem;">
                <?php echo $a['atleta_categoria']; ?>
            </small>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- ===== PERFIL DEL ATLETA ===== -->
    <?php if($atleta): ?>
    <div class="perfil-atleta">
        <div class="perfil-avatar">
            <?php 
            echo strtoupper(substr($atleta['atleta_primer_nombre'] ?? 'A', 0, 1) . substr($atleta['atleta_primer_apellido'] ?? 'A', 0, 1));
            ?>
        </div>
        <div class="perfil-info">
            <h3><?php echo $atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']; ?></h3>
            <div class="detalles">
                <span><i class="bi bi-tag"></i> <?php echo $atleta['atleta_categoria'] ?? 'Sin categoría'; ?></span>
                <span><i class="bi bi-person-badge"></i> <?php echo $atleta['atleta_cedula']; ?></span>
                <span><i class="bi bi-calendar3"></i> <?php echo $atleta['atleta_fecha_nacimiento'] ?? 'N/A'; ?></span>
                <span><i class="bi bi-trophy"></i> <?php echo $total_partidos; ?> partidos</span>
                <span><i class="bi bi-clipboard-data"></i> <?php echo $total_pruebas; ?> pruebas</span>
                <span>
                    <i class="bi bi-cash-stack"></i> 
                    <?php 
                    if($solvencia_atleta):
                        echo $pagados . ' pagados / ' . $pendientes . ' pendientes';
                    else:
                        echo 'Sin datos';
                    endif;
                    ?>
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
                <span class="valor"><?php echo $total_partidos; ?></span>
                <p class="etiqueta">Partidos Jugados</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(25,135,84,0.12); color: #198754;">
                    <i class="bi bi-emoji-sunglasses"></i>
                </div>
                <span class="valor"><?php echo $total_goles; ?></span>
                <p class="etiqueta">Goles Marcados</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(13,202,240,0.12); color: #0dcaf0;">
                    <i class="bi bi-people"></i>
                </div>
                <span class="valor"><?php echo $total_asistencias; ?></span>
                <p class="etiqueta">Asistencias</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(255,193,7,0.12); color: #ffc107;">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <span class="valor"><?php echo $total_pruebas; ?></span>
                <p class="etiqueta">Pruebas Realizadas</p>
            </div>
        </div>
    </div>

    <!-- ===== SOLVENCIA DEL ATLETA CON BOTONES ===== -->
    <h5 class="seccion-titulo">
        <i class="bi bi-cash-stack text-success"></i>
        Estado de Solvencia
        <span class="text-white-50" style="font-size:0.75rem;font-weight:400;margin-left:10px;">
            (<?php echo date('F Y'); ?>)
        </span>
    </h5>

    <div class="row g-3 mb-3">
        <div class="col-md-3 col-6">
            <div class="stat-card text-center clickable" style="border-left: 3px solid #198754;" onclick="abrirModalPagados('<?php echo $atleta['atleta_cedula']; ?>')">
                <span class="valor" style="font-size:1.4rem;color:#198754;"><?php echo $pagados; ?></span>
                <p class="etiqueta">Meses Pagados <i class="bi bi-eye ms-1" style="font-size:0.6rem;"></i></p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card text-center clickable" style="border-left: 3px solid #dc3545;" onclick="abrirModalRegistrarPago('<?php echo $atleta['atleta_cedula']; ?>')">
                <span class="valor" style="font-size:1.4rem;color:#dc3545;"><?php echo $pendientes; ?></span>
                <p class="etiqueta">Meses Pendientes <i class="bi bi-plus-circle ms-1" style="font-size:0.6rem;"></i></p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card text-center" style="border-left: 3px solid #ffc107;">
                <span class="valor" style="font-size:1.4rem;color:#ffc107;"><?php echo $revision; ?></span>
                <p class="etiqueta">En Revisión</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card text-center" style="border-left: 3px solid #6c757d;">
                <span class="valor" style="font-size:1.4rem;color:#6c757d;"><?php echo $por_vencer; ?></span>
                <p class="etiqueta">Por Vencer</p>
            </div>
        </div>
    </div>

    <!-- ===== BOTONES DE ACCIÓN ===== -->
    <div class="btn-group-acciones">
        <button class="btn btn-outline-success btn-sm" onclick="abrirModalPagados('<?php echo $atleta['atleta_cedula']; ?>')">
            <i class="bi bi-eye me-1"></i>Ver Pagados
        </button>
        <button class="btn btn-outline-warning btn-sm" onclick="abrirModalRegistrarPago('<?php echo $atleta['atleta_cedula']; ?>')">
            <i class="bi bi-plus-circle me-1"></i>Registrar Pago
        </button>
        <button class="btn btn-outline-info btn-sm" onclick="abrirModalVerMas('<?php echo $atleta['atleta_cedula']; ?>')">
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
            <?php 
            $contador = 0;
            foreach($partidos_jugados as $pj):
                if($contador >= 3) break;
                $partido = $pj['partido'];
                $participacion = $pj['participacion'];
                $contador++;
            ?>
            <div class="prueba-mini-card">
                <div class="info">
                    <i class="bi bi-trophy" style="color: #ffc107;"></i>
                    <span class="nombre">vs <?php echo $partido['rival']; ?></span>
                    <span class="fecha"><?php echo formatearFecha($partido['fecha']); ?></span>
                    <span class="badge-mini" style="background: <?php echo $partido['status'] == 'Ganado' ? '#198754' : ($partido['status'] == 'Perdido' ? '#dc3545' : '#ffc107'); ?>; color: <?php echo $partido['status'] == 'Empatado' ? '#212529' : '#fff'; ?>;">
                        <?php echo $partido['status']; ?>
                    </span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="valor" style="font-size:0.9rem;">
                        ⚽ <?php echo $participacion['goles']; ?>
                    </span>
                    <span style="color:rgba(255,255,255,0.3);font-size:0.8rem;">
                        <?php echo $participacion['minutos_jugados']; ?>'
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if($contador == 0): ?>
            <div class="text-center text-white-50 py-3">
                <i class="bi bi-trophy me-2"></i>
                No hay partidos registrados
            </div>
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

<!-- ============================================ -->
<!-- MODALES -->
<!-- ============================================ -->

<!-- ===== MODAL: VER MÁS (EXPEDIENTE COMPLETO) ===== -->
<?php include './Public/Modals/modal_representante_ver_mas.php'; ?>

<!-- ===== MODAL: MESES PAGADOS ===== -->
<?php include './Public/Modals/modal_representante_pagados.php'; ?>

<!-- ===== MODAL: REGISTRAR PAGO ===== -->
<?php include './Public/Modals/modal_representante_registrar_pago.php'; ?>

<script src="./Public/Js/DashboardRepresentante.js"></script>