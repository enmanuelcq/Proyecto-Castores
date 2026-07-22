<?php
// Controller/ConsultarAtleta_controller.php

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';

// Repositorios necesarios
require_once $root . '/Model/Repository/RepositoryAtleta.php';
require_once $root . '/Model/Repository/RepositoryAtletaRepresentante.php';
require_once $root . '/Model/Repository/RepositoryRepresentante.php';
require_once $root . '/Model/Repository/RepositoryCategoriaAtleta.php';
require_once $root . '/Model/Repository/RepositoryCategoria.php';
require_once $root . '/Model/Repository/RepositoryUniforme.php';
require_once $root . '/Model/Repository/RepositorySalud.php';
require_once $root . '/Model/Repository/RepositoryGrado.php';
require_once $root . '/Model/Repository/RepositoryColegio.php';
require_once $root . '/Model/Repository/RepositoryHobbieAtleta.php';
require_once $root . '/Model/Repository/RepositoryHobbie.php';

// Repositorios de solvencia
require_once $root . '/Model/Repository/RepositoryCuotaMensual.php';
require_once $root . '/Model/Repository/RepositoryPago.php';
require_once $root . '/Model/Repository/RepositoryPagoCuota.php';
require_once $root . '/Model/Repository/RepositoryOperacion.php';
require_once $root . '/Model/Repository/RepositoryTipoPago.php';

// ============================================================
// 1. INSTANCIAR REPOSITORIOS
// ============================================================
$repoAtleta = new RepositoryAtleta();
$repoAtletaRep = new RepositoryAtletaRepresentante();
$repoRepresentante = new RepositoryRepresentante();
$repoCategoriaAtleta = new RepositoryCategoriaAtleta();
$repoCategoria = new RepositoryCategoria();
$repoUniforme = new RepositoryUniforme();
$repoSalud = new RepositorySalud();
$repoGrado = new RepositoryGrado();
$repoColegio = new RepositoryColegio();
$repoHobbieAtleta = new RepositoryHobbieAtleta();
$repoHobbie = new RepositoryHobbie();

// Repositorios de solvencia
$repoCuota = new RepositoryCuotaMensual();
$repoPagoCuota = new RepositoryPagoCuota();
$repoPago = new RepositoryPago();
$repoOperacion = new RepositoryOperacion();
$repoTipoPago = new RepositoryTipoPago();

// ============================================================
// 2. FILTROS
// ============================================================
$estado = $_GET['estado'] ?? 'activos'; // activos, inactivos, todos
$categoriaFiltro = isset($_GET['categoria']) ? (int)$_GET['categoria'] : 0;
$busqueda = trim($_GET['buscar'] ?? '');

// ============================================================
// 3. OBTENER CATEGORÍAS PARA EL FILTRO
// ============================================================
$categorias_lista = $repoCategoria->findAll();

// ============================================================
// 4. OBTENER ATLETAS SEGÚN ESTADO
// ============================================================
if ($estado === 'inactivos') {
    $atletas = $repoAtleta->findAllInactive();
} elseif ($estado === 'todos') {
    $atletas = $repoAtleta->findAll();
} else {
    $atletas = $repoAtleta->findAllActive();
}

// ============================================================
// 5. FILTRAR POR CATEGORÍA
// ============================================================
if ($categoriaFiltro > 0) {
    $atletasFiltrados = [];
    foreach ($atletas as $atleta) {
        $categoriasAtleta = $repoCategoriaAtleta->findByAtleta($atleta->getAtletaId());
        foreach ($categoriasAtleta as $ca) {
            if ($ca->getCategoriaId() == $categoriaFiltro) {
                $atletasFiltrados[] = $atleta;
                break;
            }
        }
    }
    $atletas = $atletasFiltrados;
}

// ============================================================
// 6. FILTRAR POR BÚSQUEDA (nombre)
// ============================================================
if (!empty($busqueda)) {
    $atletasFiltrados = [];
    foreach ($atletas as $atleta) {
        $nombreCompleto = $atleta->getPrimerNombre() . ' ' . 
                         $atleta->getSegundoNombre() . ' ' . 
                         $atleta->getPrimerApellido() . ' ' . 
                         $atleta->getSegundoApellido();
        if (stripos($nombreCompleto, $busqueda) !== false) {
            $atletasFiltrados[] = $atleta;
        }
    }
    $atletas = $atletasFiltrados;
}

// ============================================================
// 7. CONSTRUIR ARRAY DE DATOS
// ============================================================
$todos_los_atletas = [];

