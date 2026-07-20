<?php
require_once './Model/Noticias.php';
require_once './Model/Participaciones_partidos.php';
require_once './Model/Pruebas.php';
require_once './Model/Atletas.php';
include './Controller/DashboardRendimientoController.php';

    // Obtener la cédula del atleta desde la URL
$cedula_atleta = isset($_GET['Atleta']) ? $_GET['Atleta'] : '';

// Si no hay cédula, mostrar mensaje
if(empty($cedula_atleta)) {
    echo '<div class="alert alert-warning">Selecciona un atleta para ver su dashboard</div>';
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

?>


<div class="container contenido">
    
    <!-- ===== PERFIL ===== -->
    <div class="perfil-atleta">
        <div class="perfil-avatar">
            <?php 
            echo strtoupper(substr($atleta['atleta_primer_nombre'] ?? 'A', 0, 1) . substr($atleta['atleta_primer_apellido'] ?? 'A', 0, 1));
            ?>
        </div>
        <div class="perfil-info">
            <h3><?php echo $atleta['atleta_primer_nombre'] . ' ' . $atleta['atleta_primer_apellido']; ?></h3>
            <div class="detalles">
                <span><i class="bi bi-tag"></i> <?php echo $atleta['atleta_categoria'] ?? 'Sin categoría'; ?></span>
                <span><i class="bi bi-person-badge"></i> <?php echo $atleta['atleta_cedula']; ?></span>
                <span><i class="bi bi-calendar3"></i> <?php echo $atleta['atleta_fecha_nacimiento'] ?? 'N/A'; ?></span>
                <span><i class="bi bi-trophy"></i> <?php echo $total_partidos; ?> partidos</span>
                <span><i class="bi bi-clipboard-data"></i> <?php echo $total_pruebas; ?> pruebas</span>
            </div>
        </div>
        <div class="perfil-acciones">
            <a href="index.php?View=Pruebas&Atleta=<?php echo $cedula_atleta; ?>" class="btn btn-outline-light btn-sm">
                <i class="bi bi-clipboard2-check me-1"></i>Pruebas
            </a>
            <a href="index.php?View=Partidos&Atleta=<?php echo $cedula_atleta; ?>" class="btn btn-outline-light btn-sm">
                <i class="bi bi-trophy me-1"></i>Partidos
            </a>
        </div>
    </div>
    
    <!-- ===== ESTADÍSTICAS GENERALES ===== -->
    <div class="row g-3 mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(13,110,253,0.12); color: #0d6efd;">
                    <i class="bi bi-trophy"></i>
                </div>
                <span class="valor"><?php echo $total_partidos; ?></span>
                <p class="etiqueta">Partidos Jugados</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(25,135,84,0.12); color: #198754;">
                    <i class="bi bi-emoji-sunglasses"></i>
                </div>
                <span class="valor"><?php echo $total_goles; ?></span>
                <p class="etiqueta">Goles Marcados</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(13,202,240,0.12); color: #0dcaf0;">
                    <i class="bi bi-people"></i>
                </div>
                <span class="valor"><?php echo $total_asistencias; ?></span>
                <p class="etiqueta">Asistencias</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="stat-card">
                <div class="icono" style="background: rgba(255,193,7,0.12); color: #ffc107;">
                    <i class="bi bi-clipboard-data"></i>
                </div>
                <span class="valor"><?php echo $total_pruebas; ?></span>
                <p class="etiqueta">Pruebas Realizadas</p>
            </div>
        </div>
    </div>
    
    <!-- ===== GRÁFICOS INDEPENDIENTES ===== -->
    <div class="row g-3 mb-4">
        <?php foreach($datos_graficos as $indice => $dato): ?>
        <div class="col-lg-4 col-md-6">
            <div class="grafico-container">
                <h6>
                    <i class="bi bi-bar-chart-fill" style="color: <?php echo $dato['color']; ?>;"></i>
                    <?php echo $dato['label']; ?>
                    <span class="text-white" style="font-size:0.65rem;font-weight:400;display:block;">
                        vs Promedio <?php echo $categoria_atleta; ?> (<?php echo $dato['unidad']; ?>)
                    </span>
                </h6>
                <canvas id="graficoIndependiente_<?php echo $indice; ?>"></canvas>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    
    <!-- ===== GRÁFICO DE EVOLUCIÓN DE TODAS LAS PRUEBAS ===== -->
    <?php if(!empty($evolucion_todos_datos) && !empty($evolucion_labels)): ?>
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="grafico-container">
                <h6>
                    <i class="bi bi-graph-up-arrow"></i> 
                    Evolución de todas las pruebas
                    <span class="text-white-50" style="font-size:0.75rem;font-weight:400;">(Línea de tiempo)</span>
                </h6>
                <canvas id="evolucionTodosChart"></canvas>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- ===== PRUEBAS RECIENTES CON COMPARATIVO ===== -->
    <h5 class="seccion-titulo">
        <i class="bi bi-clipboard2-check"></i>
        Últimos Resultados en Pruebas
        <span class="text-white-50" style="font-size:0.75rem;font-weight:400;margin-left:10px;">
            (vs promedio de <?php echo $categoria_atleta; ?>)
        </span>
    </h5>
    
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <?php 
            $contador = 0;
            foreach($tipos_prueba as $clave => $tipo):
                $pruebas_tipo = isset($pruebas_atleta[$clave]) ? $pruebas_atleta[$clave] : [];
                if(empty($pruebas_tipo) || $contador >= 3) continue;
                
                usort($pruebas_tipo, function($a, $b) {
                    $fecha_a = obtenerFechaPrueba($a);
                    $fecha_b = obtenerFechaPrueba($b);
                    return strtotime($fecha_b) - strtotime($fecha_a);
                });
                $ultima = $pruebas_tipo[0];
                $valor_atleta = obtenerValorPrincipal($ultima, $clave);
                $promedio = $promedios_categoria[$clave] ?? 0;
                
                $comparacion = '';
                $clase_comparacion = '';
                if($valor_atleta > 0 && $promedio > 0) {
                    $diferencia = $valor_atleta - $promedio;
                    $porcentaje = round(($diferencia / $promedio) * 100, 1);
                    if($porcentaje > 5) {
                        $comparacion = '▲ ' . $porcentaje . '% superior';
                        $clase_comparacion = 'superior';
                    } elseif($porcentaje < -5) {
                        $comparacion = '▼ ' . abs($porcentaje) . '% inferior';
                        $clase_comparacion = 'inferior';
                    } else {
                        $comparacion = '≈ En promedio';
                        $clase_comparacion = 'igual';
                    }
                }
                
                $contador++;
                $icono = $tipos_prueba[$clave]['icono'] ?? 'bi-clipboard';
                $color = obtenerColorTipo($clave);
                $fecha = obtenerFechaPrueba($ultima);
            ?>
            <div class="prueba-mini-card">
                <div class="info">
                    <i class="<?php echo $icono; ?>" style="color: <?php echo $color; ?>;"></i>
                    <span class="nombre"><?php echo $tipo['nombre']; ?></span>
                    <span class="fecha"><?php echo $fecha ? formatearFecha($fecha) : 'N/A'; ?></span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="valor"><?php echo $valor_atleta > 0 ? $valor_atleta : '—'; ?></span>
                    <?php if($comparacion): ?>
                    <span class="comparativo <?php echo $clase_comparacion; ?>"><?php echo $comparacion; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- ===== PARTIDOS RECIENTES ===== -->
        <div class="col-md-6">
            <?php 
            $contador = 0;
            foreach($partidos_jugados as $pj):
                if($contador >= 3) break;
                $partido = $pj['partido'];
                $participacion = $pj['participacion'];
                $contador++;
            ?>
            <div class="prueba-mini-card">
                <div class="info">
                    <i class="bi bi-trophy" style="color: #ffc107;"></i>
                    <span class="nombre">vs <?php echo $partido['rival']; ?></span>
                    <span class="fecha"><?php echo formatearFecha($partido['fecha']); ?></span>
                    <span class="badge-mini <?php echo strtolower($partido['status']); ?>">
                        <?php echo $partido['status']; ?>
                    </span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <span class="valor" style="font-size:0.9rem;">
                        ⚽ <?php echo $participacion['goles']; ?>
                    </span>
                    <span style="color:rgba(255,255,255,0.3);font-size:0.8rem;">
                        <?php echo $participacion['minutos_jugados']; ?>'
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
            <?php if($contador == 0): ?>
            <div class="text-center text-white-50 py-3">
                <i class="bi bi-trophy me-2"></i>
                No hay partidos registrados
            </div>
            <?php endif; ?>
        </div>
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ============================================
// REGISTRAR EL PLUGIN DE DATALABELS
// ============================================
Chart.register(ChartDataLabels);

// ============================================
// GRÁFICOS INDEPENDIENTES POR TIPO DE PRUEBA
// ============================================
<?php foreach($datos_graficos as $indice => $dato): 
    $maximo = max($dato['atleta'], $dato['promedio']) * 1.3;
    if($maximo == 0) $maximo = 10;
?>
const ctx_<?php echo $indice; ?> = document.getElementById('graficoIndependiente_<?php echo $indice; ?>').getContext('2d');

new Chart(ctx_<?php echo $indice; ?>, {
    type: 'bar',
    data: {
        labels: ['Tú', 'Promedio'],
        datasets: [{
            label: '<?php echo $dato['label']; ?>',
            data: [<?php echo $dato['atleta']; ?>, <?php echo $dato['promedio']; ?>],
            backgroundColor: [
                '<?php echo $dato['color']; ?>',
                '<?php echo $dato['color']; ?>80'
            ],
            borderColor: [
                '<?php echo $dato['color']; ?>',
                '<?php echo $dato['color']; ?>'
            ],
            borderWidth: 2,
            borderRadius: 6,
            barPercentage: 0.6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + ' <?php echo $dato['unidad']; ?>';
                    }
                }
            },
            // ===== PLUGIN DE ETIQUETAS DE DATOS =====
            datalabels: {
                anchor: 'start',
                align: 'end',
                color: '#000000',
                font: {
                    weight: 'bold',
                    size: 11
                },
                
                formatter: function(value) {
                    return value + ' <?php echo $dato['unidad']; ?>';
                },
                // Para que solo muestre valores > 0
                filter: function(value) {
                    return value > 0;
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                max: <?php echo ceil($maximo); ?>,
                ticks: {
                    color: 'rgb(0, 0, 0)',
                    font: { size: 10 },
                    callback: function(value) {
                        return value + ' <?php echo $dato['unidad']; ?>';
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.25)'
                }
            },
            x: {
                ticks: {
                    color: 'rgb(0, 0, 0)',
                    font: { size: 11 }
                },
                grid: {
                    display: false
                }
            }
        }
    },
    // ===== PLUGINS =====
    plugins: [ChartDataLabels]
});
<?php endforeach; ?>

