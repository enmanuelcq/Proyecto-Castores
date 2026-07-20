//Selecciona todos los elementos dentro de esta clase
const formularios_ajax = document.querySelectorAll(".FormularioAjax1");

function enviar_formulario_ajax(e) {
    e.preventDefault(); // previene el envío tradicional

    let enviar = confirm("¿Quieres enviar el formulario?"); // Confirmación

    if (enviar) {
        let data = new FormData(e.target);
        let method = e.target.getAttribute("method");
        let action = e.target.getAttribute("action");

        let config = {
            method: method,
            cache: 'no-cache',
            body: data // datos del formulario
        };

        fetch(action, config)
            .then(respuesta => respuesta.text())
            .then(respuesta => {
                let contenedor = e.target.parentElement.querySelector(".form-rest1");
                if (contenedor) contenedor.innerHTML = respuesta;
            })
            .catch(error => {
                let contenedor = e.target.parentElement.querySelector(".form-rest1");
                if (contenedor) contenedor.innerHTML = `
                    <div class="notification is-danger is-light">
                        <strong>Error de conexión</strong><br>
                        No se pudo conectar con el servidor.
                    </div>
                `;
            });
    }
}

formularios_ajax.forEach(formulario => {
    formulario.addEventListener("submit", enviar_formulario_ajax);
});