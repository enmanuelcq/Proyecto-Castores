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

    <?php if(isset($_GET['success']) && isset($_GET['mensaje'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        <?php echo htmlspecialchars($_GET['mensaje']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>
    <?php if(isset($_GET['mensaje'])): ?>
            <script>
                setTimeout(function() {
                    window.location.href = "index.php?View=Consulta_atletas";
                }, 1500); // Redirigir después de 1.5 segundos
            </script>
    <?php endif; ?>

    <div class="athlete-list">
        <?php foreach ($todos_los_atletas as $item): 
            // Extraer objetos del array $item
            $atleta = $item['atleta'];               // objeto Atleta
            $representante = $item['representante']; // objeto Representante o null
            $categoria = $item['categoria_nombre'];  // string
            $numeroFranela = $item['numero_franela']; // string
            $uniforme = $item['uniforme'] ?? null;   // objeto Uniforme o null

            // Datos del atleta usando getters
            $atleta_id = $atleta->getAtletaId();
            $nombre_completo = $atleta->getPrimerNombre() . ' ' . $atleta->getPrimerApellido();
            
        ?>
        
        <!-- TARJETA DEL ATLETA -->
        <div class="athlete-capsule d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-4">
                <div class="avatar-box">👤</div>
                <div>
                    <h3 class="athlete-name"><?php echo htmlspecialchars($nombre_completo); ?></h3>
                    <span class="athlete-meta">Categoria: <?php echo htmlspecialchars($categoria); ?></span> &nbsp;|&nbsp; 
                    <span class="athlete-meta text-uppercase"><?php echo htmlspecialchars($atleta->getSexo()); ?></span>
                    <span class="badge bg-secondary ms-2">#<?php echo htmlspecialchars($numeroFranela); ?></span>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <?php if(isset($_SESSION['usuario']) && $_SESSION['usuario']['id_rol'] == '2'): ?>
                    <button class="btn btn-action-green" data-bs-toggle="modal" data-bs-target="#modalVerMas<?php echo $atleta_id; ?>">
                        <i class="bi bi-eye me-1"></i>Ver más
                    </button>
                    <button class="btn btn-action-green" data-bs-toggle="modal" data-bs-target="#modalSolvencia<?php echo $atleta_id; ?>">
                        <i class="bi bi-cash-stack me-1"></i>Solvencia
                    </button>
                    <button class="btn btn-action-green" data-bs-toggle="modal" data-bs-target="#modalActualizar<?php echo $atleta_id; ?>">
                        <i class="bi bi-pencil me-1"></i>Actualizar
                    </button>
                    <button class="btn btn-action-red" data-bs-toggle="modal" data-bs-target="#modalEliminar<?php echo $atleta_id; ?>">
                        <i class="bi bi-trash me-1"></i>Eliminar
                    </button>
                <?php endif; ?>

                <?php if(isset($_SESSION['usuario']) && $_SESSION['usuario']['id_rol'] == '3'): ?>
                    <button class="btn btn-action-green" data-bs-toggle="modal" data-bs-target="#modalVerMas<?php echo $atleta_id; ?>">
                        <i class="bi bi-eye me-1"></i>Ver más
                    </button>
                    <a href="index.php?View=DashboardRendimiento&Atleta=<?php echo $atleta_id; ?>" class="btn btn-action-green">
                        <i class="bi bi-graph-up me-1"></i>Rendimiento
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- MODALES: aquí pasamos los datos correctos -->
        <!-- ============================================ -->
        <?php include './Public/Modals/modal_ver_mas.php'; ?>
        <?php //include './Public/Modals/modal_solvencia.php'; ?>
        <?php include './Public/Modals/modal_actualizar.php'; ?>
        <?php //include './Public/Modals/modal_eliminar.php'; ?>

        <?php endforeach; ?>
    </div>
</div>

<script src="./Public/Js/Consulta_atletas.js"></script>