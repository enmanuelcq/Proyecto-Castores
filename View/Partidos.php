<?php

require_once './Model/Repository/RepositoryAtleta.php';
require_once './Model/Repository/RepositoryConvocado.php';
require_once './Model/Repository/RepositoryGol.php';
require_once './Model/Repository/RepositoryPartidoTarjeta.php';

$atleta_id = isset($_GET['id']) ? trim($_GET['id']) : '';

if(empty($atleta_id)) {
    echo '<div class="alert alert-warning">Selecciona un atleta para ver sus partidos</div>';
    exit();
}

$repoAtleta = new RepositoryAtleta();
$repoConvocado = new RepositoryConvocado();
$repoGol = new RepositoryGol();
$repoTarjeta = new RepositoryPartidoTarjeta();

$atletaEntity = $repoAtleta->findById($atleta_id);

if(!$atletaEntity) {
    echo '<div class="alert alert-danger">Atleta no encontrado</div>';
    exit();
}

$atletaId = $atletaEntity->getAtletaId();
$categoria_atleta = $repoAtleta->getCategoriaAtleta($atletaId) ?? 'Sin categoría';

$atleta = [
    'atleta_id' => $atletaId,
    'atleta_cedula' => $atletaEntity->getCedula(),
    'atleta_primer_nombre' => $atletaEntity->getPrimerNombre(),
    'atleta_primer_apellido' => $atletaEntity->getPrimerApellido(),
    'atleta_categoria' => $categoria_atleta,
];

$convocatorias = $repoConvocado->findByAtleta($atletaId);

$golesAtleta = $repoGol->findByAtleta($atletaId);
$total_goles = 0;
$golesPorPartido = [];
foreach($golesAtleta as $gol) {
    $total_goles += (int) $gol['cant_goles'];
    $pid = (int) $gol['partido_id'];
    $golesPorPartido[$pid] = ($golesPorPartido[$pid] ?? 0) + (int) $gol['cant_goles'];
}

$tarjetasAtleta = $repoTarjeta->findByAtleta($atletaId);
$total_amarillas = 0;
$total_rojas = 0;
foreach($tarjetasAtleta as $t) {
    if((int) $t['tarjeta_id'] === 1) $total_amarillas++;
    elseif((int) $t['tarjeta_id'] === 2) $total_rojas++;
}

$total_partidos = count($convocatorias);

$ganados = 0;
$perdidos = 0;
$empatados = 0;
$por_cargar = 0;
$partidos_jugados = [];

foreach($convocatorias as $partidoRow) {
    $pid = (int) $partidoRow['partido_id'];
    $gf = (int) $partidoRow['goles_favor'];
    $gc = (int) $partidoRow['goles_contra'];
    $estado = $partidoRow['estado'] ?? 'Pendiente';

    $status = 'Pendiente';
    if($estado === 'Finalizado') {
        if($gf > $gc) { $status = 'Ganado'; $ganados++; }
        elseif($gf < $gc) { $status = 'Perdido'; $perdidos++; }
        else { $status = 'Empatado'; $empatados++; }
    } elseif($estado === 'En Curso') {
        $status = 'En Curso';
    } else {
        $por_cargar++;
    }

    $partidos_jugados[] = [
        'partido_id' => $pid,
        'rival' => $partidoRow['equipo_rival'] ?? 'N/A',
        'fecha' => $partidoRow['fecha_partido'],
        'status' => $status,
        'goles_favor' => $gf,
        'goles_contra' => $gc,
        'tipo' => $partidoRow['tipo_partido'] ?? 'Amistoso',
        'categoria' => $partidoRow['categoria_nombre'] ?? 'N/A',
        'categoria_id' => (int) ($partidoRow['categoria_id'] ?? 0),
        'goles_jugador' => $golesPorPartido[$pid] ?? 0,
        'minutos_jugados' => $partidoRow['minutos_jugados'] ?? null,
    ];
}

usort($partidos_jugados, function($a, $b) {
    return strtotime($b['fecha']) - strtotime($a['fecha']);
});

$categorias_filtro = [];
$tipos_filtro = [];
foreach($partidos_jugados as $pj) {
    if(!empty($pj['categoria']) && !in_array($pj['categoria'], $categorias_filtro)) {
        $categorias_filtro[] = $pj['categoria'];
    }
    if(!empty($pj['tipo']) && !in_array($pj['tipo'], $tipos_filtro)) {
        $tipos_filtro[] = $pj['tipo'];
    }
}
sort($categorias_filtro);
sort($tipos_filtro);

