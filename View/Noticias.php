<?php
$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Controller/NoticiasController.php';
require_once $root . '/Controller/PartidosController.php';
require_once $root . '/Model/Repository/RepositoryGol.php';
require_once $root . '/Model/Repository/RepositoryPartidoTarjeta.php';
require_once $root . '/Model/Repository/RepositoryCambio.php';
require_once $root . '/Model/Repository/RepositoryIncidencia.php';

$repoGol = new RepositoryGol();
$repoPartidoTarjeta = new RepositoryPartidoTarjeta();
$repoIncidencia = new RepositoryIncidencia();
$repoCambio = new RepositoryCambio();

$rol_usuario = $_SESSION['usuario']['id_rol'] ?? '';
$es_secretaria = ($rol_usuario == 2);
$es_admin = ($rol_usuario == 1);
$es_entrenador = ($rol_usuario == 3);

function urlConParametros($params = []) {
    $url = $_SERVER['SCRIPT_NAME'];
    $query = array_merge($_GET, $params);
    unset($query['pagina_noticias']);
    unset($query['pagina_partidos']);
    unset($query['tab']);
    return $url . '?' . http_build_query($query);
}

$tabActiva = $_GET['tab'] ?? 'noticias';
?>

<link rel="stylesheet" href="Public/css/Noticias.css">

