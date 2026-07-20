<?php

require_once './Model/Pruebas.php';
require_once './Model/Atletas.php';

// Obtener la cédula del atleta desde la URL
$cedula_atleta = isset($_GET['Atleta']) ? $_GET['Atleta'] : '';

// Si no hay cédula, mostrar mensaje
if(empty($cedula_atleta)) {
    echo '<div class="alert alert-warning">Selecciona un atleta para ver sus pruebas</div>';
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

// Obtener todas las pruebas del atleta
$pruebas_atleta = obtenerPruebasPorAtleta($cedula_atleta, $todas_las_pruebas);

// Calcular total de pruebas
$total_pruebas = 0;
foreach($pruebas_atleta as $tipo => $pruebas) {
    $total_pruebas += count($pruebas);
}

// Encontrar la última prueba general
$ultima_prueba_general = null;
$fecha_ultima = '';
$tipo_ultima = '';
foreach($pruebas_atleta as $tipo => $pruebas) {
    if(!empty($pruebas)) {
        usort($pruebas, function($a, $b) {
            $fecha_a = isset($a['fecha']) ? $a['fecha'] : (isset($a['pruebaf_fecha']) ? $a['pruebaf_fecha'] : (isset($a['pruebav_fecha']) ? $a['pruebav_fecha'] : ''));
            $fecha_b = isset($b['fecha']) ? $b['fecha'] : (isset($b['pruebaf_fecha']) ? $b['pruebaf_fecha'] : (isset($b['pruebav_fecha']) ? $b['pruebav_fecha'] : ''));
            return strtotime($fecha_b) - strtotime($fecha_a);
        });
        $ultima = $pruebas[0];
        $fecha = isset($ultima['fecha']) ? $ultima['fecha'] : (isset($ultima['pruebaf_fecha']) ? $ultima['pruebaf_fecha'] : (isset($ultima['pruebav_fecha']) ? $ultima['pruebav_fecha'] : ''));
        if(!$ultima_prueba_general || strtotime($fecha) > strtotime($fecha_ultima)) {
            $ultima_prueba_general = $ultima;
            $fecha_ultima = $fecha;
            $tipo_ultima = $tipo;
        }
    }
}


// ===== GENERAR DATOS PARA EL MODAL =====
// Pre-cargar todos los datos de pruebas para cada tipo
$datos_pruebas_modal = [];
foreach($tipos_prueba as $clave => $tipo) {
    $pruebas_tipo = isset($pruebas_atleta[$clave]) ? $pruebas_atleta[$clave] : [];
    if(!empty($pruebas_tipo)) {
        usort($pruebas_tipo, function($a, $b) {
            $fecha_a = isset($a['fecha']) ? $a['fecha'] : (isset($a['pruebaf_fecha']) ? $a['pruebaf_fecha'] : (isset($a['pruebav_fecha']) ? $a['pruebav_fecha'] : ''));
            $fecha_b = isset($b['fecha']) ? $b['fecha'] : (isset($b['pruebaf_fecha']) ? $b['pruebaf_fecha'] : (isset($b['pruebav_fecha']) ? $b['pruebav_fecha'] : ''));
            return strtotime($fecha_b) - strtotime($fecha_a);
        });
    }
    $datos_pruebas_modal[$clave] = $pruebas_tipo;
}
$url_volver = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php?View=DashboardRendimiento&Atleta=' . $cedula_atleta;
?>


        >

<div class="container contenido">
    
        <div class="top-header">
        <h4 class="text-white mb-3">
            <i class="bi bi-trophy me-2"></i>
            Partidos de <?php echo $atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']; ?>
        </h4>
        <a href="<?php echo $url_volver; ?>" class="btn-volver">
            <i class="bi bi-arrow-left"></i>
            Volver
        </a>
        </div>
    
    <!-- HEADER DEL ATLETA -->
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
            </small>
        </div>
        
        <div class="atleta-resumen">
            <div class="item">
                <span class="numero"><?php echo $total_pruebas; ?></span>
                <span class="etiqueta">Total Pruebas</span>
            </div>
            <div class="item">
                <span class="numero"><?php 
                    $tipos_con_pruebas = 0;
                    foreach($pruebas_atleta as $pruebas) {
                        if(!empty($pruebas)) $tipos_con_pruebas++;
                    }
                    echo $tipos_con_pruebas; 
                ?></span>
                <span class="etiqueta">Tipos Realizados</span>
            </div>
            <?php if($ultima_prueba_general): 
                $fecha = obtenerFechaPrueba($ultima_prueba_general);
            ?>
            <div class="item">
                <span class="numero" style="font-size:1rem;color:#fff;">
                    <?php echo $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A'; ?>
                </span>
                <span class="etiqueta">Última Prueba</span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- ÚLTIMA PRUEBA GENERAL -->
    <?php if($ultima_prueba_general): 
        $nombre_tipo = isset($tipos_prueba[$tipo_ultima]) ? $tipos_prueba[$tipo_ultima]['nombre'] : $tipo_ultima;
        $fecha = obtenerFechaPrueba($ultima_prueba_general);
        $valor = obtenerValorPrincipal($ultima_prueba_general, $tipo_ultima);
    ?>
    <div class="ultima-prueba">
        <div class="info">
            <span class="fecha"><?php echo $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A'; ?></span>
            <span class="tipo"><?php echo $nombre_tipo; ?></span>
            <span class="valor">
                <?php echo formatearValor($valor, $tipo_ultima); ?>
            </span>
        </div>
        <div>
            <span class="text-white" style="font-size:0.8rem;">
                <i class="bi bi-clock me-1"></i>Última prueba realizada
            </span>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-info text-center">
        <i class="bi bi-info-circle me-2"></i>
        Este atleta aún no tiene pruebas registradas
    </div>
    <?php endif; ?>
    
    <!-- GRID DE PRUEBAS -->
    <div class="row g-3">
        <?php foreach($tipos_prueba as $clave => $tipo): 
            $pruebas_tipo = isset($pruebas_atleta[$clave]) ? $pruebas_atleta[$clave] : [];
            $total = count($pruebas_tipo);
            
            $ultima = null;
            if(!empty($pruebas_tipo)) {
                usort($pruebas_tipo, function($a, $b) {
                    $fecha_a = isset($a['fecha']) ? $a['fecha'] : (isset($a['pruebaf_fecha']) ? $a['pruebaf_fecha'] : (isset($a['pruebav_fecha']) ? $a['pruebav_fecha'] : ''));
                    $fecha_b = isset($b['fecha']) ? $b['fecha'] : (isset($b['pruebaf_fecha']) ? $b['pruebaf_fecha'] : (isset($b['pruebav_fecha']) ? $b['pruebav_fecha'] : ''));
                    return strtotime($fecha_b) - strtotime($fecha_a);
                });
                $ultima = $pruebas_tipo[0];
            }
            
            $valor_principal = obtenerValorPrincipal($ultima, $clave);
            $valor_formateado = formatearValor($valor_principal, $clave);
            $fecha_ultima = $ultima ? obtenerFechaPrueba($ultima) : null;
            
            $color_class = 'text-' . $tipo['color'];
            $bg_class = 'bg-' . $tipo['color'];
            if($tipo['color'] == 'purple') { $color_class = 'text-purple'; $bg_class = 'bg-purple'; }
            if($tipo['color'] == 'orange') { $color_class = 'text-orange'; $bg_class = 'bg-orange'; }
            if($tipo['color'] == 'cyan') { $color_class = 'text-cyan'; $bg_class = 'bg-cyan'; }
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="card-prueba">
                <div class="icono <?php echo $bg_class; ?> <?php echo $color_class; ?>">
                    <i class="<?php echo $tipo['icono']; ?>"></i>
                </div>
                <h6 class="nombre"><?php echo $tipo['nombre']; ?></h6>
                
                <?php if($ultima): ?>
                    <span class="valor-principal"><?php echo $valor_formateado; ?></span>
                    <p class="descripcion">
                        <i class="bi bi-calendar3 me-1"></i>
                        <?php echo $fecha_ultima ? date('d/m/Y', strtotime($fecha_ultima)) : 'N/A'; ?>
                    </p>
                <?php else: ?>
                    <span class="valor-principal" style="color: rgba(255, 255, 255, 0.74);">—</span>
                    <p class="descripcion">Sin pruebas registradas</p>
                <?php endif; ?>
                
                <div class="total-badge">
                    <?php echo $total; ?> prueba(s) realizada(s)
                </div>
                
                <div class="footer">
                    <button class="btn btn-outline-light btn-sm" 
                            onclick="abrirModalDetalle('<?php echo $clave; ?>', '<?php echo $cedula_atleta; ?>', '<?php echo addslashes($atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']); ?>')">
                        <i class="bi bi-eye me-1"></i>Ver todas
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="abrirModalRegistro('<?php echo $clave; ?>', '<?php echo $cedula_atleta; ?>', '<?php echo addslashes($atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']); ?>')">
                    <i class="bi bi-plus-circle me-1"></i>Registrar
                </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
</div>
<?php include './Public/Modals/modal_detalle_pruebas.php'; ?>
<?php include './Public/Modals/modalRegistrarPrueba.php'; ?>


    
</div>

<script>
    const datosPruebas = <?php 
    $data = [];
    foreach($datos_pruebas_modal as $tipo => $pruebas) {
        $data[$tipo] = $pruebas;
    }
    echo json_encode($data);
?>;
</script>
<script src= "./Public/Js/Pruebas.js"></script>
