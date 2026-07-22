<?php
$root = dirname(__DIR__);
require_once $root . '/Controller/ConsultarAtleta_controller.php';

if (!isset($todos_los_atletas) || !is_array($todos_los_atletas)) {
    $todos_los_atletas = [];
}
?>
<div class="container py-5">
    <header class="mb-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h1 class="header-title text-uppercase m-0">Control de Registro de Jugadores</h1>
                <p class="m-0 opacity-90">Verifica los datos de los atletas activos. El orden fuera de la cancha asegura el éxito dentro de ella.</p>
            </div>
            <div>
                <span class="badge bg-primary fs-6 px-4 py-2">
                    <i class="bi bi-people-fill me-2"></i>Total: <?php echo count($todos_los_atletas); ?> Atletas
                </span>
            </div>
        </div>
    </header>

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

    <!-- ===== FILTROS Y BÚSQUEDA ===== -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="index.php" class="row g-3" id="filtrosForm">
                <input type="hidden" name="View" value="Consulta_atletas">
                
                <!-- Buscador por nombre -->
                <div class="col-md-4">
                    <label class="form-label">Buscar por nombre</label>
                    <div class="input-group">
                        <input type="text" name="buscar" class="form-control" placeholder="Ej. Juan Pérez" 
                               value="<?php echo htmlspecialchars($_GET['buscar'] ?? ''); ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>

                <!-- Filtro por categoría -->
                <div class="col-md-3">
                    <label class="form-label">Categoría</label>
                    <select name="categoria" class="form-select" onchange="this.form.submit()">
                        <option value="0">Todas las categorías</option>
                        <?php foreach ($categorias_lista as $cat): ?>
                            <option value="<?php echo $cat->getId(); ?>" 
                                <?php echo (isset($_GET['categoria']) && $_GET['categoria'] == $cat->getId()) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat->getSubCategoria()); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Filtro por estado -->
                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" class="form-select" onchange="this.form.submit()">
                        <option value="activos" <?php echo ($estado === 'activos') ? 'selected' : ''; ?>>Solo Activos</option>
                        <option value="inactivos" <?php echo ($estado === 'inactivos') ? 'selected' : ''; ?>>Solo Inactivos</option>
                        <option value="todos" <?php echo ($estado === 'todos') ? 'selected' : ''; ?>>Todos</option>
                    </select>
                </div>

                <!-- Botón de limpiar filtros -->
                <div class="col-md-2 d-flex align-items-end">
                    <a href="index.php?View=Consulta_atletas" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-counterclockwise"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="form-rest"></div>

    <!-- ===== LISTA DE ATLETAS ===== -->
    <div class="athlete-list">
        <?php if (empty($todos_los_atletas)): ?>
            <div class="alert alert-info text-center">
                <i class="bi bi-info-circle-fill me-2"></i>
                No se encontraron atletas con los filtros aplicados.
            </div>
        <?php else: ?>
            <?php foreach ($todos_los_atletas as $item): 
                $atleta = $item['atleta'];               
                $representante = $item['representante'] ?? null; 
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
                $nombre_completo = trim(
                    $atleta->getPrimerNombre() . ' ' . 
                    $atleta->getSegundoNombre() . ' ' . 
                    $atleta->getPrimerApellido() . ' ' . 
                    $atleta->getSegundoApellido()
                );
                $esActivo = $atleta->getAtletaStatus();
                
                $fechaVigencia = '';
                if ($salud && $salud->getVigencia()) {
                    $fechaVigencia = date('Y-m-d', strtotime($salud->getVigencia()));
                }

                $solvencia = $item['solvencia'] ?? [];
            ?>
            
            <!-- TARJETA DEL ATLETA -->
            <div class="athlete-capsule d-flex align-items-center justify-content-between flex-wrap gap-3 
                        <?php echo !$esActivo ? 'border border-danger bg-light' : ''; ?>">
                <div class="d-flex align-items-center gap-4">
                    <div class="avatar-box">👤</div>
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
                            <!-- Atleta Activo -->
                            <button class="btn btn-action-green" data-bs-toggle="modal" data-bs-target="#modalActualizar<?php echo $atleta_id; ?>">
                                <i class="bi bi-pencil me-1"></i>Actualizar
                            </button>
                            <button class="btn btn-action-red" data-bs-toggle="modal" data-bs-target="#modalDesactivar<?php echo $atleta_id; ?>">
                                <i class="bi bi-trash me-1"></i>Eliminar
                            </button>
                        <?php else: ?>
                            <!-- Atleta Inactivo: Botón para abrir modal de reactivación -->
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

            <!-- ===== MODALES ===== -->
            <?php include './Public/Modals/modal_ver_mas.php'; ?>
            <?php include './Public/Modals/modal_solvencia.php'; ?>
            <?php include './Public/Modals/modal_registrar_pago_global.php'; ?>
            
            <?php if ($esActivo): ?>
                <?php include './Public/Modals/modal_actualizar.php'; ?>
                <?php include './Public/Modals/modal_eliminar.php'; ?>
            <?php else: ?>
                <?php include './Public/Modals/modal_reactivar.php'; ?>
            <?php endif; ?>

            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<a href="index.php?View=configuracion_cuotas" class="btn btn-outline-primary">
    <i class="bi bi-gear me-1"></i> Configurar cuotas
</a>

<script src="./Public/Js/Consulta_atletas.js"></script>
