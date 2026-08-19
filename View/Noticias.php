<?php
// ============================================================
// 1. INCLUIR CONTROLADORES SEPARADOS
// ============================================================
$root = dirname(__DIR__);
require_once $root . '/Config/main.php';
require_once $root . '/Controller/NoticiasController.php';  // Trae $noticias, $paginacionNoticias
require_once $root . '/Controller/PartidosController.php';  // Trae $partidos, $paginacionPartidos, $categorias, $equiposRivales, $campos
require_once $root . '/Model/Repository/RepositoryGol.php';
require_once $root . '/Model/Repository/RepositoryPartidoTarjeta.php';
require_once $root . '/Model/Repository/RepositoryCambio.php';
require_once $root . '/Model/Repository/RepositoryIncidencia.php';

$repoGol = new RepositoryGol();
$repoPartidoTarjeta = new RepositoryPartidoTarjeta();
$repoIncidencia = new RepositoryIncidencia();
$repoCambio = new RepositoryCambio();

// ============================================================
// 2. ROLES
// ============================================================
$rol_usuario = $_SESSION['usuario']['id_rol'] ?? '';
$es_secretaria = ($rol_usuario == 2);
$es_admin = ($rol_usuario == 1);
$es_entrenador = ($rol_usuario == 3);

// ============================================================
// 3. FUNCIÓN PARA URLS CON PARÁMETROS DE PAGINACIÓN
// ============================================================
function urlConParametros($params = []) {
    $url = $_SERVER['SCRIPT_NAME'];
    $query = array_merge($_GET, $params);
    unset($query['pagina_noticias']);
    unset($query['pagina_partidos']);
    return $url . '?' . http_build_query($query);
}
?>

