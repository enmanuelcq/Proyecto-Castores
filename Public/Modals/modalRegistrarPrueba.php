<div class="modal fade" id="modalRegistrarPrueba" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle-fill me-2" style="color: #ffc107;"></i>
                    Registrar Nueva Prueba
                    <small class="text-white ms-2" id="registroAtletaInfo">- Atleta</small>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistrarPrueba" method="POST" action="./Controller/RegistrarPrueba_controller.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="atleta_id" id="registroAtletaId" value="">
                    <input type="hidden" name="categoria_id_hidden" id="registroCategoriaId" value="">
                    <input type="hidden" name="usuario_cedula" value="<?php echo $_SESSION['usuario']['usuario_cedula'] ?? ''; ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de Prueba</label>
                            <select class="form-select" name="tipo_prueba" id="tipoPruebaSelect" required>
                                <option value="">Seleccionar...</option>
                                <option value="balon_aereo">Balón Aéreo</option>
                                <option value="conduccion">Conducción</option>
                                <option value="definicion">Definición</option>
                                <option value="fuerza">Fuerza</option>
                                <option value="pases">Pases</option>
                                <option value="recepcion">Recepción</option>
                                <option value="resistencia">Resistencia</option>
                                <option value="salto">Salto</option>
                                <option value="velocidad">Velocidad</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Fecha</label>
                            <input type="date" class="form-control" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                    </div>

                    <hr class="border-secondary">

                    <!-- ===== BALÓN AÉREO ===== -->
                    <div id="camposBalonAereo" class="campos-prueba" style="display:none;">
                        <h6 class="text-primary"><i class="bi bi-balloon-heart me-2"></i>Balón Aéreo</h6>
                        <small class="text-muted d-block mb-3">El total de toques es el límite. Los toques por zona (pecho, rodilla, pie) no pueden superar el total.</small>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Total Toques</label>
                                <input type="number" class="form-control campo-filtrado" id="ba_total"
                                       name="total_toques" placeholder="Ej: 45" min="0" max="100"
                                       data-limite="ba">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Toques Pecho</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="ba_total"
                                       name="toques_pecho" placeholder="No supere el total" min="0" max="100">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Toques Rodilla</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="ba_total"
                                       name="toques_rodilla" placeholder="No supere el total" min="0" max="100">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Toques Pie</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="ba_total"
                                       name="toques_pie" placeholder="No supere el total" min="0" max="100">
                            </div>
                        </div>
                    </div>

                    <!-- ===== CONDUCCIÓN ===== -->
                    <div id="camposConduccion" class="campos-prueba" style="display:none;">
                        <h6 class="text-success"><i class="bi bi-arrows-move me-2"></i>Conducción</h6>
                        <small class="text-muted d-block mb-3">La distancia entre obstáculos no puede superar la distancia total de la prueba.</small>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Distancia Total (m)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" id="cond_distancia"
                                       name="cond_distancia_metros" placeholder="Ej: 30" min="0" max="100"
                                       data-limite="cond">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Dist. Obstáculos (m)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado campo-limite-dist" data-ref="cond_distancia"
                                       name="cond_distancia_obstaculos" placeholder="Máx: distancia total" min="0" max="100">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Tiempo Derecha (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="cond_tiempo_derecha" placeholder="Ej: 6.85" min="0" max="30">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Tiempo Izquierda (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="cond_tiempo_izquierda" placeholder="Ej: 7.12" min="0" max="30">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Tiempo Ambas (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="cond_tiempo_ambas_piernas" placeholder="Ej: 6.45" min="0" max="30">
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Tipo de Ejercicio</label>
                                <input type="text" class="form-control" 
                                       name="cond_descripcion" placeholder="Ej: Zigzag, Conducción en lateral, Slalon, Conducción libre" maxlength="255">
                            </div>
                        </div>
                    </div>

                    <!-- ===== DEFINICIÓN ===== -->
                    <div id="camposDefinicion" class="campos-prueba" style="display:none;">
                        <h6 class="text-danger"><i class="bi bi-bullseye me-2"></i>Definición</h6>
                        <small class="text-muted d-block mb-3">La cantidad de tiros es el límite por zona. Cada zona no puede registrarse más tiros que los establecidos.</small>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Distancia (m)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="def_distancia_metros" placeholder="Ej: 16" min="0" max="50">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Tiros por Zona</label>
                                <input type="number" class="form-control campo-filtrado" id="def_tiros"
                                       name="cantidad_tiros" placeholder="Ej: 5" min="0" max="30"
                                       data-limite="def">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Empeine</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="def_tiros"
                                       name="empeine" placeholder="Máx: tiros por zona" min="0" max="30">
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Centro Bajo</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="def_tiros"
                                       name="centro_bajo" placeholder="Máx: tiros por zona" min="0" max="30">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Centro Alto</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="def_tiros"
                                       name="centro_alto" placeholder="Máx: tiros por zona" min="0" max="30">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Derecha Bajo</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="def_tiros"
                                       name="derecha_bajo" placeholder="Máx: tiros por zona" min="0" max="30">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Derecha Alto</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="def_tiros"
                                       name="derecha_alto" placeholder="Máx: tiros por zona" min="0" max="30">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Izquierda Bajo</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="def_tiros"
                                       name="izquierda_bajo" placeholder="Máx: tiros por zona" min="0" max="30">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Izquierda Alto</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="def_tiros"
                                       name="izquierda_alto" placeholder="Máx: tiros por zona" min="0" max="30">
                            </div>
                        </div>
                    </div>

                    <!-- ===== FUERZA ===== -->
                    <div id="camposFuerza" class="campos-prueba" style="display:none;">
                        <h6 class="text-warning"><i class="bi bi-heart-pulse me-2"></i>Fuerza</h6>
                        <small class="text-muted d-block mb-3">Registro de repeticiones máximas en 1 minuto por ejercicio.</small>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Flexiones (1 min)</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="pruebaf_flexiones_1min" placeholder="Ej: 35" min="0" max="80">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Abdominales (1 min)</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="pruebaf_abdominales_1min" placeholder="Ej: 42" min="0" max="80">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Sentadillas (1 min)</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="pruebaf_sentadillas_1min" placeholder="Ej: 38" min="0" max="80">
                            </div>
                        </div>
                    </div>

                    <!-- ===== PASES ===== -->
                    <div id="camposPases" class="campos-prueba" style="display:none;">
                        <h6 class="text-info"><i class="bi bi-arrow-left-right me-2"></i>Pases</h6>
                        <small class="text-muted d-block mb-3">El total de pases es el límite. Los pases por tipo no pueden superar el total.</small>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Total Pases</label>
                                <input type="number" class="form-control campo-filtrado" id="pas_total"
                                       name="total_pases" placeholder="Ej: 30" min="0" max="60"
                                       data-limite="pas">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Derecha</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="pas_total"
                                       name="pas_derecha" placeholder="No supere el total" min="0" max="60">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Izquierda</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="pas_total"
                                       name="pas_izquierda" placeholder="No supere el total" min="0" max="60">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">1ra Intención</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="pas_total"
                                       name="pas_primera_intencion" placeholder="No supere el total" min="0" max="60">
                            </div>
                        </div>
                    </div>

                    <!-- ===== RECEPCIÓN ===== -->
                    <div id="camposRecepcion" class="campos-prueba" style="display:none;">
                        <h6 class="text-secondary"><i class="bi bi-hand-index me-2"></i>Recepción</h6>
                        <small class="text-muted d-block mb-3">El total de pases es el límite. Los controles por tipo no pueden superar el total.</small>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Cantidad Pases</label>
                                <input type="number" class="form-control campo-filtrado" id="rec_pases"
                                       name="cantidad_pases" placeholder="Ej: 20" min="0" max="60"
                                       data-limite="rec">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Distancia (m)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="rec_distancia_metros" placeholder="Ej: 15" min="0" max="50">
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Derecha</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="rec_pases"
                                       name="rec_derecha" placeholder="Máx: pases" min="0" max="60">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Izquierda</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="rec_pases"
                                       name="rec_izquierda" placeholder="Máx: pases" min="0" max="60">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label fw-semibold">Der. Orientado</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="rec_pases"
                                       name="rec_derecha_orientado" placeholder="Máx: pases" min="0" max="60">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Izq. Orientado</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="rec_pases"
                                       name="rec_izquierda_orientado" placeholder="Máx: pases" min="0" max="60">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Orientado con Giro</label>
                                <input type="number" class="form-control campo-filtrado campo-limite" data-ref="rec_pases"
                                       name="rec_orientado_giro" placeholder="Máx: pases" min="0" max="60">
                            </div>
                        </div>
                    </div>

                    <!-- ===== RESISTENCIA ===== -->
                    <div id="camposResistencia" class="campos-prueba" style="display:none;">
                        <h6 class="text-purple"><i class="bi bi-clock-history me-2"></i>Resistencia</h6>
                        <small class="text-muted d-block mb-3">Tiempo continuo en trote. La pausa indica el minuto en que el atleta disminuyó el ritmo.</small>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tiempo Total (min)</label>
                                <input type="number" step="0.1" class="form-control campo-filtrado" id="res_tiempo"
                                       name="tiempo_total" placeholder="Ej: 12.5 min" min="0" max="30"
                                       data-limite="res">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Minuto de Pausa</label>
                                <input type="number" step="0.1" class="form-control campo-filtrado campo-limite-res" data-ref="res_tiempo"
                                       name="pausa" placeholder="Minuto de pausa (máx: tiempo total)" min="0" max="30">
                            </div>
                        </div>
                    </div>

                    <!-- ===== SALTO ===== -->
                    <div id="camposSalto" class="campos-prueba" style="display:none;">
                        <h6 class="text-orange"><i class="bi bi-arrow-up me-2"></i>Salto</h6>
                        <small class="text-muted d-block mb-3">Ingrese la altura con brazo extendido y las alturas crudas de cada salto. El sistema calculará la diferencia automáticamente.</small>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Altura Inicial (cm)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" id="salto_inicial"
                                       name="pruebaS_alt_inicial" placeholder="Altura brazo extendido, Ej: 170" min="100" max="250">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">1er Salto Crudo (cm)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" id="salto_1er_raw"
                                       name="salto_1er_raw" placeholder="Altura en pared, Ej: 188" min="100" max="350">
                                <input type="hidden" name="pruebaS_1er_salto" id="salto_1er_calc" value="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">2do Salto Crudo (cm)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" id="salto_2do_raw"
                                       name="salto_2do_raw" placeholder="Altura en pared, Ej: 192" min="100" max="350">
                                <input type="hidden" name="pruebaS_2do_salto" id="salto_2do_calc" value="">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">3er Salto Crudo (cm)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" id="salto_3er_raw"
                                       name="salto_3er_raw" placeholder="Altura en pared, Ej: 185" min="100" max="350">
                                <input type="hidden" name="pruebaS_3er_salto" id="salto_3er_calc" value="">
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Promedio (cm)</label>
                                <input type="text" class="form-control" id="salto_promedio" readonly
                                       placeholder="Se calcula automáticamente" style="background:#e9ecef;">
                            </div>
                        </div>
                    </div>

                    <!-- ===== VELOCIDAD ===== -->
                    <div id="camposVelocidad" class="campos-prueba" style="display:none;">
                        <h6 class="text-cyan"><i class="bi bi-speedometer2 me-2"></i>Velocidad</h6>
                        <small class="text-muted d-block mb-3">Distancia de la carrera y tiempo de cada intento. El promedio se calcula automáticamente.</small>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Distancia (m)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="pruebav_distancia_mtr" placeholder="Ej: 30m" min="0" max="100">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">1er Intento (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" id="vel_intento1"
                                       name="pruebav_1er_intento_seg" placeholder="Ej: 4.35" min="0" max="30">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">2do Intento (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" id="vel_intento2"
                                       name="pruebav_2do_intento_seg" placeholder="Ej: 4.28" min="0" max="30">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">3er Intento (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" id="vel_intento3"
                                       name="pruebav_3er_intento_seg" placeholder="Ej: 4.32" min="0" max="30">
                            </div>
                        </div>
                        <div class="row g-3 mt-1">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Promedio (s)</label>
                                <input type="text" class="form-control" id="vel_promedio" readonly
                                       placeholder="Se calcula automáticamente" style="background:#e9ecef;">
                            </div>
                        </div>
                    </div>

                    <hr class="border-secondary mt-4">

                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="limpiarFormulario()">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Limpiar
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-2"></i>Registrar Prueba
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="pruebasToastContainer" class="pruebas-toast-container">
    <div class="pruebas-toast" id="pruebasToast">
        <i class="bi pruebas-toast-icon" id="pruebasToastIcon"></i>
        <span class="pruebas-toast-msg" id="pruebasToastMsg"></span>
    </div>
</div>
