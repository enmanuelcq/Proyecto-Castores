    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // JS dinámico para cargar datos en el Modal Único de Noticias [cite: 8]
        function abrirModalNoticia(titulo, fecha, contenido) {
            document.getElementById('modalTitulo').innerText = titulo;
            document.getElementById('modalFecha').innerText = fecha;
            document.getElementById('modalCuerpo').innerText = contenido;
            
            var myModal = new bootstrap.Modal(document.getElementById('noticiaModal'));
            myModal.show();
        }
    </script>

    