<div class="container py-5">
    
    <!-- ============================================================
    SECCIÓN: NOTICIAS Y NOVEDADES
    ============================================================ -->
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-newspaper me-2 text-warning"></i>NOTICIAS Y NOVEDADES
                </h2>
                <p class="text m-0">Mantente al día con las últimas noticias de la academia</p>
            </div>
            <?php if($es_admin || $es_secretaria): ?>
            <div>
                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCrearNoticia">
                    <i class="bi bi-plus-circle-fill me-2"></i>Crear Noticia
                </button>
            </div>
            <?php endif; ?>
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
                }, 1500); // Redirigir después de 1.5 segundos
            </script>
        <?php endif; ?>

        <?php if(isset($_GET['mensaje'])): ?>
        <script>
            setTimeout(function() {
                window.location.href = "index.php?View=Noticias";
            }, 1500);
        </script>
        <?php endif; ?>

        <!-- ===== NOTICIA DESTACADA ===== -->
        <?php if ($noticiaDestacada instanceof Noticia): ?>
        <div class="row g-4 mb-5">
            <div class="col-12">
                <div class="noticia-destacada-card p-4 position-relative overflow-hidden shadow-sm rounded-4">
                    <span class="badge bg-danger position-absolute top-0 end-0 m-3 px-3 py-2">
                        <i class="bi bi-star-fill me-1"></i>Destacada
                    </span>
                    <div class="row align-items-center g-4">
                        <div class="col-lg-7">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-warning text-dark">🔥 Destacada</span>
                                <span class="text-muted small">
                                    <i class="bi bi-clock me-1"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($noticiaDestacada->getFechaPublicacion())); ?>
                                </span>
                            </div>
                            <h3 class="fw-bold mb-3 text-black"><?php echo htmlspecialchars($noticiaDestacada->getTitulo()); ?></h3>
                            <p class="mb-3 text-muted"><?php echo htmlspecialchars(substr($noticiaDestacada->getContenido(), 0, 300)) . '...'; ?></p>
                            <div class="d-flex gap-2 flex-wrap">
                                <button class="btn btn-primary rounded-pill px-4" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalVerNoticia<?php echo $noticiaDestacada->getId(); ?>">
                                    Leer más <i class="bi bi-arrow-right ms-1"></i>
                                </button>
                                <?php if($es_admin || $es_secretaria): ?>
                                    <button class="btn btn-warning btn-sm rounded-pill px-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarNoticia<?php echo $noticiaDestacada->getId(); ?>">
                                        <i class="bi bi-pencil"></i>Editar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm rounded-pill px-2 btn-eliminar" 
                                            data-id="<?php echo $noticiaDestacada->getId(); ?>"
                                            data-titulo="<?php echo htmlspecialchars($noticiaDestacada->getTitulo()); ?>">
                                        <i class="bi bi-trash"></i>Eliminar
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-5 text-center">
                            <?php if ($noticiaDestacada->getImagen()): ?>
                                <img src="<?php echo htmlspecialchars($noticiaDestacada->getImagen()); ?>" 
                                     alt="<?php echo htmlspecialchars($noticiaDestacada->getTitulo()); ?>" 
                                     class="img-fluid rounded-3 shadow" 
                                     style="max-height: 280px; width: 100%; object-fit: cover;">
                            <?php else: ?>
                                <div class="bg-secondary d-flex align-items-center justify-content-center rounded-3" 
                                     style="height: 220px; color: #fff; background: linear-gradient(135deg, #6c757d, #343a40);">
                                    <i class="bi bi-image display-2 opacity-50"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===== MODALES PARA LA DESTACADA ===== -->
        <?php 
        $noticia = $noticiaDestacada;
        include './Public/Modals/modalVerNoticia.php'; 
        include './Public/Modals/modalEditarNoticia.php'; 
        ?>
        <?php endif; ?>

        <!-- ===== BLOQUES DE NOTICIAS NORMALES ===== -->
        <?php if (empty($noticias) && !$noticiaDestacada): ?>
            <div class="alert alert-info text-center py-4">
                <i class="bi bi-info-circle-fill me-2 fs-4"></i>
                <p class="mb-0">No hay noticias publicadas aún.</p>
            </div>
        <?php elseif (!empty($noticias)): ?>
            <div class="row g-4">
                <?php foreach ($noticias as $noticia): ?>
                    <?php if ($noticia instanceof Noticia): ?>
                    <div class="col-lg-3 col-md-6">
                        <div class="noticia-card h-100 shadow-sm  rounded-3 d-flex flex-column">
                            <?php if ($noticia->getImagen()): ?>
                                <img src="<?php echo htmlspecialchars($noticia->getImagen()); ?>" 
                                     class="card-img-top rounded-top-3" 
                                     alt="<?php echo htmlspecialchars($noticia->getTitulo()); ?>"
                                     style="height: 160px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" 
                                     style="height: 160px; color: #fff; background: linear-gradient(135deg, #6c757d, #495057);">
                                    <i class="bi bi-image display-2 opacity-50"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body d-flex flex-column flex-grow-1">
                                <h6 class="fw-bold"><?php echo htmlspecialchars($noticia->getTitulo()); ?></h6>
                                <p class="text small flex-grow-1"><?php echo htmlspecialchars(substr($noticia->getContenido(), 0, 80)) . '...'; ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="text small">
                                        <i class="bi bi-calendar me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($noticia->getFechaPublicacion())); ?>
                                    </span>
                                </div>
                                <?php if($es_admin || $es_secretaria): ?>
                                <div class="mt-2 d-flex gap-1" style="margin-bottom: 5px;">
                                     <button class="btn btn-primary rounded-pill px-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalVerNoticia<?php echo $noticia->getId(); ?>">
                                        Leer más
                                    </button>
                                    <button class="btn btn-warning btn-sm rounded-pill px-2" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#modalEditarNoticia<?php echo $noticia->getId(); ?>">
                                        <i class="bi bi-pencil"></i>Editar
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm rounded-pill px-2 btn-eliminar" 
                                            data-id="<?php echo $noticia->getId(); ?>"
                                            data-titulo="<?php echo htmlspecialchars($noticia->getTitulo()); ?>">
                                        <i class="bi bi-trash"></i>Eliminar
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- ===== MODALES PARA CADA NOTICIA NORMAL ===== -->
                    <?php include './Public/Modals/modalVerNoticia.php'; ?>
                    <?php include './Public/Modals/modalEditarNoticia.php'; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <!-- ===== PAGINADOR NOTICIAS ===== -->
            <?php if ($paginacionNoticias['total_paginas'] > 1): ?>
            <div class="paginador-wrapper mt-4">
                <nav>
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php echo ($paginaNoticias <= 1) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo urlConParametros(['pagina_noticias' => $paginaNoticias - 1]); ?>">
                                <i class="bi bi-chevron-left"></i>
                            </a>
                        </li>
                        <?php for ($i = 1; $i <= $paginacionNoticias['total_paginas']; $i++): ?>
                            <li class="page-item <?php echo ($i == $paginaNoticias) ? 'active' : ''; ?>">
                                <a class="page-link" href="<?php echo urlConParametros(['pagina_noticias' => $i]); ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($paginaNoticias >= $paginacionNoticias['total_paginas']) ? 'disabled' : ''; ?>">
                            <a class="page-link" href="<?php echo urlConParametros(['pagina_noticias' => $paginaNoticias + 1]); ?>">
                                <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="text-center text small">
                    Mostrando <?php echo $offsetNoticias + 1; ?> - <?php echo min($offsetNoticias + $noticiasPorPagina, $paginacionNoticias['total_registros']); ?> de <?php echo $paginacionNoticias['total_registros']; ?> noticias
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>

    <!-- ============================================================