foreach ($atletas as $atleta) {
    // --- Representante ---
    $relaciones = $repoAtletaRep->findByAtleta($atleta->getAtletaId());
    $representante = null;
    if (!empty($relaciones)) {
        $cedulaRep = $relaciones[0]->getCedulaRepresentante();
        $representante = $repoRepresentante->findByCedula($cedulaRep);
    }

    // --- Categoría ---
    $categoriaRelaciones = $repoCategoriaAtleta->findByAtleta($atleta->getAtletaId());
    $categoriaNombre = 'Sin categoría';
    $categoriaAnio = '';
    if (!empty($categoriaRelaciones)) {
        $cat = $repoCategoria->findById($categoriaRelaciones[0]->getCategoriaId());
        $categoriaNombre = $cat ? $cat->getSubCategoria() : 'Sin categoría';
        $categoriaAnio = $categoriaRelaciones[0]->getAnio();
    }

    // --- Uniforme ---
    $uniforme = $repoUniforme->findById($atleta->getUniformeId());
    $numeroFranela = $uniforme ? $uniforme->getFranelaNumero() : 'N/A';

    // --- Salud ---
    $salud = $repoSalud->findByAtletaId($atleta->getAtletaId());

    // --- Grado y Colegio ---
    $grados = $repoGrado->findByAtleta($atleta->getAtletaId());
    $grado = !empty($grados) ? $grados[0] : null;
    $colegio = null;
    if ($grado) {
        $colegio = $repoColegio->findById($grado->getColegioId());
    }

    // --- Hobbie ---
    $hobbieRelaciones = $repoHobbieAtleta->findByAtleta($atleta->getAtletaId());
    $hobbie = null;
    $horarioHobbie = '';
    if (!empty($hobbieRelaciones)) {
        $hobbie = $repoHobbie->findById($hobbieRelaciones[0]->getHobbieId());
        $horarioHobbie = $hobbieRelaciones[0]->getHorario();
    }

    // ============================================================
    // 8. SOLVENCIA (CORREGIDO)
    // ============================================================
    $cuotas = $repoCuota->findByAtleta($atleta->getAtletaId());
    $mesesSolvencia = [];
    $anioActual = date('Y');
    $mesActual = (int)date('m');

    for ($mes = 1; $mes <= 12; $mes++) {
        // Buscar cuota para este mes/año
        $cuota = null;
        foreach ($cuotas as $c) {
            if ($c->getMes() == $mes && $c->getAnio() == $anioActual) {
                $cuota = $c;
                break;
            }
        }

        $nombreMes = ucfirst(strftime('%B', mktime(0, 0, 0, $mes, 1, $anioActual)));
        $esFuturo = ($mes > $mesActual && $anioActual == date('Y'));

        // ===== INICIALIZAR VARIABLES CON VALORES POR DEFECTO =====
        $estadoCuota = 'Por Vencer';
        $monto = 0;
        $abonado = 0;
        $saldo = 0;
        $tipoPagoNombre = '';
        $referencia = '';
        $cuotaId = null;
        $fechaPago = '';
        $banco = '';

        if ($cuota) {
            $abonado = $repoPagoCuota->sumAbonadoByCuota($cuota->getId());
            $monto = $cuota->getMontoEstablecido();
            $saldo = $monto - $abonado;

            // Determinar estado
            if ($saldo <= 0) {
                $estadoCuota = 'Pagado';
                // Obtener último pago asociado para mostrar detalles
                $pagosCuota = $repoPagoCuota->findByCuota($cuota->getId());
                $ultimoPago = !empty($pagosCuota) ? $pagosCuota[0] : null;
                if ($ultimoPago) {
                    $pago = $repoPago->findById($ultimoPago->getPagoId());
                    if ($pago) {
                        $fechaPagoRaw = $pago->getFechaPago();
                        if ($fechaPagoRaw) {
                            $fechaPago = date('Y-m-d', strtotime($fechaPagoRaw));
                        }
                        $operaciones = $repoOperacion->findByPago($pago->getId());
                        if (!empty($operaciones)) {
                            $banco = $operaciones[0]->getBanco() ?? '';
                            $tipoPago = $repoTipoPago->findById($operaciones[0]->getTipoPagoId());
                            $tipoPagoNombre = $tipoPago ? $tipoPago->getTipoPago() : '';
                            $referencia = $operaciones[0]->getReferencia() ?? '';
                        }
                    }
                }
            } elseif ($abonado > 0 && $saldo > 0) {
                $estadoCuota = 'Parcial';
            } else {
                $estadoCuota = 'Pendiente';
            }
            $cuotaId = $cuota->getId();
        }

        // Agregar el mes al array
        $mesesSolvencia[] = [
            'mes' => $mes,
            'nombre_mes' => $nombreMes,
            'estado' => $estadoCuota,
            'monto' => $monto,
            'abonado' => $abonado,
            'saldo' => $saldo,
            'tipo_pago' => $tipoPagoNombre,
            'referencia' => $referencia,
            'cuota_id' => $cuotaId,
            'es_futuro' => $esFuturo,          // ← COMA AGREGADA
            'fecha_pago' => $fechaPago,
            'banco' => $banco,
        ];
    }

    // ============================================================
    // 9. AGREGAR ATLETA AL ARRAY PRINCIPAL
    // ============================================================
    $todos_los_atletas[] = [
        'atleta' => $atleta,
        'representante' => $representante,
        'categoria_nombre' => $categoriaNombre,
        'categoria_anio' => $categoriaAnio,
        'numero_franela' => $numeroFranela,
        'uniforme' => $uniforme,
        'salud' => $salud,
        'grado' => $grado,
        'colegio' => $colegio,
        'hobbie' => $hobbie,
        'horario_hobbie' => $horarioHobbie,
        'solvencia' => $mesesSolvencia,
    ];
}