$url_volver = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php?View=DashboardRendimiento&id=' . $atletaId;
?>

<div class="container contenido">

    <div class="top-header">
        <h4>
            <i class="bi bi-trophy me-2"></i>
            Partidos de <?php echo $atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']; ?>
        </h4>
        <a href="<?php echo $url_volver; ?>" class="btn-volver">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
    </div>
    
    <div class="atleta-header">
        <div class="atleta-avatar">
            <?php 
            echo strtoupper(substr($atleta['atleta_primer_nombre'] ?? 'A', 0, 1) . substr($atleta['atleta_primer_apellido'] ?? 'A', 0, 1));
            ?>
        </div>
        <div class="atleta-info">
            <h5><?php echo $atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']; ?></h5>
            <small>
                <i class="bi bi-tag me-1"></i><?php echo $atleta['atleta_categoria']; ?>
                <span class="mx-2">|</span>
                <i class="bi bi-person-badge me-1"></i><?php echo $atleta['atleta_cedula']; ?>
                <span class="mx-2">|</span>
                <i class="bi bi-trophy me-1"></i><?php echo $total_partidos; ?> partidos
            </small>
        </div>
        
        <div class="atleta-estadisticas">
            <div class="item verde">
                <span class="numero"><?php echo $ganados; ?></span>
                <span class="etiqueta">Ganados</span>
            </div>
            <div class="item amarillo">
                <span class="numero"><?php echo $empatados; ?></span>
                <span class="etiqueta">Empatados</span>
            </div>
            <div class="item rojo">
                <span class="numero"><?php echo $perdidos; ?></span>
                <span class="etiqueta">Perdidos</span>
            </div>
            <div class="item azul">
                <span class="numero"><?php echo $total_goles; ?></span>
                <span class="etiqueta">Goles</span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card-estadistica">
                <div class="icono" style="background: rgba(13, 109, 253, 0.09); color: #084092;">
                    <i class="bi bi-trophy"></i>
                </div>
                <span class="valor"><?php echo $total_partidos; ?></span>
                <p class="nombre">Partidos Jugados</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card-estadistica">
                <div class="icono" style="background: rgba(25,135,84,0.09); color: #0c4e2f;">
                    <i class="bi bi-emoji-sunglasses"></i>
                </div>
                <span class="valor"><?php echo $total_goles; ?></span>
                <p class="nombre">Goles Marcados</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card-estadistica">
                <div class="icono" style="background: rgba(255,193,7,0.09); color: #ffc415;">
                    <i class="bi bi-square text-warning"></i>
                </div>
                <span class="valor"><?php echo $total_amarillas; ?></span>
                <p class="nombre">Tarjetas Amarillas</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card-estadistica">
                <div class="icono" style="background: rgba(220,53,69,0.09); color: #a50b0b;">
                    <i class="bi bi-square-fill text-danger"></i>
                </div>
                <span class="valor"><?php echo $total_rojas; ?></span>
                <p class="nombre">Tarjetas Rojas</p>
            </div>
        </div>
    </div>

    <div class="filtros-partidos">
        <div class="filtro-grupo">
            <label for="filtroCategoria"><i class="bi bi-tag me-1"></i>Categoría</label>
            <select id="filtroCategoria" onchange="filtrarPartidos()">
                <option value="">Todas</option>
                <?php foreach($categorias_filtro as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filtro-grupo">
            <label for="filtroTipo"><i class="bi bi-award me-1"></i>Tipo de Partido</label>
            <select id="filtroTipo" onchange="filtrarPartidos()">
                <option value="">Todos</option>
                <?php foreach($tipos_filtro as $tipo): ?>
                    <option value="<?php echo htmlspecialchars($tipo); ?>"><?php echo htmlspecialchars($tipo); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filtro-grupo">
            <label for="filtroResultado"><i class="bi bi-flag me-1"></i>Resultado</label>
            <select id="filtroResultado" onchange="filtrarPartidos()">
                <option value="">Todos</option>
                <option value="Ganado">Ganados</option>
                <option value="Empatado">Empatados</option>
                <option value="Perdido">Perdidos</option>
                <option value="En Curso">En Curso</option>
            </select>
        </div>
        <span class="filtro-contador" id="filtroContador"><?php echo $total_partidos; ?> partidos</span>
    </div>

    <h5 class="mb-3" style="color: var(--text-primary);">
        <i class="bi bi-list-ul me-2 text-warning"></i>
        Historial de Partidos
        <span class="ms-2" style="font-size:0.8rem;color: var(--text-muted);">(<?php echo $total_partidos; ?> partidos)</span>
    </h5>

    <?php if(empty($partidos_jugados)): ?>
        <div class="empty-state">
            <i class="bi bi-trophy"></i>
            <h5>Sin partidos registrados</h5>
            <p>Este atleta aún no ha participado en ningún partido</p>
        </div>
    <?php else: ?>
        <?php foreach($partidos_jugados as $pj): 
            $status = $pj['status'];
            
            $badge_class = 'badge-';
            if($status == 'Ganado') $badge_class .= 'ganado';
            elseif($status == 'Perdido') $badge_class .= 'perdido';
            elseif($status == 'Empatado') $badge_class .= 'empatado';
            elseif($status == 'Por Cargar') $badge_class .= 'por-cargar';
            elseif($status == 'En Curso') $badge_class .= 'por-jugar';
            else $badge_class .= 'por-jugar';
            
            $fecha = new DateTime($pj['fecha']);
            $fecha_formateada = $fecha ? $fecha->format('d/m/Y') : $pj['fecha'];
        ?>
        <div class="partido-card"
             data-categoria="<?php echo htmlspecialchars($pj['categoria']); ?>"
             data-tipo="<?php echo htmlspecialchars($pj['tipo']); ?>"
             data-resultado="<?php echo $status; ?>">
            <div class="partido-header">
                <div>
                    <span class="fecha">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?php echo $fecha_formateada; ?>
                    </span>
                    <span class="rival ms-3">
                        <i class="bi bi-shield"></i>
                        vs <?php echo $pj['rival']; ?>
                    </span>
                    <span class="ms-2 tipo-badge"><?php echo $pj['tipo']; ?></span>
                </div>
                <div>
                    <span class="badge-estado <?php echo $badge_class; ?>">
                        <?php echo $status; ?>
                    </span>
                </div>
            </div>
            
            <div class="estadisticas-jugador">
                <span class="stat">
                    <i class="bi bi-speedometer"></i>
                    <?php echo $pj['goles_favor']; ?> - <?php echo $pj['goles_contra']; ?>
                </span>
                <?php if($pj['minutos_jugados'] !== null): ?>
                <span class="stat">
                    <i class="bi bi-clock text-info"></i>
                    <span class="numero"><?php echo $pj['minutos_jugados']; ?></span> min
                </span>
                <?php endif; ?>
                <?php if($pj['goles_jugador'] > 0): ?>
                <span class="stat">
                    <i class="bi bi-emoji-sunglasses text-success"></i>
                    <span class="numero"><?php echo $pj['goles_jugador']; ?></span> gol(es)
                </span>
                <?php endif; ?>
                <?php
                $tarjetas_amarillas_partido = 0;
                $tarjetas_rojas_partido = 0;
                foreach($tarjetasAtleta as $t) {
                    if((int)$t['partido_id'] === $pj['partido_id']) {
                        if((int)$t['tarjeta_id'] === 1) $tarjetas_amarillas_partido++;
                        elseif((int)$t['tarjeta_id'] === 2) $tarjetas_rojas_partido++;
                    }
                }
                ?>
                <?php if($tarjetas_amarillas_partido > 0): ?>
                <span class="stat">
                    <i class="bi bi-square text-warning"></i>
                    <span class="numero"><?php echo $tarjetas_amarillas_partido; ?></span>
                </span>
                <?php endif; ?>
                <?php if($tarjetas_rojas_partido > 0): ?>
                <span class="stat">
                    <i class="bi bi-square-fill text-danger"></i>
                    <span class="numero"><?php echo $tarjetas_rojas_partido; ?></span>
                </span>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
</div>

<script>
function filtrarPartidos() {
    var cat = document.getElementById('filtroCategoria').value;
    var tipo = document.getElementById('filtroTipo').value;
    var resultado = document.getElementById('filtroResultado').value;
    var cards = document.querySelectorAll('.partido-card');
    var visibles = 0;

    cards.forEach(function(card) {
        var matchCat = !cat || card.getAttribute('data-categoria') === cat;
        var matchTipo = !tipo || card.getAttribute('data-tipo') === tipo;
        var matchResultado = !resultado || card.getAttribute('data-resultado') === resultado;

        if(matchCat && matchTipo && matchResultado) {
            card.style.display = '';
            visibles++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('filtroContador').textContent = visibles + ' partido' + (visibles !== 1 ? 's' : '');
}
</script>
