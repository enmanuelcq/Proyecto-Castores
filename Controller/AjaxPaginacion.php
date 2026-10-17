<?php
$root = dirname(__DIR__);
require_once $root . '/Config/Session_start.php';
require_once $root . '/Config/main.php';
require_once $root . '/Model/Repository/RepositoryGol.php';
require_once $root . '/Model/Repository/RepositoryPartidoTarjeta.php';
require_once $root . '/Model/Repository/RepositoryCambio.php';
require_once $root . '/Model/Repository/RepositoryIncidencia.php';
require_once $root . '/Model/Repository/RepositoryConvocado.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';

header('Content-Type: application/json');

$type = $_GET['type'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));

$rol_usuario = $_SESSION['usuario']['id_rol'] ?? '';
$es_secretaria = ($rol_usuario == 2);
$es_admin = ($rol_usuario == 1);
$es_entrenador = ($rol_usuario == 3);

$repoGol = new RepositoryGol();
$repoPartidoTarjeta = new RepositoryPartidoTarjeta();
$repoIncidencia = new RepositoryIncidencia();
$repoCambio = new RepositoryCambio();

ob_start();

switch ($type) {

    case 'noticias':
        $_GET['pagina_noticias'] = $page;
        require_once $root . '/Controller/NoticiasController.php';
        ?>
        <?php if (empty($noticias)): ?>
        <div class="empty-state text-center py-5">
            <div class="empty-state-icon"><i class="bi bi-newspaper"></i></div>
            <h4 class="fw-bold mt-3 mb-1">Sin noticias aún</h4>
            <p class="text-muted mb-0">No hay noticias publicadas.</p>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($noticias as $noticia): ?>
            <?php if ($noticia instanceof Noticia): ?>
            <div class="col-lg-3 col-md-6">
                <div class="news-col-card h-100 d-flex flex-column">
                    <?php if ($noticia->getImagen()): ?>
                    <div class="news-col-img-wrap">
                        <img src="<?php echo htmlspecialchars($noticia->getImagen()); ?>" class="news-col-img" alt="<?php echo htmlspecialchars($noticia->getTitulo()); ?>">
                    </div>
                    <?php else: ?>
                    <div class="news-col-img-placeholder"><i class="bi bi-image"></i></div>
                    <?php endif; ?>
                    <div class="news-col-body d-flex flex-column flex-grow-1">
                        <div class="news-col-date mb-2"><?php echo date('d/m/Y', strtotime($noticia->getFechaPublicacion())); ?></div>
                        <h6 class="news-col-title"><?php echo htmlspecialchars($noticia->getTitulo()); ?></h6>
                        <p class="news-col-excerpt flex-grow-1"><?php echo htmlspecialchars(substr($noticia->getContenido(), 0, 110)); ?>…</p>
                        <div class="news-col-actions mt-auto">
                            <button class="news-col-read-btn" data-bs-toggle="modal" data-bs-target="#modalVerNoticia<?php echo $noticia->getId(); ?>">
                                Leer más <i class="bi bi-arrow-right ms-1"></i>
                            </button>
                            <?php if($es_admin || $es_secretaria): ?>
                            <div class="news-col-admin mt-2 d-flex gap-1">
                                <button class="btn-admin-sm btn-admin-edit" data-bs-toggle="modal" data-bs-target="#modalEditarNoticia<?php echo $noticia->getId(); ?>"><i class="bi bi-pencil"></i></button>
                                <button class="btn-admin-sm btn-admin-delete btn-eliminar" data-id="<?php echo $noticia->getId(); ?>" data-titulo="<?php echo htmlspecialchars($noticia->getTitulo()); ?>"><i class="bi bi-trash"></i></button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php include $root . '/Public/Modals/modalVerNoticia.php'; ?>
            <?php include $root . '/Public/Modals/modalEditarNoticia.php'; ?>
            <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <?php if ($paginacionNoticias['total_paginas'] > 1): ?>
        <div class="mt-4">
            <nav><ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($paginaNoticias <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link ajax-page" data-type="noticias" data-page="<?php echo $paginaNoticias - 1; ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php for ($i = 1; $i <= $paginacionNoticias['total_paginas']; $i++): ?>
                <li class="page-item <?php echo ($i == $paginaNoticias) ? 'active' : ''; ?>">
                    <a class="page-link ajax-page" data-type="noticias" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($paginaNoticias >= $paginacionNoticias['total_paginas']) ? 'disabled' : ''; ?>">
                    <a class="page-link ajax-page" data-type="noticias" data-page="<?php echo $paginaNoticias + 1; ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul></nav>
            <div class="text-center text-muted small">
                Mostrando <?php echo $offsetNoticias + 1; ?>–<?php echo min($offsetNoticias + $noticiasPorPagina, $paginacionNoticias['total_registros']); ?> de <?php echo $paginacionNoticias['total_registros']; ?> noticias
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        <?php
        break;

    case 'partidos':
        $_GET['pagina_partidos'] = $page;
        require_once $root . '/Controller/PartidosController.php';
        $repoConvocado = new RepositoryConvocado();
        ?>
        <?php if (empty($partidos)): ?>
        <div class="empty-state text-center py-5">
            <div class="empty-state-icon empty-state-icon-green"><i class="bi bi-trophy"></i></div>
            <h4 class="fw-bold mt-3 mb-1">Sin partidos registrados</h4>
            <p class="text-muted mb-0">Registra el primer partido de la temporada.</p>
        </div>
        <?php else: ?>
        <div class="row g-3">
            <?php foreach ($partidos as $partido): 
                $estadoVisual = obtenerEstadoPartido($partido);
                $estado_clase = match($estadoVisual) { 'Finalizado' => 'finalizado', 'Por Cargar' => 'por-cargar', default => 'pendiente' };
                $icono_estado = match($estadoVisual) { 'Finalizado' => 'bi-check-circle-fill', 'Por Cargar' => 'bi-pencil-square', default => 'bi-calendar-event' };
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
                $catsElegibles = array_filter([(int)$partido['categoria_id'], (int)$partido['categoria_id'] - 1, (int)$partido['categoria_id'] - 2], fn($c) => $c >= 1);
                $atletasCategoria = (new RepositoryAtleta())->findByCategorias($catsElegibles, (int)$partido['categoria_id']);
                $catAtletaIds = array_map(fn($a) => $a->getAtletaId(), $atletasCategoria);
                $mapaCatAtletas = (new RepositoryAtleta())->mapaCategorias($catAtletaIds);
            ?>
            <div class="col-12 partido-item" data-id="<?php echo $partido['partido_id']; ?>" data-status="<?php echo $estado_clase; ?>" data-categoria="<?php echo $partido['categoria_id']; ?>">
                <div class="dash-match-card" data-bs-toggle="modal" data-bs-target="#<?php echo $modalVer . $partido['partido_id']; ?>" style="cursor: pointer;">
                    <div class="dash-match-left">
                        <div class="dash-match-datebox">
                            <span class="dash-match-day"><?php echo $dia; ?></span>
                            <span class="dash-match-month"><?php echo $mes_nombre; ?></span>
                            <span class="dash-match-weekday"><?php echo $diaSemana; ?></span>
                        </div>
                        <div class="dash-match-separator"></div>
                        <div class="dash-match-info">
                            <div class="dash-match-cat"><?php echo htmlspecialchars($partido['categoria_nombre'] ?? 'Sin categoría'); ?></div>
                            <div class="dash-match-rival">vs <?php echo htmlspecialchars($partido['equipo_rival'] ?? 'Rival'); ?></div>
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
                        <span class="dash-status-pill <?php echo $estado_clase; ?>"><i class="<?php echo $icono_estado; ?> me-1"></i><?php echo $estadoVisual; ?></span>
                        <?php if(($es_entrenador || $es_admin) && $estadoVisual === 'Por Cargar'): ?>
                        <button class="btn btn-sm-accent btn-round-sm" data-bs-toggle="modal" data-bs-target="#modalCargarResultado<?php echo $partido['partido_id']; ?>" onclick="event.stopPropagation();"><i class="bi bi-pencil-square me-1"></i>Cargar</button>
                        <?php endif; ?>
                        <?php if($es_secretaria || $es_admin): ?>
                        <button class="btn-admin-sm btn-admin-edit" data-bs-toggle="modal" data-bs-target="#modalEditarPartido<?php echo $partido['partido_id']; ?>" onclick="event.stopPropagation();"><i class="bi bi-pencil"></i></button>
                        <button class="btn-admin-sm btn-admin-delete" onclick="event.stopPropagation(); if(confirm('¿Eliminar este partido?')) window.location.href='Controller/EliminarPartidoController.php?id=<?php echo $partido['partido_id']; ?>'"><i class="bi bi-trash"></i></button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php
            if ($estadoVisual === 'Por Cargar') { $tiposTarjetas = $repoTarjeta->findAll(); }
            if ($estadoVisual === 'Finalizado') { include $root . '/Public/Modals/modalVerPartidoFinalizado.php'; }
            else { include $root . '/Public/Modals/modalVerPartidoPendiente.php'; }
            ?>
            <?php include $root . '/Public/Modals/modalEditarPartido.php'; ?>
            <?php include $root . '/Public/Modals/modalCargarResultado.php'; ?>
            <?php endforeach; ?>
        </div>
        <?php if ($paginacionPartidos['total_paginas'] > 1): ?>
        <div class="mt-4">
            <nav><ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($paginaPartidos <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link ajax-page" data-type="partidos" data-page="<?php echo $paginaPartidos - 1; ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php for ($i = 1; $i <= $paginacionPartidos['total_paginas']; $i++): ?>
                <li class="page-item <?php echo ($i == $paginaPartidos) ? 'active' : ''; ?>">
                    <a class="page-link ajax-page" data-type="partidos" data-page="<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($paginaPartidos >= $paginacionPartidos['total_paginas']) ? 'disabled' : ''; ?>">
                    <a class="page-link ajax-page" data-type="partidos" data-page="<?php echo $paginaPartidos + 1; ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul></nav>
            <div class="text-center text-muted small">
                Mostrando <?php echo $offsetPartidos + 1; ?>–<?php echo min($offsetPartidos + $partidosPorPagina, $paginacionPartidos['total_registros']); ?> de <?php echo $paginacionPartidos['total_registros']; ?> partidos
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        <?php
        break;

    case 'atletas':
        $_GET['pagina'] = $page;
        $_GET['buscar'] = $_GET['buscar'] ?? '';
        $_GET['categoria'] = $_GET['categoria'] ?? 0;
        $_GET['estado'] = $_GET['estado'] ?? 'activos';
        require_once $root . '/Controller/ConsultarAtleta_controller.php';
        $pagina_actual = $paginacion['pagina_actual'] ?? 1;
        $total_paginas = $paginacion['total_paginas'] ?? 1;
        $total_registros = $paginacion['total_registros'] ?? 0;
        $registros_por_pagina = $paginacion['registros_por_pagina'] ?? 5;
        $offset = $paginacion['offset'] ?? 0;
        ?>
        <div class="athlete-list">
        <?php if (empty($todos_los_atletas)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle-fill me-2"></i>No se encontraron atletas con los filtros aplicados.
            </div>
        <?php else: ?>
            <?php foreach ($todos_los_atletas as $item): 
                $atleta = $item['atleta'];
                $representante = $item['representante'] ?? null;
                $representantes = $item['representantes'] ?? [];
                $categoria = $item['categoria_nombre'] ?? 'Sin categoría';
                $categoriaAnio = $item['categoria_anio'] ?? 'Primero';
                $numeroFranela = $item['numero_franela'] ?? 'N/A';
                $uniforme = $item['uniforme'] ?? null;
                $salud = $item['salud'] ?? null;
                $grado = $item['grado'] ?? null;
                $colegio = $item['colegio'] ?? null;
                $hobbie = $item['hobbie'] ?? null;
                $horarioHobbie = $item['horario_hobbie'] ?? '';
                $atleta_id = $atleta->getAtletaId();
                $nombre_completo = trim($atleta->getPrimerNombre() . ' ' . $atleta->getSegundoNombre() . ' ' . $atleta->getPrimerApellido() . ' ' . $atleta->getSegundoApellido());
                $esActivo = $atleta->getAtletaStatus();
                $fechaVigencia = '';
                if ($salud && $salud->getVigencia()) {
                    $fechaVigencia = date('Y-m-d', strtotime($salud->getVigencia()));
                }
                $solvencia = $item['solvencia'] ?? [];
            ?>
            
            <div class="athlete-capsule d-flex align-items-center justify-content-between flex-wrap gap-3 
                        <?php echo !$esActivo ? 'border border-danger bg-light' : ''; ?>">
                <div class="d-flex align-items-center gap-4">
                    <?php $foto = $atleta->getFoto(); ?>
                    <div class="avatar-box">
                        <?php if (!empty($foto)): ?>
                            <img src="./Public/uploads/<?php echo htmlspecialchars($foto); ?>" alt="Foto" class="avatar-img">
                        <?php else: ?>
                            👤
                        <?php endif; ?>
                    </div>
                    <div>
                        <h3 class="athlete-name">
                            <?php echo htmlspecialchars($nombre_completo); ?>
                            <?php if (!$esActivo): ?>
                                <span class="badge bg-danger ms-2">INACTIVO</span>
                            <?php endif; ?>
                        </h3>
                        <span class="athlete-meta">Categoria: <?php echo htmlspecialchars($categoria); ?></span> &nbsp;|&nbsp; 
                        <span class="athlete-meta text-uppercase"><?php echo htmlspecialchars($atleta->getSexo()); ?></span>
                        <span class="badge bg-secondary ms-2">#<?php echo htmlspecialchars($numeroFranela); ?></span>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <?php if(isset($_SESSION['usuario']) && ($_SESSION['usuario']['id_rol'] ?? 0) == 2): ?>
                        <button class="btn btn-action-green" data-bs-toggle="modal" data-bs-target="#modalVerMas<?php echo $atleta_id; ?>">
                            <i class="bi bi-eye me-1"></i>Ver más
                        </button>
                        <button class="btn btn-action-green" data-bs-toggle="modal" data-bs-target="#modalSolvencia<?php echo $atleta_id; ?>">
                            <i class="bi bi-cash-stack me-1"></i>Solvencia
                        </button>
                        
                        <?php if ($esActivo): ?>
                            <button class="btn btn-action-green" data-bs-toggle="modal" data-bs-target="#modalActualizar<?php echo $atleta_id; ?>">
                                <i class="bi bi-pencil me-1"></i>Actualizar
                            </button>
                            <button class="btn btn-action-red" data-bs-toggle="modal" data-bs-target="#modalDesactivar<?php echo $atleta_id; ?>">
                                <i class="bi bi-trash me-1"></i>Eliminar
                            </button>
                        <?php else: ?>
                            <button class="btn btn-action-success" data-bs-toggle="modal" data-bs-target="#modalReactivar<?php echo $atleta_id; ?>">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reactivar
                            </button>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php if(isset($_SESSION['usuario']) && ($_SESSION['usuario']['id_rol'] ?? 0) == 3): ?>
                        <button class="btn btn-action-green" data-bs-toggle="modal" data-bs-target="#modalVerMas<?php echo $atleta_id; ?>">
                            <i class="bi bi-eye me-1"></i>Ver más
                        </button>
                        <a href="index.php?View=DashboardRendimiento&Atleta=<?php echo $atleta_id; ?>" class="btn btn-action-green">
                            <i class="bi bi-graph-up me-1"></i>Rendimiento
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php include $root . '/Public/Modals/modal_ver_mas.php'; ?>
            <?php include $root . '/Public/Modals/modal_solvencia.php'; ?>
            <?php include $root . '/Public/Modals/modal_registrar_pago_global.php'; ?>
            
            <?php if ($esActivo): ?>
                <?php include $root . '/Public/Modals/modal_actualizar.php'; ?>
                <?php include $root . '/Public/Modals/modal_eliminar.php'; ?>
            <?php else: ?>
                <?php include $root . '/Public/Modals/modal_reactivar.php'; ?>
            <?php endif; ?>
        <?php endforeach; ?>
        <?php endif; ?>
        </div>
        <?php if ($total_paginas > 1): ?>
        <nav aria-label="Paginación de atletas" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link ajax-page" data-type="atletas" data-page="<?php echo $pagina_actual - 1; ?>" data-buscar="<?php echo urlencode($_GET['buscar'] ?? ''); ?>" data-categoria="<?php echo $_GET['categoria'] ?? 0; ?>" data-estado="<?php echo $_GET['estado'] ?? 'activos'; ?>"><i class="bi bi-chevron-left"></i></a>
                </li>
                <?php $rango = 2; $inicio = max(1, $pagina_actual - $rango); $fin = min($total_paginas, $pagina_actual + $rango); ?>
                <?php if ($inicio > 1): ?>
                    <li class="page-item"><a class="page-link ajax-page" data-type="atletas" data-page="1" data-buscar="<?php echo urlencode($_GET['buscar'] ?? ''); ?>" data-categoria="<?php echo $_GET['categoria'] ?? 0; ?>" data-estado="<?php echo $_GET['estado'] ?? 'activos'; ?>">1</a></li>
                    <?php if ($inicio > 2): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                <?php endif; ?>
                <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                <li class="page-item <?php echo ($i == $pagina_actual) ? 'active' : ''; ?>">
                    <a class="page-link ajax-page" data-type="atletas" data-page="<?php echo $i; ?>" data-buscar="<?php echo urlencode($_GET['buscar'] ?? ''); ?>" data-categoria="<?php echo $_GET['categoria'] ?? 0; ?>" data-estado="<?php echo $_GET['estado'] ?? 'activos'; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                <?php if ($fin < $total_paginas): ?>
                    <?php if ($fin < $total_paginas - 1): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                    <li class="page-item"><a class="page-link ajax-page" data-type="atletas" data-page="<?php echo $total_paginas; ?>" data-buscar="<?php echo urlencode($_GET['buscar'] ?? ''); ?>" data-categoria="<?php echo $_GET['categoria'] ?? 0; ?>" data-estado="<?php echo $_GET['estado'] ?? 'activos'; ?>"><?php echo $total_paginas; ?></a></li>
                <?php endif; ?>
                <li class="page-item <?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
                    <a class="page-link ajax-page" data-type="atletas" data-page="<?php echo $pagina_actual + 1; ?>" data-buscar="<?php echo urlencode($_GET['buscar'] ?? ''); ?>" data-categoria="<?php echo $_GET['categoria'] ?? 0; ?>" data-estado="<?php echo $_GET['estado'] ?? 'activos'; ?>"><i class="bi bi-chevron-right"></i></a>
                </li>
            </ul>
        </nav>
        <div class="text-center text small mt-2">
            Mostrando <?php echo $offset + 1; ?> - <?php echo min($offset + $registros_por_pagina, $total_registros); ?> de <?php echo $total_registros; ?> atletas
        </div>
        <?php endif; ?>
        <?php
        break;

    default:
        echo '<div class="alert alert-danger">Tipo no válido</div>';
        break;
}

$html = ob_get_clean();
echo json_encode(['ok' => true, 'html' => $html]);
