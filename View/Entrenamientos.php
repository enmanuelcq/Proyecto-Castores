<?php
// ============================================
// /View/Entrenamientos.php
// VISTA PRINCIPAL DE ENTRENAMIENTOS
// La carga de datos se delega al controlador; aquí solo se presenta.
// ============================================

$root = dirname(__DIR__);
require_once $root . '/Controller/EntrenamientosController.php';
require_once $root . '/Model/Entrenamiento.php';
require_once $root . '/Model/Asistencias.php';


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

// Obtener categorías únicas para el filtro (provistas por el controlador)
if (!isset($todas_las_categorias) || !is_array($todas_las_categorias)) {
    $todas_las_categorias = [];
}

// ===== PAGINACIÓN =====
$por_pagina = 5;
$pagina_actual = isset($_GET['pagina_entrenamientos']) ? max(1, intval($_GET['pagina_entrenamientos'])) : 1;
$total_registros = count($entrenamientos_filtrados);
$total_paginas = max(1, ceil($total_registros / $por_pagina));
if ($pagina_actual > $total_paginas) $pagina_actual = $total_paginas;
$offset = ($pagina_actual - 1) * $por_pagina;
$entrenamientos_pagina = array_slice($entrenamientos_filtrados, $offset, $por_pagina);

// Función para construir URLs con parámetros preservando filtros
function urlEntrenamientos($params_extra = []) {
    $base = 'index.php?View=entrenamientos';
    $categoria_filtro = $_GET['categoria'] ?? 'todas';
    $estado_filtro = $_GET['estado'] ?? 'todos';
    $params = [
        'categoria' => $categoria_filtro,
        'estado' => $estado_filtro,
    ];
    $params = array_merge($params, $params_extra);
    foreach ($params as $k => $v) {
        $base .= '&' . $k . '=' . urlencode($v);
    }
    return $base;
}

// Estadísticas del entrenador
$cedula_entrenador = $_SESSION['usuario']['cedula'] ?? '';
$estadisticas_entrenador = obtenerEstadisticasEntrenador($cedula_entrenador, $todos_los_entrenamientos);

// Calcular el promedio de asistencia directamente en la vista
$total_asistentes_real = 0;
$total_atletas_real = 0;

if (!empty($todos_los_entrenamientos) && !empty($toda_la_asistencia)) {
    foreach ($todos_los_entrenamientos as $ent) {
        if ($ent['estado'] != 'Programado') {
            $asistencias_ent = array_filter($toda_la_asistencia, function($a) use ($ent) {
                return $a['entrenamiento_id'] == $ent['id'];
            });
            $asistentes = count(array_filter($asistencias_ent, function($a) {
                return $a['asistio'] == 1;
            }));
            $total_asistentes_real += $asistentes;
            $total_atletas_real += max($ent['total_atletas'], count($asistencias_ent));
        }
    }
}

$promedio_asistencia_global = ($total_atletas_real > 0) ? round(($total_asistentes_real / $total_atletas_real) * 100) : 0;

