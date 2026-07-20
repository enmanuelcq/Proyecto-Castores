<?php
// ============================================
// /vistas/admin_dashboard.php
// DASHBOARD DEL ADMINISTRADOR
// ============================================

// 1. INCLUIR TODOS LOS MODELOS
require_once './Model/Atletas.php';
require_once './Model/Solvencia.php';
require_once './Model/Entrenamiento.php';
require_once './Model/Asistencias.php';
require_once './Model/Pruebas.php';
require_once './Model/Usuarios.php';

// 2. INCLUIR EL MODELO DE REPORTES
require_once './Model/ReportesAdministrador.php';

// 3. GENERAR REPORTES
$estadisticas = obtenerEstadisticasGenerales(
    $todos_los_atletas,
    $todas_las_solvencias,
    $todos_los_entrenamientos,
    $toda_la_asistencia,
    $todas_las_pruebas
);

$morosos = obtenerAtletasMorosos($todas_las_solvencias, $todos_los_atletas, 2);
$asistencia_categoria = obtenerAsistenciaPorCategoria($todos_los_entrenamientos);
$top_asistencia = obtenerTopAsistencia($toda_la_asistencia, $todos_los_atletas, 5);
$resumen_entrenadores = obtenerResumenEntrenadores($todos_los_usuarios, $todos_los_entrenamientos);
$frecuencia_pruebas = obtenerFrecuenciaPruebas($todas_las_pruebas, $todos_los_atletas);
$solvencia_categoria = obtenerSolvenciaPorCategoria($todas_las_solvencias, $todos_los_atletas);

// 4. OBTENER DATOS PARA REPORTES DE SOLVENCIA
$estadisticas_solvencia = obtenerEstadisticasSolvencia($todas_las_solvencias);
$mes_actual = obtenerMesActualReporte();
$anio_actual = obtenerAnioActual();
$meses_activos = obtenerMesesActivosReportes();
$rango_meses = obtenerRangoMesesReporte();

// 5. DATOS PARA GRÁFICOS
$categorias = array_keys($estadisticas['atletas']['por_categoria']);
$cantidades = array_values($estadisticas['atletas']['por_categoria']);
$solvencia_data = $estadisticas['solvencia']['estados'];
?>


