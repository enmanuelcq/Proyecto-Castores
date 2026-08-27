<?php

require_once './Model/Pruebas.php';
require_once './Model/Repository/RepositoryAtleta.php';
require_once './Model/Repository/RepositoryPrueba.php';
require_once './Model/Repository/RepositoryCategoria.php';

$atleta_id = isset($_GET['id']) ? trim($_GET['id']) : '';

if(empty($atleta_id)) {
    echo '<div class="alert alert-warning">Selecciona un atleta para ver sus pruebas</div>';
    exit();
}

$repoAtleta = new RepositoryAtleta();
$repoPrueba = new RepositoryPrueba();

$atletaEntity = $repoAtleta->findById($atleta_id);

if(!$atletaEntity) {
    echo '<div class="alert alert-danger">Atleta no encontrado</div>';
    exit();
}

$atletaId = $atletaEntity->getAtletaId();
$categoriaId = $repoAtleta->getCategoriaId($atletaId);
$categoria_atleta = $repoAtleta->getCategoriaAtleta($atletaId) ?? 'Sin categoría';

$atleta = [
    'atleta_id' => $atletaId,
    'atleta_cedula' => $atletaEntity->getCedula(),
    'atleta_primer_nombre' => $atletaEntity->getPrimerNombre(),
    'atleta_primer_apellido' => $atletaEntity->getPrimerApellido(),
    'atleta_categoria' => $categoria_atleta,
    'categoria_id' => $categoriaId,
];

$pruebas_atleta = $repoPrueba->findAllByAtleta($atletaId);
$total_pruebas = $repoPrueba->countByAtleta($atletaId);

$ultima_prueba_general = null;
$fecha_ultima_global = '';
$tipo_ultima_global = '';

foreach($pruebas_atleta as $tipo => $pruebas) {
    if(!empty($pruebas)) {
        usort($pruebas, function($a, $b) use ($tipo) {
            $fecha_a = obtenerFechaPrueba($a);
            $fecha_b = obtenerFechaPrueba($b);
            return strtotime($fecha_b) - strtotime($fecha_a);
        });
        $ultima = $pruebas[0];
        $fecha = obtenerFechaPrueba($ultima);
        if(!$ultima_prueba_general || ($fecha && strtotime($fecha) > strtotime($fecha_ultima_global))) {
            $ultima_prueba_general = $ultima;
            $fecha_ultima_global = $fecha;
            $tipo_ultima_global = $tipo;
        }
    }
}

$datos_pruebas_modal = [];
$categorias_pruebas = [];
foreach($tipos_prueba as $clave => $tipo) {
    $pruebas_tipo = $pruebas_atleta[$clave] ?? [];
    if(!empty($pruebas_tipo)) {
        usort($pruebas_tipo, function($a, $b) {
            $fecha_a = obtenerFechaPrueba($a);
            $fecha_b = obtenerFechaPrueba($b);
            return strtotime($fecha_b) - strtotime($fecha_a);
        });
        foreach($pruebas_tipo as $p) {
            $catId = $p['categoria_id'] ?? null;
            if($catId && !isset($categorias_pruebas[$catId])) {
                $categorias_pruebas[$catId] = null;
            }
        }
    }
    $datos_pruebas_modal[$clave] = $pruebas_tipo;
}

$repoCategoria = new RepositoryCategoria();
$categorias_nombres = [];
foreach(array_keys($categorias_pruebas) as $catId) {
    $cat = $repoCategoria->findById((int)$catId);
    $categorias_nombres[$catId] = $cat ? $cat->getSubCategoria() : 'Categoría ' . $catId;
}
arsort($categorias_nombres);

$url_volver = 'index.php?View=DashboardRendimiento&id=' . urlencode($atletaId);
?>

<div class="container contenido">

