<?php
// ============================================
// /vistas/entrenamientos.php
// VISTA PRINCIPAL DE ENTRENAMIENTOS
// ============================================

require_once './Model/Entrenamiento.php';
require_once './Model/Asistencias.php';
require_once './Model/Atletas.php';

// Obtener filtros
$categoria_filtro = isset($_GET['categoria']) ? $_GET['categoria'] : 'todas';
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : 'todos';

// Filtrar entrenamientos
$entrenamientos_filtrados = $todos_los_entrenamientos;
if($categoria_filtro != 'todas') {
    $entrenamientos_filtrados = array_filter($entrenamientos_filtrados, function($e) use ($categoria_filtro) {
        return $e['categoria'] == $categoria_filtro;
    });
}
if($estado_filtro != 'todos') {
    $entrenamientos_filtrados = array_filter($entrenamientos_filtrados, function($e) use ($estado_filtro) {
        return $e['estado'] == $estado_filtro;
    });
}
$entrenamientos_filtrados = array_values($entrenamientos_filtrados);

// Obtener categorías únicas para el filtro
$categorias = array_unique(array_column($todos_los_entrenamientos, 'categoria'));

// Estadísticas del entrenador
$estadisticas_entrenador = obtenerEstadisticasEntrenador('26804373', $todos_los_entrenamientos);
$proximos_entrenamientos = obtenerProximosEntrenamientos($todos_los_entrenamientos, 3);

// Estadísticas generales
$total_entrenamientos = count($todos_los_entrenamientos);
$finalizados = contarEntrenamientosPorEstado('Finalizado', $todos_los_entrenamientos);
$en_curso = contarEntrenamientosPorEstado('En curso', $todos_los_entrenamientos);
$programados = contarEntrenamientosPorEstado('Programado', $todos_los_entrenamientos);
?>