<div class="container py-4">

    <!-- ===== BARRA DE FILTRO TABS ===== -->
    <div class="noticias-tabs-bar d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="section-icon-box">
                <i class="bi bi-grid-3x3-gap-fill"></i>
            </div>
            <div>
                <h1 class="section-main-title mb-0">Academia Los Castores</h1>
                <p class="section-subtitle mb-0">Noticias, partidos y novedades</p>
            </div>
        </div>

        <div class="d-flex gap-2">
            <?php if($es_admin || $es_secretaria): ?>
            <button class="btn btn-sm-accent btn-round-sm" data-bs-toggle="modal" data-bs-target="#modalCrearNoticia">
                <i class="bi bi-plus-lg me-1"></i>Nueva Noticia
            </button>
            <?php endif; ?>
            <?php if($es_secretaria || $es_admin): ?>
            <button class="btn btn-sm-accent btn-round-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarPartido">
                <i class="bi bi-plus-lg me-1"></i>Registrar Partido
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- ===== NAV PILLS ===== -->
    <div class="nav-pills-container mb-4">
        <button class="nav-pill active" data-target="panel-noticias" onclick="switchTab(this, 'panel-noticias')">
            <i class="bi bi-newspaper me-2"></i>Noticias
        </button>
        <button class="nav-pill" data-target="panel-partidos" onclick="switchTab(this, 'panel-partidos')">
            <i class="bi bi-trophy me-2"></i>Partidos
        </button>
    </div>

    <!-- Mensajes de éxito/error -->
    <?php if(isset($_GET['success']) && isset($_GET['mensaje'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?php echo htmlspecialchars($_GET['mensaje']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    
    <?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?php echo htmlspecialchars($_GET['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if(isset($_GET['mensaje'])): ?>
    <script>
        setTimeout(function() {
            window.location.href = "index.php?View=Noticias";
        }, 1500);
    </script>
    <?php endif; ?>

    <!-- ============================================================
    PANEL: NOTICIAS (ESTILO NEWSPAPER)
    ============================================================ -->
    <div id="panel-noticias" class="tab-panel <?php echo $tabActiva === 'partidos' ? 'd-none' : ''; ?>">

        <!-- --- Headline / Noticia Destacada --- -->
        <?php if ($noticiaDestacada instanceof Noticia): ?>
        <div class="headline mb-5">
            <div class="headline-inner position-relative overflow-hidden">
                <?php if ($noticiaDestacada->getImagen()): ?>
                <div class="headline-img-bg">
                    <img src="<?php echo htmlspecialchars($noticiaDestacada->getImagen()); ?>" 
                         alt="" class="headline-img">
                    <div class="headline-overlay"></div>
                </div>
                <?php endif; ?>
                <div class="headline-content">
                    <div class="headline-meta d-flex align-items-center gap-3 mb-3">
                        <span class="headline-badge"><i class="bi bi-star-fill me-1"></i>Destacada</span>
                        <span class="headline-date">
                            <i class="bi bi-clock me-1"></i>
                            <?php 
                            setlocale(LC_TIME, 'es_ES.UTF-8', 'Spanish_Spain.1252');
                            $ts = strtotime($noticiaDestacada->getFechaPublicacion());
                            echo strftime('%d %b %Y · %H:%M', $ts);
                            ?>
                        </span>
                    </div>
                    <h1 class="headline-title"><?php echo htmlspecialchars($noticiaDestacada->getTitulo()); ?></h1>
                    <p class="headline-excerpt"><?php echo htmlspecialchars(substr($noticiaDestacada->getContenido(), 0, 400)); ?></p>
                    <div class="d-flex gap-2 flex-wrap mt-3">
                        <button class="btn btn-headline-read" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalVerNoticia<?php echo $noticiaDestacada->getId(); ?>">
                            Leer artículo <i class="bi bi-arrow-right ms-1"></i>
                        </button>
                        <?php if($es_admin || $es_secretaria): ?>
                        <button class="btn btn-headline-edit" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEditarNoticia<?php echo $noticiaDestacada->getId(); ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-headline-delete btn-eliminar" 
                                data-id="<?php echo $noticiaDestacada->getId(); ?>"
                                data-titulo="<?php echo htmlspecialchars($noticiaDestacada->getTitulo()); ?>">
                            <i class="bi bi-trash"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php 
        $noticia = $noticiaDestacada;
        include './Public/Modals/modalVerNoticia.php'; 
        include './Public/Modals/modalEditarNoticia.php'; 
        ?>
        <?php endif; ?>

        <!-- --- Separador de columna --- -->
        <div class="section-divider mb-4">
            <span class="section-divider-label">Últimas noticias</span>
        </div>

        <!-- --- Grid de noticias --- -->
        <div id="noticias-content">
        <?php if (empty($noticias) && !$noticiaDestacada): ?>
        <div class="empty-state text-center py-5">
            <div class="empty-state-icon">
                <i class="bi bi-newspaper"></i>
            </div>
            <h4 class="fw-bold mt-3 mb-1">Sin noticias aún</h4>
            <p class="text-muted mb-0">No hay noticias publicadas. Sé el primero en compartir una.</p>
        </div>
        <?php elseif (!empty($noticias)): ?>
        <div class="row g-4">
            <?php foreach ($noticias as $noticia): ?>
            <?php if ($noticia instanceof Noticia): ?>
            <div class="col-lg-3 col-md-6">
                <div class="news-col-card h-100 d-flex flex-column">
                    <?php if ($noticia->getImagen()): ?>
                    <div class="news-col-img-wrap">
                        <img src="<?php echo htmlspecialchars($noticia->getImagen()); ?>" 
                             class="news-col-img" 
                             alt="<?php echo htmlspecialchars($noticia->getTitulo()); ?>">
                    </div>
                    <?php else: ?>
                    <div class="news-col-img-placeholder">
                        <i class="bi bi-image"></i>
                    </div>
                    <?php endif; ?>
                    <div class="news-col-body d-flex flex-column flex-grow-1">
                        <div class="news-col-date mb-2">
                            <?php echo date('d/m/Y', strtotime($noticia->getFechaPublicacion())); ?>
                        </div>
                        <h6 class="news-col-title"><?php echo htmlspecialchars($noticia->getTitulo()); ?></h6>
                        <p class="news-col-excerpt flex-grow-1"><?php echo htmlspecialchars(substr($noticia->getContenido(), 0, 110)); ?>…</p>
                        <div class="news-col-actions mt-auto">
                            <button class="news-col-read-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalVerNoticia<?php echo $noticia->getId(); ?>">
                                Leer más <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                            <?php if($es_admin || $es_secretaria): ?>
                            <div class="news-col-admin mt-2 d-flex gap-1">
                                <button class="btn-admin-sm btn-admin-edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditarNoticia<?php echo $noticia->getId(); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn-admin-sm btn-admin-delete btn-eliminar" 
                                        data-id="<?php echo $noticia->getId(); ?>"
                                        data-titulo="<?php echo htmlspecialchars($noticia->getTitulo()); ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php include './Public/Modals/modalVerNoticia.php'; ?>
            <?php include './Public/Modals/modalEditarNoticia.php'; ?>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($paginacionNoticias['total_paginas'] > 1): ?>
        <div class="mt-4">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($paginaNoticias <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link ajax-page" data-type="noticias" data-page="<?php echo $paginaNoticias - 1; ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $paginacionNoticias['total_paginas']; $i++): ?>
                    <li class="page-item <?php echo ($i == $paginaNoticias) ? 'active' : ''; ?>">
                        <a class="page-link ajax-page" data-type="noticias" data-page="<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($paginaNoticias >= $paginacionNoticias['total_paginas']) ? 'disabled' : ''; ?>">
                        <a class="page-link ajax-page" data-type="noticias" data-page="<?php echo $paginaNoticias + 1; ?>">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="text-center text-muted small">
                Mostrando <?php echo $offsetNoticias + 1; ?>–<?php echo min($offsetNoticias + $noticiasPorPagina, $paginacionNoticias['total_registros']); ?> de <?php echo $paginacionNoticias['total_registros']; ?> noticias
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        </div>
    </div>

    <!-- ============================================================
    PANEL: PARTIDOS (ESTILO DASHBOARD)
    ============================================================ -->
    <div id="panel-partidos" class="tab-panel <?php echo $tabActiva === 'noticias' ? 'd-none' : ''; ?>">

        <!-- --- Resumen rapido --- -->
        <?php
        $totalPartidos = count($partidos);
        $finalizados = 0;
        $pendientes = 0;
        $porCargar = 0;
        foreach ($partidos as $p) {
            $ev = obtenerEstadoPartido($p);
            if ($ev === 'Finalizado') $finalizados++;
            elseif ($ev === 'Por Cargar') $porCargar++;
            else $pendientes++;
        }
        ?>
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <button class="dash-stat-card stat-total stat-filter-btn active" data-filter="todos" onclick="filtrarPartidos(this, 'todos')">
                    <div class="dash-stat-icon"><i class="bi bi-calendar3"></i></div>
                    <div class="dash-stat-value"><?php echo $totalPartidos; ?></div>
                    <div class="dash-stat-label">Total partidos</div>
                </button>
            </div>
            <div class="col-md-3 col-6">
                <button class="dash-stat-card stat-pendiente stat-filter-btn" data-filter="pendiente" onclick="filtrarPartidos(this, 'pendiente')">
                    <div class="dash-stat-icon"><i class="bi bi-calendar-event"></i></div>
                    <div class="dash-stat-value"><?php echo $pendientes; ?></div>
                    <div class="dash-stat-label">Pendientes</div>
                </button>
            </div>
            <div class="col-md-3 col-6">
                <button class="dash-stat-card stat-por-cargar stat-filter-btn" data-filter="por-cargar" onclick="filtrarPartidos(this, 'por-cargar')">
                    <div class="dash-stat-icon"><i class="bi bi-pencil-square"></i></div>
                    <div class="dash-stat-value"><?php echo $porCargar; ?></div>
                    <div class="dash-stat-label">Por cargar</div>
                </button>
            </div>
            <div class="col-md-3 col-6">
                <button class="dash-stat-card stat-finalizado stat-filter-btn" data-filter="finalizado" onclick="filtrarPartidos(this, 'finalizado')">
                    <div class="dash-stat-icon"><i class="bi bi-check-circle"></i></div>
                    <div class="dash-stat-value"><?php echo $finalizados; ?></div>
                    <div class="dash-stat-label">Finalizados</div>
                </button>
            </div>
        </div>

        <div id="partidos-filter-msg" class="d-none text-center text-muted py-3"></div>

        <!-- --- Filtro por categoría --- -->
        <div class="cat-filter-bar mb-3">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="cat-filter-label"><i class="bi bi-funnel me-1"></i>Categoría:</span>
                <div class="cat-filter-pills">
                    <button class="cat-pill active" onclick="filtrarPorCategoria(this, 'todas')">Todas</button>
                    <?php foreach ($categorias as $cat): ?>
                    <button class="cat-pill" onclick="filtrarPorCategoria(this, '<?php echo $cat->getId(); ?>')">
                        <?php echo htmlspecialchars($cat->getSubCategoria()); ?>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div id="partidos-content">
        <?php if (empty($partidos)): ?>
        <div class="empty-state text-center py-5">
            <div class="empty-state-icon empty-state-icon-green">
                <i class="bi bi-trophy"></i>
            </div>
            <h4 class="fw-bold mt-3 mb-1">Sin partidos registrados</h4>
            <p class="text-muted mb-0">Registra el primer partido de la temporada.</p>
        </div>
        <?php else: ?>
        <div class="row g-3">
            <?php foreach ($partidos as $partido): 
                $estadoVisual = obtenerEstadoPartido($partido);
                $estado_clase = match($estadoVisual) {
                    'Finalizado' => 'finalizado',
                    'Por Cargar' => 'por-cargar',
                    default => 'pendiente'
                };
                $icono_estado = match($estadoVisual) {
                    'Finalizado' => 'bi-check-circle-fill',
                    'Por Cargar' => 'bi-pencil-square',
                    default => 'bi-calendar-event'
                };

                $goleadores = $repoGol->findByPartido($partido['partido_id']);
                $tarjetas = $repoPartidoTarjeta->findByPartido($partido['partido_id']);
                $incidencias = $repoIncidencia->findByPartido($partido['partido_id']);
                $cambios = $repoCambio->findByPartido($partido['partido_id']);
                
                $fecha_obj = new DateTime($partido['fecha_partido']);
                $dia = $fecha_obj->format('d');
                $meses = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
                $mes_nombre = $meses[(int)$fecha_obj->format('m') - 1];
                $diaSemana = ['Dom','Lun','Mar','Mié','Jue','Vie','Sáb'][(int)$fecha_obj->format('w')];
                
                $modalVer = ($estadoVisual === 'Finalizado') ? 'modalVerPartidoFinalizado' : 'modalVerPartidoPendiente';

                $convocados = $repoConvocado->findByPartido($partido['partido_id']);
                $convocadoIds = $repoConvocado->findIdsByPartido($partido['partido_id']);
                $numConvocados = count($convocados);

                $catsElegibles = array_filter([
                    (int)$partido['categoria_id'],
                    (int)$partido['categoria_id'] - 1,
                    (int)$partido['categoria_id'] - 2
                ], fn($c) => $c >= 1);
                $atletasCategoria = $repoAtleta->findByCategorias($catsElegibles, (int)$partido['categoria_id']);
                $catAtletaIds = array_map(fn($a) => $a->getAtletaId(), $atletasCategoria);
                $mapaCatAtletas = $repoAtleta->mapaCategorias($catAtletaIds);
            ?>
            <div class="col-12 partido-item" data-id="<?php echo $partido['partido_id']; ?>" data-status="<?php echo $estado_clase; ?>" data-categoria="<?php echo $partido['categoria_id']; ?>">
                <div class="dash-match-card"
                     data-bs-toggle="modal" 
                     data-bs-target="#<?php echo $modalVer . $partido['partido_id']; ?>"
                     style="cursor: pointer;">
                    
                    <div class="dash-match-left">
                        <div class="dash-match-datebox">
                            <span class="dash-match-day"><?php echo $dia; ?></span>
                            <span class="dash-match-month"><?php echo $mes_nombre; ?></span>
                            <span class="dash-match-weekday"><?php echo $diaSemana; ?></span>
                        </div>
                        <div class="dash-match-separator"></div>
                        <div class="dash-match-info">
                            <div class="dash-match-cat">
                                <?php echo htmlspecialchars($partido['categoria_nombre'] ?? 'Sin categoría'); ?>
                            </div>
                            <div class="dash-match-rival">
                                vs <?php echo htmlspecialchars($partido['equipo_rival'] ?? 'Rival'); ?>
                            </div>
                            <div class="dash-match-meta">
                                <span><i class="bi bi-clock me-1"></i><?php echo date('H:i', strtotime($partido['fecha_partido'])); ?></span>
                                <span><i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($partido['campo_nombre'] ?? 'Sin campo'); ?></span>
                                <?php if (!empty($partido['tipo_partido']) && $partido['tipo_partido'] !== 'Amistoso'): ?>
                                <span class="dash-match-type"><?php echo htmlspecialchars($partido['tipo_partido']); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="dash-match-right d-flex align-items-center gap-2">
                        <span class="dash-status-pill <?php echo $estado_clase; ?>">
                            <i class="<?php echo $icono_estado; ?> me-1"></i><?php echo $estadoVisual; ?>
                        </span>
                        
                        <?php if(($es_entrenador || $es_admin) && $estadoVisual === 'Por Cargar'): ?>
                        <button class="btn btn-sm-accent btn-round-sm" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalCargarResultado<?php echo $partido['partido_id']; ?>"
                                onclick="event.stopPropagation();">
                            <i class="bi bi-pencil-square me-1"></i>Cargar
                        </button>
                        <?php endif; ?>
                        
                        <?php if($es_secretaria || $es_admin): ?>
                        <button class="btn-admin-sm btn-admin-edit" 
                                data-bs-toggle="modal" 
                                data-bs-target="#modalEditarPartido<?php echo $partido['partido_id']; ?>"
                                onclick="event.stopPropagation();">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn-admin-sm btn-admin-delete" 
                                onclick="event.stopPropagation(); if(confirm('¿Eliminar este partido?')) window.location.href='Controller/EliminarPartidoController.php?id=<?php echo $partido['partido_id']; ?>'">
                            <i class="bi bi-trash"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <?php 
            if ($estadoVisual === 'Por Cargar') {
                $tiposTarjetas = $repoTarjeta->findAll();
            }
            if ($estadoVisual === 'Finalizado') {
                include './Public/Modals/modalVerPartidoFinalizado.php';
            } else {
                include './Public/Modals/modalVerPartidoPendiente.php';
            }
            ?>
            <?php include './Public/Modals/modalEditarPartido.php'; ?>
            <?php include './Public/Modals/modalCargarResultado.php'; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($paginacionPartidos['total_paginas'] > 1): ?>
        <div class="mt-4">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($paginaPartidos <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link ajax-page" data-type="partidos" data-page="<?php echo $paginaPartidos - 1; ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $paginacionPartidos['total_paginas']; $i++): ?>
                    <li class="page-item <?php echo ($i == $paginaPartidos) ? 'active' : ''; ?>">
                        <a class="page-link ajax-page" data-type="partidos" data-page="<?php echo $i; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($paginaPartidos >= $paginacionPartidos['total_paginas']) ? 'disabled' : ''; ?>">
                        <a class="page-link ajax-page" data-type="partidos" data-page="<?php echo $paginaPartidos + 1; ?>">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="text-center text-muted small">
                Mostrando <?php echo $offsetPartidos + 1; ?>–<?php echo min($offsetPartidos + $partidosPorPagina, $paginacionPartidos['total_registros']); ?> de <?php echo $paginacionPartidos['total_registros']; ?> partidos
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        </div>
    </div>

</div>

<?php include './Public/Modals/modalCrearNoticia.php'; ?>
<?php include './Public/Modals/modalRegistrarPartido.php'; ?>

<script>
var filtroEstado = 'todos';
var filtroCategoria = 'todas';

function switchTab(btn, panelId) {
    document.querySelectorAll('.nav-pill').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.tab-panel').forEach(p => {
        p.classList.add('d-none');
    });
    document.getElementById(panelId).classList.remove('d-none');
    var otherPanel = panelId === 'panel-noticias' ? 'panel-partidos' : 'panel-noticias';
    document.getElementById(otherPanel).classList.add('d-none');
    history.replaceState(null, '', '?View=Noticias&tab=' + (panelId === 'panel-noticias' ? 'noticias' : 'partidos'));
}

function aplicarFiltros() {
    document.querySelectorAll('.partido-item').forEach(function(item) {
        var matchEstado = (filtroEstado === 'todos') || (item.getAttribute('data-status') === filtroEstado);
        var matchCat = (filtroCategoria === 'todas') || (item.getAttribute('data-categoria') === filtroCategoria);
        if (matchEstado && matchCat) {
            item.style.display = '';
            item.style.opacity = '0';
            requestAnimationFrame(() => {
                item.style.opacity = '1';
                item.style.transition = 'opacity 0.3s ease';
            });
        } else {
            item.style.display = 'none';
        }
    });

    var visible = document.querySelectorAll('.partido-item:not([style*="display: none"])').length;
    var msg = document.getElementById('partidos-filter-msg');
    if (visible === 0) {
        msg.textContent = 'No hay partidos que coincidan con los filtros seleccionados.';
        msg.classList.remove('d-none');
    } else {
        msg.classList.add('d-none');
    }
}

function filtrarPartidos(btn, filtro) {
    document.querySelectorAll('.stat-filter-btn').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    filtroEstado = filtro;
    aplicarFiltros();
}

function filtrarPorCategoria(btn, catId) {
    document.querySelectorAll('.cat-pill').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    filtroCategoria = catId;
    aplicarFiltros();
}

function toggleConvocado(btn, partidoId, atletaId) {
    var isConvocado = btn.classList.contains('convocado-active');
    var action = isConvocado ? 'desconvocar' : 'convocar';

    var fd = new FormData();
    fd.append('action', action);
    fd.append('partido_id', partidoId);
    fd.append('atleta_id', atletaId);

    fetch('Controller/ConvocarJugadores_controller.php', {
        method: 'POST',
        body: fd
    })
    .then(r => r.json())
    .then(data => {
        if (data.ok) {
            btn.classList.toggle('convocado-active');
            var icon = btn.querySelector('i');
            if (action === 'convocar') {
                icon.className = 'bi bi-check-circle-fill';
                btn.closest('.convocado-row').classList.add('ya-convocado');
            } else {
                icon.className = 'bi bi-plus-circle';
                btn.closest('.convocado-row').classList.remove('ya-convocado');
            }
            actualizarContadorConvocados(partidoId);
        } else {
            alert(data.msg);
        }
    })
    .catch(() => alert('Error de conexión'));
}

function actualizarContadorConvocados(partidoId) {
    var container = document.getElementById('convocadosContainer_' + partidoId);
    if (!container) return;
    var activos = container.querySelectorAll('.convocado-active').length;
    var counter = document.getElementById('convocadoCount_' + partidoId);
    if (counter) counter.textContent = activos;
}

var ajaxPageCache = {};

function ajaxPaginate(el) {
    var type = el.getAttribute('data-type');
    var page = parseInt(el.getAttribute('data-page'));
    if (page < 1 || el.closest('.disabled')) return;
    var container = document.getElementById(type + '-content');
    if (!container) return;

    var url = 'Controller/AjaxPaginacion.php?type=' + type + '&page=' + page;
    if (type === 'atletas') {
        url += '&buscar=' + (el.getAttribute('data-buscar') || '');
        url += '&categoria=' + (el.getAttribute('data-categoria') || '0');
        url += '&estado=' + (el.getAttribute('data-estado') || 'activos');
    }

    container.style.opacity = '0.5';
    container.style.transition = 'opacity 0.2s';

    fetch(url)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.ok) {
                container.innerHTML = data.html;
                container.style.opacity = '1';
                container.querySelectorAll('.ajax-page').forEach(function(link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        ajaxPaginate(this);
                    });
                });
                container.querySelectorAll('.btn-eliminar').forEach(function(btn) {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        var id = this.getAttribute('data-id');
                        var titulo = this.getAttribute('data-titulo');
                        if (confirm('¿Eliminar la noticia "' + titulo + '"?')) {
                            window.location.href = 'Controller/EliminarNoticiaController.php?id=' + id;
                        }
                    });
                });
                if (typeof ajustarAlturas === 'function') ajustarAlturas();
            }
        })
        .catch(function() {
            container.style.opacity = '1';
        });
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.ajax-page').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            ajaxPaginate(this);
        });
    });
    document.querySelectorAll('.btn-eliminar').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var id = this.getAttribute('data-id');
            var titulo = this.getAttribute('data-titulo');
            if (confirm('¿Eliminar la noticia "' + titulo + '"? Esta acción no se puede deshacer.')) {
                window.location.href = 'Controller/EliminarNoticiaController.php?id=' + id;
            }
        });
    });
});
</script>