SECCIÓN: PARTIDOS
============================================================ -->
<section class="mt-5 pt-3 border-top">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="bi bi-trophy me-2 text-success"></i>TORNEOS Y ENCUENTROS
            </h2>
            <p class="text m-0">Calendario de partidos y resultados</p>
        </div>
        <?php if($es_secretaria || $es_admin): ?>
        <div>
            <button class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalRegistrarPartido">
                <i class="bi bi-plus-circle-fill me-2"></i>Registrar Partido
            </button>
        </div>
        <?php endif; ?>
    </div>

    <!-- ===== LISTA DE PARTIDOS ===== -->
    <?php if (empty($partidos)): ?>
        <div class="alert alert-info text-center py-4">
            <i class="bi bi-info-circle-fill me-2 fs-4"></i>
            <p class="mb-0">No hay partidos registrados.</p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($partidos as $partido): 
                // ===== DETERMINAR ESTADO VISUAL =====
                $estadoVisual = obtenerEstadoPartido($partido);
                $estado_clase = match($estadoVisual) {
                    'Finalizado' => 'finalizado',
                    'Por Cargar' => 'por-cargar',
                    default => 'pendiente'
                };
                $icono_estado = match($estadoVisual) {
                    'Finalizado' => '🏁',
                    'Por Cargar' => '📝',
                    default => '📅'
                };

                // Obtener goleadores del partido
                $goleadores = $repoGol->findByPartido($partido['partido_id']);
                $tarjetas = $repoPartidoTarjeta->findByPartido($partido['partido_id']);
                $incidencias = $repoIncidencia->findByPartido($partido['partido_id']);
                $cambios = $repoCambio->findByPartido($partido['partido_id']);
                
                $fecha_obj = new DateTime($partido['fecha_partido']);
                $dia = $fecha_obj->format('d');
                $meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                $mes_nombre = $meses[(int)$fecha_obj->format('m') - 1];
                
                // Determinar modal de ver partido
                $modalVer = ($estadoVisual === 'Finalizado') ? 'modalVerPartidoFinalizado' : 'modalVerPartidoPendiente';
            ?>
            <div class="col-12 partido-item" data-id="<?php echo $partido['partido_id']; ?>">
                <div class="torneo-list-card p-3 d-flex align-items-center justify-content-between flex-wrap gap-3 shadow-sm rounded-3 border-0" 
                     data-bs-toggle="modal" 
                     data-bs-target="#<?php echo $modalVer . $partido['partido_id']; ?>"
                     style="cursor: pointer;">
                    
                    <div class="d-flex align-items-center">
                        <div class="badge-fecha-box me-3">
                            <span class="dia"><?php echo $dia; ?></span>
                            <span class="mes"><?php echo $mes_nombre; ?></span>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">
                                <?php echo htmlspecialchars($partido['categoria_nombre'] ?? 'Sin categoría'); ?> 
                                — <?php echo htmlspecialchars($partido['equipo_rival'] ?? 'Rival'); ?>
                            </h6>
                            <div class="text small">
                                <span class="me-3">
                                    <i class="bi bi-clock me-1"></i>
                                    <?php echo date('H:i', strtotime($partido['fecha_partido'])); ?>
                                </span>
                                <span>
                                    <i class="bi bi-geo-alt me-1"></i>
                                    <?php echo htmlspecialchars($partido['campo_nombre'] ?? 'Sin campo'); ?>
                                </span>
                                <?php if (!empty($partido['tipo_partido']) && $partido['tipo_partido'] !== 'Amistoso'): ?>
                                    <span class="ms-2 badge bg-secondary">
                                        <?php echo htmlspecialchars($partido['tipo_partido']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex align-items-center gap-3">
                        <span class="pill-estado <?php echo $estado_clase; ?>">
                            <?php echo $icono_estado . ' ' . $estadoVisual; ?>
                        </span>
                        
                        <?php if(($es_entrenador || $es_admin) && $estadoVisual === 'Por Cargar'): ?>
                            <button class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalCargarResultado<?php echo $partido['partido_id']; ?>"
                                    onclick="event.stopPropagation();">
                                <i class="bi bi-pencil-square me-1"></i> Cargar Resultado
                            </button>
                        <?php endif; ?>
                        
                        <?php if($es_secretaria || $es_admin): ?>
                            <button class="btn btn-warning btn-sm rounded-pill px-2" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditarPartido<?php echo $partido['partido_id']; ?>"
                                    onclick="event.stopPropagation();">
                                <i class="bi bi-pencil"></i>Editar
                            </button>
                            <button class="btn btn-danger btn-sm rounded-pill px-2" 
                                    onclick="if(confirm('¿Eliminar este partido?')) window.location.href='Controller/EliminarPartidoController.php?id=<?php echo $partido['partido_id']; ?>'"
                                    onclick="event.stopPropagation();">
                                <i class="bi bi-trash"></i>Eliminar
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- ===== MODALES DE PARTIDOS ===== -->
            <?php 
            // Cargar atletas y tarjetas para el modal de cargar resultado
            if ($estadoVisual === 'Por Cargar') {
                $atletasCategoria = $repoAtleta->findByCategoria($partido['categoria_id']);
                $tiposTarjetas = $repoTarjeta->findAll();
            }
            // Modal Ver Partido (Pendiente o Finalizado)
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

        <!-- ===== PAGINADOR PARTIDOS ===== -->
        <?php if ($paginacionPartidos['total_paginas'] > 1): ?>
        <div class="paginador-wrapper mt-4">
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo ($paginaPartidos <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo urlConParametros(['pagina_partidos' => $paginaPartidos - 1]); ?>">
                            <i class="bi bi-chevron-left"></i>
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $paginacionPartidos['total_paginas']; $i++): ?>
                        <li class="page-item <?php echo ($i == $paginaPartidos) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo urlConParametros(['pagina_partidos' => $i]); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo ($paginaPartidos >= $paginacionPartidos['total_paginas']) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo urlConParametros(['pagina_partidos' => $paginaPartidos + 1]); ?>">
                            <i class="bi bi-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <div class="text-center text small">
                Mostrando <?php echo $offsetPartidos + 1; ?> - <?php echo min($offsetPartidos + $partidosPorPagina, $paginacionPartidos['total_registros']); ?> de <?php echo $paginacionPartidos['total_registros']; ?> partidos
            </div>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</section>
</div>

<!-- ============================================================
MODALES DE NOTICIAS (GLOBALES)
============================================================ -->
<?php include './Public/Modals/modalCrearNoticia.php'; ?>

<!-- ============================================================
MODALES DE PARTIDOS (GLOBALES)
============================================================ -->
<?php include './Public/Modals/modalRegistrarPartido.php'; ?>

<!-- ============================================================
SCRIPT UNIFICADO
============================================================ -->
<script>
// Eliminar noticia con confirmación (usando event listeners)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.btn-eliminar').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var id = this.getAttribute('data-id');
            var titulo = this.getAttribute('data-titulo');
            if (confirm('¿Estás seguro de eliminar la noticia "' + titulo + '"? Esta acción no se puede deshacer.')) {
                window.location.href = 'Controller/EliminarNoticiaController.php?id=' + id;
            }
        });
    });
});
</script>