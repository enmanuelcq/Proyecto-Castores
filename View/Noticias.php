<?php
    require_once './Model/Atletas.php';
    require_once './Model/Noticias.php';
    require_once './Config/main.php';
    
    // Determinar el rol de la sesión
    $rol_usuario = isset($_SESSION['rol']) ? $_SESSION['rol'] : '';
    $es_secretaria = ($rol_usuario === "Secretaria");
    $es_entrenador = ($rol_usuario === "Entrenador");
    $es_admin = ($rol_usuario === "Administrador");
    
    // Obtener la URL actual sin el parámetro 'pagina'
    $url_actual = $_SERVER['REQUEST_URI'];
    // Eliminar el parámetro 'pagina' si existe
    $url_base = strtok($url_actual, '?');
    $parametros = $_GET;
    unset($parametros['pagina']);
    $query_string = http_build_query($parametros);
    $url_con_parametros = $url_base . ($query_string ? '?' . $query_string . '&' : '?');

    // ============ PAGINACIÓN ============
    // Número de partidos por página
    $partidos_por_pagina = 5;
    
    // Obtener el número de página actual
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($pagina_actual < 1) $pagina_actual = 1;
    
    // Calcular el total de partidos
    $total_partidos = count($todos_los_partidos);
    
    // Calcular el total de páginas
    $total_paginas = ceil($total_partidos / $partidos_por_pagina);
    
    // Calcular el índice de inicio
    $indice_inicio = ($pagina_actual - 1) * $partidos_por_pagina;
    
    // Obtener los partidos de la página actual
    $partidos_pagina = array_slice($todos_los_partidos, $indice_inicio, $partidos_por_pagina);
    
    // Determinar el rango de páginas a mostrar
    $rango_paginas = 2; // Número de páginas a mostrar a cada lado de la actual
    $pagina_inicio = max(1, $pagina_actual - $rango_paginas);
    $pagina_fin = min($total_paginas, $pagina_actual + $rango_paginas);