<div class="container contenido">
    
    <!-- ===== HEADER ===== -->
    <div class="page-header">
        <div>
            <h4>
                <i class="bi bi-speedometer2 me-2"></i>
                Dashboard Administrativo
            </h4>
            <p class="text-white-50 m-0 small">
                Reporte al <?php echo $mes_actual . ' ' . $anio_actual; ?> 
                <span class="text-white-50 ms-2">|</span>
                <span class="text-white-50 small">
                    <i class="bi bi-calendar-check me-1"></i>
                    <?php echo count($meses_activos); ?> meses procesados
                </span>
                <span class="text-white-50 ms-2">|</span>
                <span class="text-white-50 small">
                    <i class="bi bi-people me-1"></i>
                    <?php echo $estadisticas['atletas']['total']; ?> atletas
                </span>
            </p>
        </div>
        <div>
            <span class="text-white-50 small">
                <i class="bi bi-calendar3 me-1"></i>
                <?php echo date('d/m/Y'); ?>
            </span>
        </div>
    </div>

    <!-- ===== TARJETAS DE KPIs ===== -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(13,110,253,0.12); color: #0d6efd;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <span class="valor"><?php echo $estadisticas['atletas']['total']; ?></span>
                <p class="etiqueta">Total Atletas</p>
                <p class="sub-info">
                    <?php echo count($estadisticas['atletas']['por_categoria']); ?> categorías
                </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(25,135,84,0.12); color: #198754;">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <span class="valor"><?php echo $estadisticas_solvencia['Pagado']; ?></span>
                <p class="etiqueta">Meses Pagados</p>
                <p class="sub-info">
                    <?php echo $estadisticas_solvencia['Pendiente']; ?> pendientes
                </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(175, 158, 6, 0.12); color: #ffe713;">
                    <i class="bi bi-universal-access"></i>
                </div>
                <span class="valor"><?php echo $estadisticas['entrenamientos']['total']; ?></span>
                <p class="etiqueta">Entrenamientos</p>
                <p class="sub-info">
                    <?php echo $estadisticas['entrenamientos']['porcentaje_asistencia']; ?>% asistencia promedio
                </p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(220,53,69,0.12); color: #dc3545;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <span class="valor" style="color: #dc3545;"><?php echo count($morosos); ?></span>
                <p class="etiqueta">Atletas Morosos</p>
                <p class="sub-info">
                    Más de 2 meses pendientes
                </p>
            </div>
        </div>
    </div>

    <!-- ===== GRÁFICOS ===== -->
    <div class="row g-3 mb-4">
        <!-- Gráfico: Atletas por categoría -->
        <div class="col-lg-6">
            <div class="grafico-container">
                <h6><i class="bi bi-bar-chart-fill"></i> Atletas por Categoría</h6>
                <canvas id="categoriasChart"></canvas>
                <div class="sub-info-grafico">
                    <i class="bi bi-people me-1"></i>
                    <?php echo $estadisticas['atletas']['total']; ?> atletas en <?php echo count($categorias); ?> categorías
                </div>
            </div>
        </div>
        
        <!-- Gráfico: Estado de Solvencia -->
        <div class="col-lg-6">
            <div class="grafico-container">
                <h6>
                    <i class="bi bi-pie-chart-fill"></i> 
                    Estado de Solvencia
                </h6>
                <canvas id="solvenciaChart"></canvas>
                <div class="sub-info-grafico">
                    <i class="bi bi-calendar-check me-1"></i>
                    <?php echo count($meses_activos); ?> meses procesados | 
                    <?php echo $estadisticas_solvencia['atletas_procesados']; ?> atletas
                </div>
            </div>
        </div>
    </div>

    <!-- ===== SECCIÓN: ATLETAS MOROSOS ===== -->
    <?php if(!empty($morosos)): ?>
    <h5 class="seccion-titulo">
        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
        Atletas con Mayor Mora
        <span class="badge-count">Más de 2 meses pendientes</span>
    </h5>
    <div class="table-responsive mb-4">
        <table class="table table-dashboard">
            <thead>
                <tr>
                    <th>Atleta</th>
                    <th>Categoría</th>
                    <th>Meses Pendientes</th>
                    
                </tr>
            </thead>
            <tbody>
                <?php foreach(array_slice($morosos, 0, 5) as $m): ?>
                    
                <tr>
                    <td>
                        <strong><?php echo $m['atleta']['atleta_primer_nombre'] . ' ' . $m['atleta']['atleta_primer_apellido']; ?></strong>
                    </td>
                    <td><?php echo $m['atleta']['atleta_categoria']; ?></td>
                    <td>
                        <span class="badge badge-pendiente"><?php echo $m['meses_pendientes']; ?> meses</span>
                    </td>
                    
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- ===== SECCIÓN: ASISTENCIA POR CATEGORÍA ===== -->
    <h5 class="seccion-titulo">
        <i class="bi bi-person-check-fill text-success"></i>
        Asistencia por Categoría
    </h5>
    <div class="row g-3 mb-4">
        <?php if(empty($asistencia_categoria)): ?>
        <div class="col-12">
            <div class="text-center text-white-50 py-3">
                <i class="bi bi-info-circle me-2"></i>
                No hay datos de asistencia disponibles
            </div>
        </div>
        <?php else: ?>
        <?php foreach($asistencia_categoria as $cat => $data): 
            $color = $data['porcentaje'] >= 75 ? '#198754' : ($data['porcentaje'] >= 50 ? '#ffc107' : '#dc3545');
        ?>
        <div class="col-md-4">
            <div class="stat-card" style="border-left: 3px solid <?php echo $color; ?>;">
                <h6 class="text-white"><?php echo $cat; ?></h6>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="valor" style="font-size:1.2rem;"><?php echo $data['porcentaje']; ?>%</span>
                        <p class="etiqueta" style="font-size:0.7rem;">
                            <?php echo $data['total_asistentes']; ?> asistentes / <?php echo $data['total_convocados']; ?> convocados
                        </p>
                        <p class="etiqueta" style="font-size:0.65rem;">
                            <?php echo $data['total_entrenamientos']; ?> entrenamientos
                        </p>
                    </div>
                    <div class="progress-ring">
                        <svg viewBox="0 0 36 36">
                            <circle cx="18" cy="18" r="16" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="3"/>
                            <circle cx="18" cy="18" r="16" fill="none" 
                                    stroke="<?php echo $color; ?>" 
                                    stroke-width="3"
                                    stroke-dasharray="<?php echo $data['porcentaje']; ?> 100"
                                    stroke-linecap="round"/>
                        </svg>
                        <span class="center-text"><?php echo $data['porcentaje']; ?>%</span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- ===== SECCIÓN: TOP ASISTENCIA ===== -->
    <h5 class="seccion-titulo">
        <i class="bi bi-trophy-fill text-warning"></i>
        Top 5 - Mejor Asistencia
    </h5>
    <div class="row g-3 mb-4">
        <?php if(empty($top_asistencia)): ?>
        <div class="col-12">
            <div class="text-center text-white-50 py-3">
                <i class="bi bi-info-circle me-2"></i>
                No hay datos de asistencia disponibles
            </div>
        </div>
        <?php else: ?>
        <?php foreach($top_asistencia as $index => $t): ?>
        <div class="col-md-2 col-6">
            <div class="stat-card text-center">
                <div style="font-size:1.5rem;margin-bottom:5px;">
                    <?php 
                    $medallas = ['🥇', '🥈', '🥉', '4️⃣', '5️⃣'];
                    echo $medallas[$index] ?? '🏅';
                    ?>
                </div>
                <h6 class="text-white" style="font-size:0.85rem;">
                    <?php echo $t['atleta']['atleta_primer_nombre'] . ' ' . $t['atleta']['atleta_primer_apellido']; ?>
                </h6>
                <span class="valor" style="font-size:1.2rem;color:#ffc107;"><?php echo $t['porcentaje']; ?>%</span>
                <p class="etiqueta" style="font-size:0.6rem;">
                    <?php echo $t['presentes']; ?>/<?php echo $t['total']; ?> asistencias
                </p>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- ===== SECCIÓN: RESUMEN DE ENTRENADORES ===== -->
    <h5 class="seccion-titulo">
        <i class="bi bi-person-badge text-info"></i>
        Resumen de Entrenadores
    </h5>
    <div class="table-responsive mb-4">
        <table class="table table-dashboard">
            <thead>
                <tr>
                    <th>Entrenador</th>
                    <th>Entrenamientos</th>
                    <th>Asistencia</th>
                    <th>Promedio</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($resumen_entrenadores)): ?>
                <tr>
                    <td colspan="4" class="text-center text-white-50">No hay entrenadores registrados</td>
                </tr>
                <?php else: ?>
                <?php foreach($resumen_entrenadores as $re): ?>
                <tr>
                    <td>
                        <strong><?php echo $re['usuario']['usuario_nombre'] . ' ' . $re['usuario']['usuario_apellido']; ?></strong>
                        <br>
                        <small class="text-black"><?php echo $re['usuario']['usuario_cedula']; ?></small>
                    </td>
                    <td><?php echo $re['total_entrenamientos']; ?></td>
                    <td>
                        <?php echo $re['total_asistentes']; ?>/<?php echo $re['total_convocados']; ?>
                    </td>
                    <td>
                        <span class="badge <?php echo $re['porcentaje_asistencia'] >= 75 ? 'bg-success' : ($re['porcentaje_asistencia'] >= 50 ? 'bg-warning text-dark' : 'bg-danger'); ?>">
                            <?php echo $re['porcentaje_asistencia']; ?>%
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== SECCIÓN: FRECUENCIA DE PRUEBAS ===== -->
    <h5 class="seccion-titulo">
        <i class="bi bi-clipboard-data text-primary"></i>
        Frecuencia de Pruebas por Categoría
        <span class="badge-count">Total: <?php echo $estadisticas['pruebas']['total']; ?> pruebas</span>
    </h5>
    <div class="table-responsive mb-4">
        <table class="table table-dashboard">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <?php 
                    $tipos_mostrar = array_keys($estadisticas['pruebas']['por_tipo']);
                    foreach($tipos_mostrar as $tipo): 
                        $nombre_tipo = $tipos_prueba[$tipo]['nombre'] ?? $tipo;
                    ?>
                    <th><?php echo $nombre_tipo; ?></th>
                    <?php endforeach; ?>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($frecuencia_pruebas)): ?>
                <tr>
                    <td colspan="<?php echo count($tipos_mostrar) + 2; ?>" class="text-center text-white-50">
                        No hay pruebas registradas
                    </td>
                </tr>
                <?php else: 
                    $categorias_pruebas = array_keys($frecuencia_pruebas);
                    foreach($categorias_pruebas as $cat):
                        $total_cat = array_sum($frecuencia_pruebas[$cat]);
                ?>
                <tr>
                    <td><strong><?php echo $cat; ?></strong></td>
                    <?php foreach($tipos_mostrar as $tipo): ?>
                    <td><?php echo $frecuencia_pruebas[$cat][$tipo] ?? 0; ?></td>
                    <?php endforeach; ?>
                    <td><strong><?php echo $total_cat; ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- ===== SECCIÓN: SOLVENCIA POR CATEGORÍA ===== -->
    <h5 class="seccion-titulo">
        <i class="bi bi-cash-stack text-success"></i>
        Solvencia por Categoría
    </h5>
    <div class="table-responsive mb-4">
        <table class="table table-dashboard">
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th><span class="badge badge-pagado">Pagado</span></th>
                    <th><span class="badge badge-pendiente">Pendiente</span></th>
                    <th><span class="badge badge-revision">Revisión</span></th>
                    <th><span class="badge bg-secondary">Por Vencer</span></th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($solvencia_categoria)): ?>
                <tr>
                    <td colspan="6" class="text-center text-white-50">No hay datos de solvencia</td>
                </tr>
                <?php else: 
                    foreach($solvencia_categoria as $cat => $data):
                        $total_cat = $data['Pagado'] + $data['Pendiente'] + $data['Revision'];
                ?>
                <tr>
                    <td><strong><?php echo $cat; ?></strong></td>
                    <td style="color: #125c39;"><?php echo $data['Pagado']; ?></td>
                    <td style="color: #dc3545;"><?php echo $data['Pendiente']; ?></td>
                    <td style="color: #ffc107;"><?php echo $data['Revision']; ?></td>
                    <td style="color: #32363a;"><?php echo $data['Por_Vencer']; ?></td>
                    <td><strong><?php echo $data['Total']; ?></strong></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6" class="text-50 small" style="background: rgba(255, 255, 255, 0); color: #ffffff;">
                        <i class="bi bi-info-circle me-1"></i>
                        * Por Vencer: meses futuros que aún no han llegado (hasta diciembre)
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