<?php if(isset($_GET['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo htmlspecialchars($_GET['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if(isset($_GET['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?php echo htmlspecialchars($_GET['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
    
    <div class="top-header">
        <h4 style="color: var(--text-primary);">
            <i class="bi bi-clipboard2-check me-2" style="color: var(--primary);"></i>
            Pruebas de <?php echo $atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']; ?>
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
    
    <?php if($ultima_prueba_general): 
        $nombre_tipo = isset($tipos_prueba[$tipo_ultima_global]) ? $tipos_prueba[$tipo_ultima_global]['nombre'] : $tipo_ultima_global;
        $fecha = obtenerFechaPrueba($ultima_prueba_general);
        $valor = obtenerValorPrincipal($ultima_prueba_general, $tipo_ultima_global);
    ?>
    <div class="ultima-prueba">
        <div class="info">
            <span class="fecha"><?php echo $fecha ? date('d/m/Y', strtotime($fecha)) : 'N/A'; ?></span>
            <span class="tipo"><?php echo $nombre_tipo; ?></span>
            <span class="valor">
                <?php echo formatearValor($valor, $tipo_ultima_global); ?>
            </span>
        </div>
        <div>
            <span style="font-size:0.8rem;color: var(--text-muted);">
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
    
    <div class="row g-3">
        <?php foreach($tipos_prueba as $clave => $tipo): 
            $pruebas_tipo = $pruebas_atleta[$clave] ?? [];
            $total = count($pruebas_tipo);
            
            $ultima = null;
            if(!empty($pruebas_tipo)) {
                usort($pruebas_tipo, function($a, $b) {
                    $fecha_a = obtenerFechaPrueba($a);
                    $fecha_b = obtenerFechaPrueba($b);
                    return strtotime($fecha_b) - strtotime($fecha_a);
                });
                $ultima = $pruebas_tipo[0];
            }
            
            $fecha_ultima = $ultima ? obtenerFechaPrueba($ultima) : null;
            
        ?>
        <div class="col-lg-4 col-md-6">
            <div class="card-prueba" data-tipo="<?php echo $clave; ?>">
                <div class="icono">
                    <i class="<?php echo $tipo['icono']; ?>"></i>
                </div>
                <h6 class="nombre"><?php echo $tipo['nombre']; ?></h6>
                
                <?php if($ultima): ?>
                    <p class="fecha-ultima">
                        <i class="bi bi-calendar3"></i>
                        <?php echo $fecha_ultima ? date('d/m/Y', strtotime($fecha_ultima)) : 'N/A'; ?>
                    </p>
                <?php else: ?>
                    <p class="fecha-ultima" style="color: #9ca3af;">Sin pruebas registradas</p>
                <?php endif; ?>
                
                <div class="total-badge">
                    <?php echo $total; ?> prueba(s)
                </div>
                
                <div class="footer">
                    <button class="btn btn-outline-success btn-sm" 
                            onclick="abrirModalDetalle('<?php echo $clave; ?>', '<?php echo $atletaId; ?>', '<?php echo addslashes($atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']); ?>')">
                        <i class="bi bi-eye me-1"></i>Ver todas
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="abrirModalRegistro('<?php echo $clave; ?>', '<?php echo $atletaId; ?>', '<?php echo addslashes($atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']); ?>', '<?php echo $atleta['categoria_id'] ?? ''; ?>')">
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

<script>
    const datosPruebas = <?php echo json_encode($datos_pruebas_modal); ?>;
    const categoriasMap = <?php echo json_encode($categorias_nombres); ?>;
    const atletaIdGlobal = '<?php echo $atletaId; ?>';
    const categoriaIdGlobal = '<?php echo $categoriaId ?? ''; ?>';
</script>
<script src="./Public/Js/Pruebas.js?v=<?php echo time(); ?>"></script>
<script>
    setTimeout(function() {
        document.querySelectorAll('.container .alert').forEach(function(el) {
            var a = bootstrap.Alert.getOrCreateInstance(el);
            a.close();
        });
    }, 5000);
</script>
