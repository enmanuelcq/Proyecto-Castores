<?php
// ============================================
// /vistas/partidos.php
// VISTA DE PARTIDOS INDIVIDUAL DEL ATLETA
// ============================================

require_once './Model/Noticias.php';
require_once './Model/Participaciones_partidos.php';
require_once './Model/Atletas.php';

// Obtener la cédula del atleta desde la URL
$cedula_atleta = isset($_GET['Atleta']) ? $_GET['Atleta'] : '';

// Si no hay cédula, mostrar mensaje
if(empty($cedula_atleta)) {
    echo '<div class="alert alert-warning">Selecciona un atleta para ver sus partidos</div>';
    exit();
}

// Buscar el atleta en el array global
$atleta = null;
foreach($todos_los_atletas as $a) {
    if($a['atleta_cedula'] == $cedula_atleta) {
        $atleta = $a;
        break;
    }
}

if(!$atleta) {
    echo '<div class="alert alert-danger">Atleta no encontrado</div>';
    exit();
}

// Obtener resumen de partidos del atleta
$resumen = resumenPartidosAtleta($cedula_atleta, $todas_las_participaciones, $todos_los_partidos);

// Estadísticas generales
$total_partidos = $resumen['total_partidos'];
$total_goles = $resumen['goles'];
$total_asistencias = $resumen['asistencias'];
$total_minutos = $resumen['minutos'];
$tarjetas = $resumen['tarjetas'];
$promedio_calificacion = $resumen['promedio_calificacion'];
$partidos_jugados = $resumen['partidos'];

// Calcular partidos ganados, perdidos y empatados
$ganados = 0;
$perdidos = 0;
$empatados = 0;
$por_cargar = 0;

foreach($partidos_jugados as $pj) {
    $status = $pj['partido']['status'];
    if($status == 'Ganado') $ganados++;
    elseif($status == 'Perdido') $perdidos++;
    elseif($status == 'Empatado') $empatados++;
    elseif($status == 'Por Cargar') $por_cargar++;
}

$url_volver = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php?View=DashboardRendimiento&Atleta=' . $cedula_atleta;
?>


<div class="container contenido">

    <!-- ===== TOP HEADER CON BOTÓN VOLVER ===== -->
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
    
    <!-- ===== HEADER DEL ATLETA ===== -->
    
        <div class="atleta-header">
        <div class="atleta-avatar">
            <?php 
            echo strtoupper(substr($atleta['atleta_primer_nombre'] ?? 'A', 0, 1) . substr($atleta['atleta_primer_apellido'] ?? 'A', 0, 1));
            ?>
        </div>
        <div class="atleta-info">
            <h5><?php echo $atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']; ?></h5>
            <small>
                <i class="bi bi-tag me-1"></i><?php echo $atleta['atleta_categoria'] ?? 'Sin categoría'; ?>
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
            <div class="item morado">
                <span class="numero"><?php echo $promedio_calificacion; ?></span>
                <span class="etiqueta">Promedio</span>
            </div>
        </div>
    </div>

    <!-- ===== TARJETAS DE ESTADÍSTICAS ===== -->
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
                <div class="icono" style="background: rgba(240, 58, 13, 0.09); color: #a50b0b;">
                    <i class="bi bi-people"></i>
                </div>
                <span class="valor"><?php echo $total_asistencias; ?></span>
                <p class="nombre">Asistencias</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card-estadistica">
                <div class="icono" style="background: rgba(255,193,7,0.09); color: #ffc415;">
                    <i class="bi bi-clock"></i>
                </div>
                <span class="valor"><?php echo $total_minutos; ?>'</span>
                <p class="nombre">Minutos Jugados</p>
            </div>
        </div>
    </div>

    <!-- ===== LISTA DE PARTIDOS ===== -->
    <h5 class="text-white mb-3">
        <i class="bi bi-list-ul me-2 text-warning"></i>
        Historial de Partidos
        <span class="text-white ms-2" style="font-size:0.8rem;">(<?php echo $total_partidos; ?> partidos)</span>
    </h5>

    <?php if(empty($partidos_jugados)): ?>
        <div class="empty-state">
            <i class="bi bi-trophy"></i>
            <h5>Sin partidos registrados</h5>
            <p>Este atleta aún no ha participado en ningún partido</p>
        </div>
    <?php else: ?>
        <?php foreach($partidos_jugados as $pj): 
            $partido = $pj['partido'];
            $participacion = $pj['participacion'];
            $status = $partido['status'];
            
            // Clase del badge
            $badge_class = 'badge-';
            if($status == 'Ganado') $badge_class .= 'ganado';
            elseif($status == 'Perdido') $badge_class .= 'perdido';
            elseif($status == 'Empatado') $badge_class .= 'empatado';
            elseif($status == 'Por Cargar') $badge_class .= 'por-cargar';
            elseif($status == 'Cancelado') $badge_class .= 'cancelado';
            else $badge_class .= 'por-jugar';
            
            // Formatear fecha
            $fecha = DateTime::createFromFormat('d/m/Y', $partido['fecha']);
            $fecha_formateada = $fecha ? $fecha->format('d/m/Y') : $partido['fecha'];
        ?>
        <div class="partido-card">
            <div class="partido-header">
                <div>
                    <span class="fecha">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?php echo $fecha_formateada; ?>
                    </span>
                    <span class="rival ms-3">
                        <i class="bi bi-shield"></i>
                        vs <?php echo $partido['rival']; ?>
                    </span>
                    <span class="ms-2 text-white" style="font-size:0.75rem;">
                        (<?php echo $partido['categoria']; ?>)
                    </span>
                </div>
                <div>
                    <span class="badge-estado <?php echo $badge_class; ?>">
                        <?php echo $status; ?>
                    </span>
                    <span class="calificacion ms-2">
                        <?php echo $participacion['calificacion']; ?>
                    </span>
                </div>
            </div>
            
            <div class="estadisticas-jugador">
                <span class="stat">
                    <i class="bi bi-clock"></i>
                    <span class="numero"><?php echo $participacion['minutos_jugados']; ?>'</span>
                </span>
                <span class="stat">
                    <i class="bi bi-emoji-sunglasses text-success"></i>
                    <span class="numero"><?php echo $participacion['goles']; ?></span> goles
                </span>
                <span class="stat">
                    <i class="bi bi-people text-info"></i>
                    <span class="numero"><?php echo $participacion['asistencias']; ?></span> asistencias
                </span>
                <?php if($participacion['tarjetas_amarillas'] > 0): ?>
                <span class="stat">
                    <i class="bi bi-square text-warning"></i>
                    <span class="numero"><?php echo $participacion['tarjetas_amarillas']; ?></span>
                </span>
                <?php endif; ?>
                <?php if($participacion['tarjetas_rojas'] > 0): ?>
                <span class="stat">
                    <i class="bi bi-square-fill text-danger"></i>
                    <span class="numero"><?php echo $participacion['tarjetas_rojas']; ?></span>
                </span>
                <?php endif; ?>
                <span class="stat">
                    <i class="bi bi-person"></i>
                    <?php echo $participacion['posicion']; ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    
</div>