?>
<div class="container-img">
    <div class="container py-5">
        
        <!-- SECCIÓN DE NOTICIAS Y NOVEDADES -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-newspaper me-2 text-warning"></i>NOTICIAS Y NOVEDADES
                    </h2>
                    <p class="text-white-50 m-0">No te pierdas de nada</p>
                </div>
                <?php if($es_admin || $es_secretaria): ?>
                <div>
                    <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrearNoticia">
                        <i class="bi bi-plus-circle-fill me-2"></i>Crear Noticia
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <div class="row g-4">
                <!-- Noticia 1 - Destacada -->
                <div class="col-12">
                    <div class="noticia-horizontal-card p-4 position-relative overflow-hidden">
                        <!-- Badge de destacada -->
                        <span class="badge bg-danger position-absolute top-0 end-0 m-3 px-3 py-2">
                            <i class="bi bi-star-fill me-1"></i>Destacada
                        </span>
                        
                        <div class="row align-items-center g-4">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge bg-warning text-dark">📢 Anuncio</span>
                                    <span class="text-white small">
                                        <i class="bi bi-clock me-1"></i>Hace 2 días
                                    </span>
                                </div>
                                <h4 class="fw-bold mb-2">¡Súmate al Deportivo los Castores!</h4>
                                <p class="mb-3 text-white">
                                    Abriremos el proceso de captación de nuevos talentos para la temporada 2026. 
                                    Si naciste entre los años 2010 y 2016, ven a nuestras pruebas técnicas este 
                                    sábado en la cancha principal. ¡Cupos limitados por categoría!
                                </p>
                                <div class="d-flex gap-3 flex-wrap">
                                    <span class="text-white small">
                                        <i class="bi bi-calendar me-1"></i>15 Enero 2026
                                    </span>
                                    <span class="text-white small">
                                        <i class="bi bi-tag me-1"></i>Captación
                                    </span>
                                    <button class="btn btn-sm btn-outline-light rounded-pill px-3">
                                        Leer más <i class="bi bi-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                <div class="noticia-imagen-wrapper">
                                    <img src="Public/img/5.png" alt="Captación" class="img-noticia-vignette img-fluid rounded-3 shadow">
                                    <div class="noticia-overlay">
                                        <span class="badge bg-dark">Ver más</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Noticia 2 -->
                <div class="col-lg-6">
                    <div class="noticia-vertical-card p-3 h-100">
                        <div class="d-flex align-items-start gap-3">
                            <div class="noticia-icono">
                                <i class="bi bi-trophy-fill text-warning display-6"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-success">🏆 Torneo</span>
                                    <span class="text-white small">
                                        <i class="bi bi-clock me-1"></i>Hace 5 días
                                    </span>
                                </div>
                                <h5 class="fw-bold mb-1">A la gran final del Torneo de Distrito Capital</h5>
                                <p class="mb-2 small text-white">
                                    Nuestra categoría Sub-15 logró el pase a la final tras vencer 2-1 en un 
                                    emocionante partido. ¡Vamos por la copa este domingo!
                                </p>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-info">Sub-15</span>
                                    <span class="badge bg-secondary">Final</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Noticia 3 - Próximamente -->
                <div class="col-lg-6">
                    <div class="noticia-vertical-card p-3 h-100">
                        <div class="d-flex align-items-start gap-3">
                            <div class="noticia-icono">
                                <i class="bi bi-calendar-event text-primary display-6"></i>
                            </div>
                            <div>
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="badge bg-primary">📅 Evento</span>
                                    <span class="text-white small">
                                        <i class="bi bi-clock me-1"></i>Próximamente
                                    </span>
                                </div>
                                <h5 class="fw-bold mb-1">Clínica Deportiva de Verano</h5>
                                <p class="mb-2 small text-white">
                                    Del 15 al 30 de julio, nuestros entrenadores ofrecerán clínicas especializadas 
                                    para todas las categorías. ¡Inscripciones abiertas!
                                </p>
                                <div class="d-flex gap-2">
                                    <span class="badge bg-warning text-dark">¡Plazas limitadas!</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN DE TORNEOS Y ENCUENTROS -->
        <section>
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <h2 class="fw-bold mb-1">TORNEOS Y ENCUENTROS</h2>
                    <p class="text-white m-0">Mantente preparado para el siguiente</p>
                    <small class="text-white-50">
                        Mostrando <?php echo count($partidos_pagina); ?> de <?php echo $total_partidos; ?> partidos
                    </small>
                </div>
                <?php if($es_secretaria || $es_admin): ?>
                <div>
                    <button class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarPartido">
                        <i class="bi bi-plus-circle-fill me-2"></i>Registrar Partido
                    </button>
                </div>
                <?php endif; ?>
            </div>

            <div class="row g-3" id="listaPartidos">
                <?php if(count($partidos_pagina) > 0): ?>
                    <?php foreach($partidos_pagina as $partido):
                        $id = $partido['id'];
                        
                        // Determinar el estado del partido
                        $status = $partido['status'];
                        
                        // Clases de estado para los colores
                        $estado_clase = match($status) {
                            'Ganado' => 'ganado',
                            'Perdido' => 'perdido',
                            'Empatado' => 'empatado',
                            'Por Cargar' => 'por-cargar',
                            'Cancelado' => 'cancelado',
                            default => 'programado'
                        };
                        
                        // Formatear fecha
                        $fecha_obj = DateTime::createFromFormat('d/m/Y', $partido['fecha']);
                        $dia = $fecha_obj ? $fecha_obj->format('d') : '';
                        $mes_numero = $fecha_obj ? $fecha_obj->format('m') : '';
                        
                        // Nombre del mes
                        $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                        $mes_nombre = $mes_numero ? $meses[(int)$mes_numero - 1] : '';
                        
                        // Determinar qué modal mostrar al hacer clic en la tarjeta
                        $modal_principal = '';
                        if(in_array($status, ['Ganado', 'Perdido', 'Empatado'])) {
                            $modal_principal = 'modalVerPartidoFinalizado';
                        } elseif($status === 'Por Cargar') {
                            $modal_principal = 'modalVerPartidoPorCargar';
                        } else {
                            $modal_principal = 'modalVerPartidoPendiente';
                        }
                    ?>
                    <div class="col-12 partido-item" data-id="<?php echo $id; ?>">
                        <div class="torneo-list-card p-3 d-flex align-items-center justify-content-between flex-wrap gap-3" 
                             data-bs-toggle="modal" 
                             data-bs-target="#<?php echo $modal_principal . $id; ?>"
                             style="cursor: pointer;">
                            
                            <!-- Información del partido -->
                            <div class="d-flex align-items-center">
                                <div class="badge-fecha-box me-3">
                                    <span class="dia"><?php echo $dia; ?></span>
                                    <span class="mes"><?php echo $mes_nombre; ?></span>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        <?php echo $partido['tipo'] . ' — ' . $partido['rival']; ?>
                                    </h5>
                                    <div class="text-white small">
                                        <span class="me-3">
                                            <i class="bi bi-clock me-1"></i><?php echo $partido['hora']; ?>
                                        </span>
                                        <span>
                                            <i class="bi bi-geo-alt me-1"></i><?php echo $partido['estadio']; ?>
                                        </span>
                                        <span class="ms-3">
                                            <i class="bi bi-tag me-1"></i><?php echo $partido['categoria']; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Acciones y estado -->
                            <div class="d-flex align-items-center gap-3">
                                <span class="pill-estado <?php echo $estado_clase; ?>">
                                    <?php 
                                    $icono_estado = match($status) {
                                        'Ganado' => '🏆',
                                        'Perdido' => '❌',
                                        'Empatado' => '🤝',
                                        'Por Cargar' => '📝',
                                        'Cancelado' => '🚫',
                                        default => '⏳'
                                    };
                                    echo $icono_estado . ' ' . $status; 
                                    ?>
                                </span>
                                
                                <!-- Botón Cargar Resultado - SOLO para ENTRENADOR y ADMIN -->
                                <?php if(($es_entrenador || $es_admin) && $status === 'Por Cargar'): ?>
                                    <button class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalCargarResultado<?php echo $id; ?>"
                                            onclick="event.stopPropagation();">
                                        <i class="bi bi-pencil-square me-1"></i> Cargar Resultado
                                    </button>
                                <?php endif; ?>
                                
                                <!-- Botón de editar para Secretaria/Admin -->
                                <?php if($es_secretaria || $es_admin): ?>
                                    <button class="btn btn-info btn-sm rounded-pill px-3 fw-bold text-dark" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarPartido<?php echo $id; ?>"
                                            onclick="event.stopPropagation();">
                                        <i class="bi bi-pencil me-1"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- MODALES -->
                    <?php 
                    // Modal de Ver Partido Finalizado
                    include './Public/Modals/modalVerPartidoFinalizado.php';
                    
                    // Modal de Ver Partido Por Cargar
                    include './Public/Modals/modalVerPartidoPorCargar.php';
                    
                    // Modal de Ver Partido Pendiente
                    include './Public/Modals/modalVerPartidoPendiente.php';
                    
                    // Modal de Cargar Resultado
                    if($es_entrenador || $es_admin) {
                        include './Public/Modals/modalCargarResultado.php';
                    }
                    
                    // Modal de Editar
                    if($es_secretaria || $es_admin) {
                        include './Public/Modals/modalEditarPartido.php';
                    }
                    ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center">
                            <i class="bi bi-info-circle me-2"></i>
                            No hay partidos registrados
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- PAGINADOR -->
            <?php if($total_paginas > 1): ?>
            <div class="mt-4">
                <nav aria-label="Navegación de partidos">
                    <ul class="pagination justify-content-center pagination-custom">
                        <!-- Botón Anterior -->
                        <li class="page-item <?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo $url_con_parametros; ?>pagina=<?php echo $pagina_actual - 1; ?>" aria-label="Anterior">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        
                        <!-- Primera página -->
                        <?php if($pagina_inicio > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo $url_con_parametros; ?>pagina=1">1</a>
                            </li>
                            <?php if($pagina_inicio > 2): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                        
                        <!-- Páginas del rango -->
                        <?php for($i = $pagina_inicio; $i <= $pagina_fin; $i++): ?>
                            <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo $url_con_parametros; ?>pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <!-- Última página -->
                        <?php if($pagina_fin < $total_paginas): ?>
                            <?php if($pagina_fin < $total_paginas - 1): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            <?php endif; ?>
                            <li class="page-item">
                                <a class="page-link" href="<?php echo $url_con_parametros; ?>pagina=<?php echo $total_paginas; ?>"><?php echo $total_paginas; ?></a>
                            </li>
                        <?php endif; ?>
                        
                        <!-- Botón Siguiente -->
                        <li class="page-item <?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo $url_con_parametros; ?>pagina=<?php echo $pagina_actual + 1; ?>" aria-label="Siguiente">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                
                <!-- Información de paginación -->
                <div class="text-center text-white-50 small mt-2">
                    Página <?php echo $pagina_actual; ?> de <?php echo $total_paginas; ?> 
                    | Mostrando <?php echo count($partidos_pagina); ?> de <?php echo $total_partidos; ?> partidos
                </div>
            </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<!-- Modal Registrar Partido -->
<?php include './Public/Modals/modalRegistrarPartido.php'; ?>
<!-- Modal Crear Noticia -->
<?php include './Public/Modals/modalCrearNoticia.php'; ?>
<script src="./Public/Js/Noticias.js"></script>