$estadisticas_entrenador['promedio_asistencia'] = $promedio_asistencia_global;
$estadisticas_entrenador['total_asistentes'] = $total_asistentes_real;
$estadisticas_entrenador['total_atletas'] = $total_atletas_real;

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
                <small class="text-black-70 d-block" style="font-size: 0.65rem;">
                    <?php echo $estadisticas_entrenador['total_asistentes']; ?> asistentes / <?php echo $estadisticas_entrenador['total_atletas']; ?> convocados
                </small>
            </div>
        </div>
    </div>

    <!-- ===== PRÓXIMOS ENTRENAMIENTOS ===== -->
    <?php if(!empty($proximos_entrenamientos)): ?>
    <div class="mb-4">
        <h6 class="mb-2" style="color: #111827;">
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
                        <small class="text-black-70">
                            <i class="bi bi-calendar3 me-1"></i><?php echo date('d/m/Y', strtotime($pe['fecha'])); ?>
                            <span class="mx-1">|</span>
                            <i class="bi bi-clock me-1"></i><?php echo $pe['hora_inicio']; ?>
                        </small>
                    </div>
                    <div class="mt-1">
                        <small class="text-black-70">
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
    <?php
    $categorias_disponibles = ['Sub-4', 'Sub-6', 'Sub-8', 'Sub-10', 'Sub-12', 'Sub-14', 'Sub-16', 'Sub-18'];
    ?>
    <div class="filtros-container">
        <select class="form-select filtro-select" onchange="if(this.value) window.location.href=this.value;">
            <option value="<?php echo urlEntrenamientos(['categoria' => 'todas', 'estado' => $estado_filtro, 'pagina_entrenamientos' => 1]); ?>" <?php echo ($categoria_filtro == 'todas') ? 'selected' : ''; ?>>
                Todas las categorías
            </option>
            <?php foreach($categorias_disponibles as $cat): ?>
            <option value="<?php echo urlEntrenamientos(['categoria' => $cat, 'estado' => $estado_filtro, 'pagina_entrenamientos' => 1]); ?>" <?php echo ($categoria_filtro == $cat) ? 'selected' : ''; ?>>
                <?php echo $cat; ?>
            </option>
            <?php endforeach; ?>
        </select>
        <select class="form-select filtro-select" onchange="if(this.value) window.location.href=this.value;">
            <option value="<?php echo urlEntrenamientos(['estado' => 'todos', 'categoria' => $categoria_filtro, 'pagina_entrenamientos' => 1]); ?>" <?php echo ($estado_filtro == 'todos') ? 'selected' : ''; ?>>
                Todos los estados
            </option>
            <option value="<?php echo urlEntrenamientos(['estado' => 'Programado', 'categoria' => $categoria_filtro, 'pagina_entrenamientos' => 1]); ?>" <?php echo ($estado_filtro == 'Programado') ? 'selected' : ''; ?>>
                Programados
            </option>
            <option value="<?php echo urlEntrenamientos(['estado' => 'En curso', 'categoria' => $categoria_filtro, 'pagina_entrenamientos' => 1]); ?>" <?php echo ($estado_filtro == 'En curso') ? 'selected' : ''; ?>>
                En Curso
            </option>
            <option value="<?php echo urlEntrenamientos(['estado' => 'Finalizado', 'categoria' => $categoria_filtro, 'pagina_entrenamientos' => 1]); ?>" <?php echo ($estado_filtro == 'Finalizado') ? 'selected' : ''; ?>>
                Finalizados
            </option>
        </select>
    </div>

    <!-- ===== LISTA DE ENTRENAMIENTOS ===== -->
    <?php if(empty($entrenamientos_pagina)): ?>
        <div class="text-center py-5">
            <i class="bi bi-dumbbell" style="font-size: 4rem; color: rgba(255,255,255,0.05);"></i>
            <h5 class="text-white mt-3">No hay entrenamientos</h5>
            <p class="text-white small">Crea un nuevo entrenamiento para comenzar</p>
        </div>
    <?php else: ?>
        <?php foreach($entrenamientos_pagina as $e): 
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
                        <h6 class="titulo"><?php echo $e['tipo']; ?> - <?php echo implode(', ', $e['categorias'] ?: [$e['categoria']]); ?></h6>
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
                <span style="font-size:0.8rem;color:rgba(0, 0, 0, 0.88);">
                    <i class="bi bi-people me-1"></i>
                    <?php echo $asistentes; ?>/<?php echo $e['total_atletas']; ?>
                </span>
                <div class="barra">
                    <div class="progreso <?php echo $clase_progreso; ?>" style="width: <?php echo $porcentaje; ?>%;"></div>
                </div>
                <span style="font-size:0.8rem;font-weight:600;">
                    <?php echo $porcentaje; ?>%
                </span>
            </div>
            <?php endif; ?>
            
            <?php if(!empty($e['descripcion'])): ?>
            <p class="small text-black-70 mt-2 mb-0">
                <i class="bi bi-file-text me-1"></i>
                <?php echo $e['descripcion']; ?>
            </p>
            <?php endif; ?>

            <?php if(!empty($e['recursos'])): ?>
            <button class="btn btn-outline-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#modalVerRecursos<?php echo $e['id']; ?>">
                <i class="bi bi-paperclip me-1"></i>Ver recursos (<?php echo count($e['recursos']); ?>)
            </button>
            <?php endif; ?>
            
            <div class="entrenador-info">
                <i class="bi bi-person-badge me-1"></i>
                Entrenador: <?php echo $e['entrenador_nombre']; ?> (Cédula: <?php echo $e['entrenador_cedula']; ?>)
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- ===== PAGINACIÓN ===== -->
    <?php if($total_paginas > 1): ?>
    <div class="mt-4">
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo urlEntrenamientos(['pagina_entrenamientos' => max(1, $pagina_actual - 1)]); ?>">
                        <i class="bi bi-chevron-left"></i>
                    </a>
                </li>
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo urlEntrenamientos(['pagina_entrenamientos' => $i]); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo urlEntrenamientos(['pagina_entrenamientos' => min($total_paginas, $pagina_actual + 1)]); ?>">
                        <i class="bi bi-chevron-right"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="text-center text-muted small">
            Mostrando <?php echo $offset + 1; ?>–<?php echo min($offset + $por_pagina, $total_registros); ?> de <?php echo $total_registros; ?> entrenamientos
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- ===== MODALES ===== -->
<?php include './Public/Modals/modal_registrar_entrenamiento.php'; ?>
<?php include './Public/Modals/modal_editar_entrenamiento.php'; ?>
<?php include './Public/Modals/modal_registrar_asistencia.php'; ?>
<?php include './Public/Modals/modal_ver_recursos.php'; ?>

<script src="./Public/Js/Entrenamientos.js"></script>
