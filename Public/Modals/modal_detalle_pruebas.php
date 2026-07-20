
<!-- ===== MODAL DE DETALLE ===== -->
<div class="modal fade" id="modalDetallePruebas" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                <h5 class="modal-title" id="modalDetalleTitulo">
                    <i class="bi bi-clipboard-data me-2" style="color: #ffc107;"></i>
                    <span id="modalTipoPrueba">Balón Aéreo</span>
                    <small class="text-white-50 ms-2" id="modalAtletaInfo">- Atleta: 28765432</small>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalDetalleBody">
                <!-- El contenido se carga con JavaScript -->
                <div class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="text-white-50 mt-2">Cargando datos...</p>
                </div>
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(255,255,255,0.06);">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-outline-light" onclick="imprimirTabla()">
                    <i class="bi bi-printer me-2"></i>Imprimir
                </button>
                <button type="button" class="btn btn-success" onclick="exportarExcel()">
                    <i class="bi bi-file-earmark-excel me-2"></i>Exportar Excel
                </button>
            </div>
        </div>
    </div>
</div>