// ============================================
// GRÁFICO DE EVOLUCIÓN DE TODAS LAS PRUEBAS
// ============================================
<?php if(!empty($evolucion_todos_datos) && !empty($evolucion_labels)): ?>
const ctxEvolucionTodos = document.getElementById('evolucionTodosChart').getContext('2d');

const datasetsEvolucion = [
    <?php foreach($evolucion_todos_datos as $dato): ?>
    {
        label: '<?php echo $dato['label']; ?>',
        data: <?php echo json_encode($dato['valores']); ?>,
        borderColor: '<?php echo $dato['color']; ?>',
        backgroundColor: '<?php echo $dato['color']; ?>20',
        fill: false,
        tension: 0.3,
        pointBackgroundColor: '<?php echo $dato['color']; ?>',
        pointBorderColor: '#000000',
        pointBorderWidth: 1,
        pointRadius: 4,
        spanGaps: true
    },
    <?php endforeach; ?>
];

new Chart(ctxEvolucionTodos, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($evolucion_labels); ?>,
        datasets: datasetsEvolucion
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                labels: {
                    color: 'rgb(0, 0, 0)',
                    font: { size: 10 },
                    boxWidth: 12,
                    padding: 10
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': ' + context.parsed.y;
                    }
                }
            },
            // ===== PLUGIN DE ETIQUETAS DE DATOS PARA EVOLUCIÓN =====
            datalabels: {
                anchor: 'end',
                align: 'top',
                color: 'rgba(0, 0, 0, 0)',
                font: {
                    weight: 'bold',
                    size: 9
                },
                formatter: function(value) {
                    return value;
                },
                // Mostrar solo para algunos puntos (cada 2 para no saturar)
                filter: function(value, context) {
                    // Mostrar solo valores > 0 y en posiciones pares
                    return value > 0 && context.dataIndex % 2 === 0;
                },
                // Offset para separar de los puntos
                offset: 4
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: 'rgb(0, 0, 0)',
                    font: { size: 10 }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.23)'
                }
            },
            x: {
                ticks: {
                    color: 'rgb(0, 0, 0)',
                    font: { size: 10 }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.21)'
                }
            }
        }
    },
    plugins: [ChartDataLabels]
});
<?php endif; ?>
</script>