</div>


<!-- ===== SCRIPTS PARA GRÁFICOS ===== -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>

<script>
// ============================================
// REGISTRAR EL PLUGIN DE DATALABELS
// ============================================
Chart.register(ChartDataLabels);

document.addEventListener('DOMContentLoaded', function() {
    
    // ===== GRÁFICO: ATLETAS POR CATEGORÍA =====
    const ctxCategorias = document.getElementById('categoriasChart').getContext('2d');
    const categorias = <?php echo json_encode($categorias); ?>;
    const cantidades = <?php echo json_encode($cantidades); ?>;
    
    const colores = [
        'rgba(255, 193, 7, 0.7)',
        'rgba(13, 110, 253, 0.7)',
        'rgba(25, 135, 84, 0.7)',
        'rgba(220, 53, 69, 0.7)',
        'rgba(111, 66, 193, 0.7)',
        'rgba(253, 126, 20, 0.7)',
        'rgba(32, 201, 151, 0.7)',
        'rgba(13, 202, 240, 0.7)'
    ];
    
    const bordes = [
        '#ffc107', '#0d6efd', '#198754', '#dc3545', 
        '#6f42c1', '#fd7e14', '#20c997', '#0dcaf0'
    ];
    
    new Chart(ctxCategorias, {
        type: 'bar',
        data: {
            labels: categorias,
            datasets: [{
                label: 'Atletas',
                data: cantidades,
                backgroundColor: colores.slice(0, categorias.length),
                borderColor: bordes.slice(0, categorias.length),
                borderWidth: 2,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' atletas';
                        }
                    }
                },
                // ===== DATALABELS PARA GRÁFICO DE BARRAS =====
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    color: '#000000',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    offset: 2,
                    formatter: function(value) {
                        return value;
                    },
                    filter: function(value) {
                        return value > 0;
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: Math.max(...cantidades) + 1,
                    ticks: {
                        color: 'rgb(0, 0, 0)',
                        font: { size: 10 },
                        stepSize: 1
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.22)'
                    }
                },
                x: {
                    ticks: {
                        color: 'rgb(0, 0, 0)',
                        font: { size: 10 }
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
    
    // ===== GRÁFICO: SOLVENCIA =====
    const ctxSolvencia = document.getElementById('solvenciaChart').getContext('2d');
    
    new Chart(ctxSolvencia, {
        type: 'doughnut',
        data: {
            labels: ['Pagados', 'Pendientes', 'En Revisión'],
            datasets: [{
                data: [
                    <?php echo $estadisticas_solvencia['Pagado']; ?>,
                    <?php echo $estadisticas_solvencia['Pendiente']; ?>,
                    <?php echo $estadisticas_solvencia['Revision']; ?>
                ],
                backgroundColor: ['#198754', '#dc3545', '#ffc107'],
                borderWidth: 2,
                borderColor: '#0f0f1a'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: 'rgb(0, 0, 0)',
                        font: { size: 11 },
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + context.parsed + ' meses';
                        }
                    }
                },
                // ===== DATALABELS PARA GRÁFICO DE DONA =====
                datalabels: {
                    color: '#000000',
                    font: {
                        weight: 'bold',
                        size: 14
                    },
                    formatter: function(value, context) {
                        // Mostrar el valor y porcentaje
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const porcentaje = total > 0 ? Math.round((value / total) * 100) : 0;
                        return value + '\n' + porcentaje + '%';
                    },
                    // Mostrar solo si el valor es > 0
                    filter: function(value) {
                        return value > 0;
                    },
                    // Posicionar en el centro de cada segmento
                    anchor: 'center',
                    align: 'center',
                    offset: 0
                }
            },
            cutout: '65%'
        }
    });
});

function verAtleta(cedula) {
    alert('Ver detalle del atleta: ' + cedula);
    // window.location.href = '?seccion=atleta&cedula=' + cedula;
}
</script>