<div class="container contenido">
    
    <!-- ===== HEADER ===== -->
    <div class="page-header">
        <div>
            <h4>
                <i class="bi bi-dumbbell me-2"></i>
                Gestión de Entrenamientos
            </h4>
            <p class="text-white m-0 small">Registra y gestiona los entrenamientos de la academia</p>
        </div>
        <div>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalRegistrarEntrenamiento">
                <i class="bi bi-plus-circle-fill me-2"></i>Nuevo Entrenamiento
            </button>
        </div>
    </div>

    <!-- ===== ESTADÍSTICAS DEL ENTRENADOR ===== -->
    <div class="row g-3 mb-4">
        <div class="col-lg-9">
            <div class="row g-2">
                <div class="col-3">
                    <div class="stat-card-mini">
                        <span class="stat-numero"><?php echo $estadisticas_entrenador['total']; ?></span>
                        <span class="stat-etiqueta">Total Entrenamientos</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="stat-card-mini">
                        <span class="stat-numero" style="color: #198754;"><?php echo $estadisticas_entrenador['finalizados']; ?></span>
                        <span class="stat-etiqueta">Finalizados</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="stat-card-mini">
                        <span class="stat-numero" style="color: #ffc107;"><?php echo $estadisticas_entrenador['en_curso']; ?></span>
                        <span class="stat-etiqueta">En Curso</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="stat-card-mini">
                        <span class="stat-numero" style="color: #0d6efd;"><?php echo $estadisticas_entrenador['programados']; ?></span>
                        <span class="stat-etiqueta">Programados</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="stat-card-mini border-left">
                <span class="stat-numero" style="font-size: 1.2rem; color: #ffc107;">
                    <?php echo $estadisticas_entrenador['promedio_asistencia']; ?>%
                </span>
                <span class="stat-etiqueta">Promedio de Asistencia</span>
                <small class="text-white d-block" style="font-size: 0.65rem;">
                    <?php echo $estadisticas_entrenador['total_asistentes']; ?> asistentes / <?php echo $estadisticas_entrenador['total_atletas']; ?> convocados
                </small>
            </div>
        </div>
    </div>

    <!-- ===== PRÓXIMOS ENTRENAMIENTOS ===== -->
    <?php if(!empty($proximos_entrenamientos)): ?>
    <div class="mb-4">
        <h6 class="text-white mb-2">
            <i class="bi bi-calendar-event me-2 text-warning"></i>
            Próximos Entrenamientos
        </h6>
        <div class="row g-2">
            <?php foreach($proximos_entrenamientos as $pe): ?>
            <div class="col-md-4">
                <div class="proximo-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="badge bg-primary"><?php echo $pe['categoria']; ?></span>
                            <span class="badge bg-secondary"><?php echo $pe['tipo']; ?></span>
                        </div>
                        <span class="badge-estado badge-<?php echo strtolower(str_replace(' ', '-', $pe['estado'])); ?>">
                            <?php echo $pe['estado']; ?>
                        </span>
                    </div>
                    <div class="mt-1">
                        <small class="text-white">
                            <i class="bi bi-calendar3 me-1"></i><?php echo date('d/m/Y', strtotime($pe['fecha'])); ?>
                            <span class="mx-1">|</span>
                            <i class="bi bi-clock me-1"></i><?php echo $pe['hora_inicio']; ?>
                        </small>
                    </div>
                    <div class="mt-1">
                        <small class="text-white">
                            <i class="bi bi-person me-1"></i><?php echo $pe['entrenador_nombre']; ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- ===== FILTROS ===== -->
    <div class="filtros-container">
        <a href="index.php?View=entrenamientos&categoria=todas&estado=todos" 
           class="btn-filtro <?php echo ($categoria_filtro == 'todas' && $estado_filtro == 'todos') ? 'active' : ''; ?>">
            Todos
        </a>
        <?php foreach($categorias as $cat): ?>
        <a href="index.php?View=entrenamientos&categoria=<?php echo $cat; ?>&estado=<?php echo $estado_filtro; ?>" 
           class="btn-filtro <?php echo ($categoria_filtro == $cat) ? 'active' : ''; ?>">
            <?php echo $cat; ?>
        </a>
        <?php endforeach; ?>
        <span class="text-white mx-2">|</span>
        <a href="index.php?View=entrenamientos&categoria=<?php echo $categoria_filtro; ?>&estado=todos" 
           class="btn-filtro <?php echo ($estado_filtro == 'todos') ? 'active' : ''; ?>">
            Todos los estados
        </a>
        <a href="index.php?View=entrenamientos&categoria=<?php echo $categoria_filtro; ?>&estado=Programado" 
           class="btn-filtro <?php echo ($estado_filtro == 'Programado') ? 'active' : ''; ?>">
            Programados
        </a>
        <a href="index.php?View=entrenamientos&categoria=<?php echo $categoria_filtro; ?>&estado=Finalizado" 
           class="btn-filtro <?php echo ($estado_filtro == 'Finalizado') ? 'active' : ''; ?>">
            Finalizados
        </a>
    </div>

    <!-- ===== LISTA DE ENTRENAMIENTOS ===== -->
    <?php if(empty($entrenamientos_filtrados)): ?>
        <div class="text-center py-5">
            <i class="bi bi-dumbbell" style="font-size: 4rem; color: rgba(255,255,255,0.05);"></i>
            <h5 class="text-white mt-3">No hay entrenamientos</h5>
            <p class="text-white small">Crea un nuevo entrenamiento para comenzar</p>
        </div>
    <?php else: ?>
        <?php foreach($entrenamientos_filtrados as $e): 
            $asistencia = obtenerAsistenciaPorEntrenamiento($e['id'], $toda_la_asistencia);
            $asistentes = count(array_filter($asistencia, function($a) { return $a['asistio'] == 1; }));
            $porcentaje = $e['total_atletas'] > 0 ? round(($asistentes / $e['total_atletas']) * 100) : 0;
            
            $clase_progreso = 'progreso-alta';
            if($porcentaje < 50) $clase_progreso = 'progreso-baja';
            elseif($porcentaje < 75) $clase_progreso = 'progreso-media';
            
            $fecha = DateTime::createFromFormat('Y-m-d', $e['fecha']);
            $dia = $fecha ? $fecha->format('d') : '';
            $mes = $fecha ? $fecha->format('M') : '';
        ?>
        <div class="entrenamiento-card">
            <div class="header">
                <div class="info-principal">
                    <div class="fecha-box">
                        <span class="dia"><?php echo $dia; ?></span>
                        <span class="mes"><?php echo $mes; ?></span>
                    </div>
                    <div>
                        <h6 class="titulo"><?php echo $e['tipo']; ?> - <?php echo $e['categoria']; ?></h6>
                        <div class="subtitulo">
                            <span><i class="bi bi-clock me-1"></i><?php echo $e['hora_inicio']; ?> - <?php echo $e['hora_fin']; ?></span>
                            <span class="mx-2">|</span>
                            <span><i class="bi bi-geo-alt me-1"></i><?php echo $e['lugar']; ?></span>
                            <span class="mx-2">|</span>
                            <span><i class="bi bi-person me-1"></i><?php echo $e['entrenador_nombre']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge-estado badge-<?php echo strtolower(str_replace(' ', '-', $e['estado'])); ?>">
                        <?php echo $e['estado']; ?>
                    </span>
                    <div class="acciones">
                        
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEditarEntrenamiento<?php echo $e['id']; ?>">
                            <i class="bi bi-pencil"> Editar</i>
                        </button>
                        <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarAsistencia<?php echo $e['id']; ?>">
                            <i class="bi bi-person-check"> Asistencia</i>
                        </button>
                        
                    </div>
                </div>
            </div>
            
            <?php if($e['estado'] != 'Programado'): ?>
            <div class="asistencia-info">
                <span style="font-size:0.8rem;color:rgba(255,255,255,0.5);">
                    <i class="bi bi-people me-1"></i>
                    <?php echo $asistentes; ?>/<?php echo $e['total_atletas']; ?>
                </span>
                <div class="barra">
                    <div class="progreso <?php echo $clase_progreso; ?>" style="width: <?php echo $porcentaje; ?>%;"></div>
                </div>
                <span style="font-size:0.8rem;font-weight:600;color:#fff;">
                    <?php echo $porcentaje; ?>%
                </span>
            </div>
            <?php endif; ?>
            
            <?php if(!empty($e['descripcion'])): ?>
            <p class="small text-white mt-2 mb-0">
                <i class="bi bi-file-text me-1"></i>
                <?php echo $e['descripcion']; ?>
            </p>
            <?php endif; ?>
            
            <div class="entrenador-info">
                <i class="bi bi-person-badge me-1"></i>
                Entrenador: <?php echo $e['entrenador_nombre']; ?> (Cédula: <?php echo $e['entrenador_cedula']; ?>)
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

</div>

<!-- ===== MODALES ===== -->
<?php include './Public/Modals/modal_registrar_entrenamiento.php'; ?>
<?php include './Public/Modals/modal_editar_entrenamiento.php'; ?>
<?php include './Public/Modals/modal_registrar_asistencia.php'; ?>




