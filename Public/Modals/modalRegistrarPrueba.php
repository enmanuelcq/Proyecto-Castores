<div class="modal fade" id="modalRegistrarPrueba" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle-fill me-2" style="color: #ffc107;"></i>
                    Registrar Nueva Prueba
                    <small class="text-white ms-2" id="registroAtletaInfo">- Atleta: 28765432</small>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formRegistrarPrueba" method="POST" action="./Controller/RegistrarPrueba_controller.php">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <input type="hidden" name="atleta_cedula" id="registroAtletaCedula" value="">
                    <input type="hidden" name="usuario_cedula" value="<?php echo $_SESSION['usuario']['usuario_cedula'] ?? ''; ?>">
                    
                    <!-- Selección del tipo de prueba -->
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-white">Tipo de Prueba</label>
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
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-white">Fecha</label>
                            <input type="date" class="form-control" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-white">Categoría</label>
                            <select class="form-select" name="categoria_id" required>
                                <option value="">Seleccionar...</option>
                                <option value="1">Sub-8</option>
                                <option value="2">Sub-10</option>
                                <option value="3">Sub-12</option>
                                <option value="4">Sub-14</option>
                                <option value="5">Sub-16</option>
                                <option value="6">Sub-18</option>
                            </select>
                        </div>
                    </div>

                    <hr class="border-secondary">

                    <!-- ===== CAMPOS BALÓN AÉREO ===== -->
                    <div id="camposBalonAereo" class="campos-prueba" style="display:none;">
                        <h6 class="text-primary"><i class="bi bi-balloon-heart me-2"></i>Balón Aéreo</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-white">Total Toques</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="total_toques" placeholder="0" min="0" max="999" 
                                       data-tipo="balon_aereo" data-campo="total_toques">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Toques Pecho</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="toques_pecho" placeholder="0" min="0" max="999"
                                       data-tipo="balon_aereo" data-campo="toques_pecho">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Toques Rodilla</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="toques_rodilla" placeholder="0" min="0" max="999"
                                       data-tipo="balon_aereo" data-campo="toques_rodilla">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Toques Pie</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="toques_pie" placeholder="0" min="0" max="999"
                                       data-tipo="balon_aereo" data-campo="toques_pie">
                            </div>
                        </div>
                    </div>

                    <!-- ===== CAMPOS CONDUCCIÓN ===== -->
                    <div id="camposConduccion" class="campos-prueba" style="display:none;">
                        <h6 class="text-success"><i class="bi bi-arrows-move me-2"></i>Conducción</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-white">Distancia (m)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="distancia_metros" placeholder="30.00" min="0" max="999.99"
                                       data-tipo="conduccion" data-campo="distancia_metros">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Distancia Obstáculos</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="distancia_obstaculos" placeholder="5.00" min="0" max="999.99"
                                       data-tipo="conduccion" data-campo="distancia_obstaculos">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-white">Tiempo Derecha (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="tiempo_derecha" placeholder="6.85" min="0" max="999.99"
                                       data-tipo="conduccion" data-campo="tiempo_derecha">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-white">Tiempo Izquierda (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="tiempo_izquierda" placeholder="7.12" min="0" max="999.99"
                                       data-tipo="conduccion" data-campo="tiempo_izquierda">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-white">Tiempo Ambas (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="tiempo_ambas_piernas" placeholder="6.45" min="0" max="999.99"
                                       data-tipo="conduccion" data-campo="tiempo_ambas_piernas">
                            </div>
                        </div>
                    </div>

                    <!-- ===== CAMPOS DEFINICIÓN ===== -->
                    <div id="camposDefinicion" class="campos-prueba" style="display:none;">
                        <h6 class="text-danger"><i class="bi bi-bullseye me-2"></i>Definición</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-white">Distancia (m)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="distancia" placeholder="16.00" min="0" max="999.99"
                                       data-tipo="definicion" data-campo="distancia">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Cantidad Tiros</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="cantidad_tiros" placeholder="20" min="0" max="999"
                                       data-tipo="definicion" data-campo="cantidad_tiros">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Goles</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="goles" placeholder="12" min="0" max="999"
                                       data-tipo="definicion" data-campo="goles">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Tiros al Empeine</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="empeine" placeholder="12" min="0" max="999"
                                       data-tipo="definicion" data-campo="empeine">
                            </div>
                        </div>
                    </div>

                    <!-- ===== CAMPOS FUERZA ===== -->
                    <div id="camposFuerza" class="campos-prueba" style="display:none;">
                        <h6 class="text-warning"><i class="bi bi-heart-pulse me-2"></i>Fuerza</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-white">Flexiones (1 min)</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="pruebaf_flexiones_1min" placeholder="35" min="0" max="999"
                                       data-tipo="fuerza" data-campo="pruebaf_flexiones_1min">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-white">Abdominales (1 min)</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="pruebaf_abdominales_1min" placeholder="42" min="0" max="999"
                                       data-tipo="fuerza" data-campo="pruebaf_abdominales_1min">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-white">Sentadillas (1 min)</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="pruebaf_sentadillas_1min" placeholder="38" min="0" max="999"
                                       data-tipo="fuerza" data-campo="pruebaf_sentadillas_1min">
                            </div>
                        </div>
                    </div>

                    <!-- ===== CAMPOS PASES ===== -->
                    <div id="camposPases" class="campos-prueba" style="display:none;">
                        <h6 class="text-info"><i class="bi bi-arrow-left-right me-2"></i>Pases</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-white">Total Pases</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="total_pases" placeholder="28" min="0" max="999"
                                       data-tipo="pases" data-campo="total_pases">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Pases con Derecha</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="derecha" placeholder="15" min="0" max="999"
                                       data-tipo="pases" data-campo="derecha">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Pases con Izquierda</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="izquierda" placeholder="8" min="0" max="999"
                                       data-tipo="pases" data-campo="izquierda">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">1ra Intención</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="primera_intencion" placeholder="5" min="0" max="999"
                                       data-tipo="pases" data-campo="primera_intencion">
                            </div>
                        </div>
                    </div>

                    <!-- ===== CAMPOS RECEPCIÓN ===== -->
                    <div id="camposRecepcion" class="campos-prueba" style="display:none;">
                        <h6 class="text-secondary"><i class="bi bi-hand-index me-2"></i>Recepción</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-white">Pases Recibidos</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="pases_recibidos" placeholder="15" min="0" max="999"
                                       data-tipo="recepcion" data-campo="pases_recibidos">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Controles Exitosos</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="controles_exitosos" placeholder="12" min="0" max="999"
                                       data-tipo="recepcion" data-campo="controles_exitosos">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Controles Fallidos</label>
                                <input type="number" class="form-control campo-filtrado" 
                                       name="controles_fallidos" placeholder="3" min="0" max="999"
                                       data-tipo="recepcion" data-campo="controles_fallidos">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Distancia (m)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="distancia_recepcion" placeholder="15.00" min="0" max="999.99"
                                       data-tipo="recepcion" data-campo="distancia_recepcion">
                            </div>
                        </div>
                    </div>

                    <!-- ===== CAMPOS RESISTENCIA ===== -->
                    <div id="camposResistencia" class="campos-prueba" style="display:none;">
                        <h6 class="text-purple"><i class="bi bi-clock-history me-2"></i>Resistencia</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-white">Tiempo Total (min)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="tiempo_total" placeholder="8.30" min="0" max="999.99"
                                       data-tipo="resistencia" data-campo="tiempo_total">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-white">Pausa (min)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="pausa" placeholder="2.00" min="0" max="999.99"
                                       data-tipo="resistencia" data-campo="pausa">
                            </div>
                        </div>
                    </div>

                    <!-- ===== CAMPOS SALTO ===== -->
                    <div id="camposSalto" class="campos-prueba" style="display:none;">
                        <h6 class="text-orange"><i class="bi bi-arrow-up me-2"></i>Salto</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-white">Altura Inicial (cm)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="pruebaS_alt_inicial" placeholder="210.00" min="0" max="999.99"
                                       data-tipo="salto" data-campo="pruebaS_alt_inicial">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-white">1er Salto (cm)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="pruebaS_1er_salto" placeholder="245.00" min="0" max="999.99"
                                       data-tipo="salto" data-campo="pruebaS_1er_salto">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-white">2do Salto (cm)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="pruebaS_2do_salto" placeholder="248.00" min="0" max="999.99"
                                       data-tipo="salto" data-campo="pruebaS_2do_salto">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-white">3er Salto (cm)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="pruebaS_3er_salto" placeholder="243.00" min="0" max="999.99"
                                       data-tipo="salto" data-campo="pruebaS_3er_salto">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-white">Promedio (cm)</label>
                                <input type="number" step="0.01" class="form-control" 
                                       name="pruebaS_Promedio" placeholder="245.33" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- ===== CAMPOS VELOCIDAD ===== -->
                    <div id="camposVelocidad" class="campos-prueba" style="display:none;">
                        <h6 class="text-cyan"><i class="bi bi-speedometer2 me-2"></i>Velocidad</h6>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label text-white">Distancia (m)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="pruebav_distancia_mtr" placeholder="30.00" min="0" max="999.99"
                                       data-tipo="velocidad" data-campo="pruebav_distancia_mtr">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-white">1er Intento (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="pruebav_1er_intento_seg" placeholder="4.35" min="0" max="999.99"
                                       data-tipo="velocidad" data-campo="pruebav_1er_intento_seg">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-white">2do Intento (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="pruebav_2do_intento_seg" placeholder="4.28" min="0" max="999.99"
                                       data-tipo="velocidad" data-campo="pruebav_2do_intento_seg">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label text-white">3er Intento (s)</label>
                                <input type="number" step="0.01" class="form-control campo-filtrado" 
                                       name="pruebav_3er_intento_seg" placeholder="4.32" min="0" max="999.99"
                                       data-tipo="velocidad" data-campo="pruebav_3er_intento_seg">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label text-white">Promedio (s)</label>
                                <input type="number" step="0.01" class="form-control" 
                                       name="pruebav_Promedio" placeholder="4.32" readonly>
                            </div>
                        </div>
                    </div>

                    <hr class="border-secondary mt-4">

                    <!-- Botones -->
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-2"></i>Cancelar
                        </button>
                        <button type="reset" class="btn btn-outline-secondary" onclick="limpiarFormulario()">
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