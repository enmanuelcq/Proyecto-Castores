<?php
#Verificar Datos#
function verificar_datos($filtro, $cadena){
    // Escapar caracteres especiales en el filtro
    $filtro_escapado = preg_quote($filtro, '/');
    if(preg_match('/^' . $filtro_escapado . '$/', $cadena)){
        return false;
    } else {
        return true;
    }
}

#Verificar fechas
function verificar_fecha($filtro, $fecha) {
    // Validar parámetros
    if (empty($filtro) || !is_string($filtro) || empty($fecha)) {
        return false;
    }
    $filtro_escapado = preg_quote($filtro, '/');
    // 1. Primero verificar el formato con regex
    if (preg_match('/^' . $filtro_escapado . '$/', $fecha)) {
        // 2. Luego validar si es una fecha REAL
        return validar_fecha_real($fecha, $filtro_escapado);
    }
    
    return false;
}

function validar_fecha_real($fecha, $formato = null) {
    // Intentar crear objeto DateTime
    try {
        if ($formato) {
            $dateTime = DateTime::createFromFormat($formato, $fecha);
        } else {
            $dateTime = new DateTime($fecha);
        }
        
        if (!$dateTime) {
            return false;
        }
        
        // Verificar que la fecha parsed sea igual a la original
        $fecha_formateada = $dateTime->format($formato ?: 'd-m-Y');
        
        return $fecha_formateada === $fecha;
        
    } catch (Exception $e) {
        return false;
    }
}

    function calcularEdad($fecha_nac) {
        $hoy = new DateTime();
        $fn = new DateTime($fecha_nac);
        return $hoy->diff($fn)->y;
    }

    function generarIdAtleta() {
        return 'atl_' . uniqid();
    }

    /**
     * Convierte un número de mes (1-12) a nombre en español
     * 
     * @param int $mes
     * @return string
     */
    function nombreMes($mes) {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 
            4 => 'Abril', 5 => 'Mayo', 6 => 'Junio',
            7 => 'Julio', 8 => 'Agosto', 9 => 'Septiembre',
            10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        return $meses[(int)$mes] ?? '';
    }

    function formatearMonto($monto) {
        return '$ ' . number_format((float)$monto, 2, ',', '.');
    }
#Limpiar Cadenas de Texto#

    function limpiar_cadena($cadena){
        $cadena = trim($cadena); // elimina espacios en blanco
        $cadena = stripslashes($cadena); // elimina las barras en un string
        #Eliminar inyección SQL
        $cadena = str_ireplace("<script>","",$cadena);
        $cadena = str_ireplace("</script>","",$cadena);
        $cadena = str_ireplace("<script src","",$cadena);
        $cadena = str_ireplace("<script type =","",$cadena);
        $cadena = str_ireplace("SELECT * FROM","",$cadena);
        $cadena = str_ireplace("DELETE FROM","",$cadena);
        $cadena = str_ireplace("INSERT INTO","",$cadena);
        $cadena = str_ireplace("DROP TABLE","",$cadena);
        $cadena = str_ireplace("DROP DATABASE","",$cadena);
        $cadena = str_ireplace("TRUNCATE TABLE","",$cadena);
        $cadena = str_ireplace("SHOW TABLES","",$cadena);
        $cadena = str_ireplace("SHOW DATABASES","",$cadena);
        $cadena = str_ireplace("<?php","",$cadena);
        $cadena = str_ireplace("?>","",$cadena);
        $cadena = str_ireplace("<?PHP","",$cadena);
        $cadena = str_ireplace("--","",$cadena);
        $cadena = str_ireplace("<","",$cadena);
        $cadena = str_ireplace("[","",$cadena);
        $cadena = str_ireplace("]","",$cadena);
        $cadena = str_ireplace("<?PHP","",$cadena);
        $cadena = str_ireplace("==","",$cadena);
        $cadena = str_ireplace(";","",$cadena);
        $cadena = str_ireplace("::","",$cadena);
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        return $cadena;
    }

#Función para Paginador de tablas

    function paginador_tablas($pagina, $n_paginas, $url, $botones){
        $tabla =' <nav class = "pagination is- center is- rounded" 
        role = "navigation" aria-label = *pagination">';

        #Desde Primera página
        if ($pagina <=1 ){
            #Desabilitar el botón de anterior del paginador en 1ra página
            $tabla.='
            <a class = "pagination-previous is - disabled" 
            disabled> Anterior </a>
            <ul class = "pagination-list">

            ';
        } else{
            #Habilita el botón de anterior del paginador
            $tabla.='
            <a class = "pagination-previus" href = "'.$url.($pagina-1).'"> Anterior </a>
            <ul class = "pagination-list">
                <li><a class = "pagination-link" href = "'.$url.'1">1</a></li>
                <li><span class = "pagination-ellipsis">&hellip;</span></li>
            ';
        }

        #controlar cantidad de botones
        $ci = 0;
        for ($i =$pagina; $i <= $n_paginas; $i++ ){
            if($ci >= $botones){
                break;
            }
            if($pagina == $i){
                $tabla.= '<li><a class = "pagination-link is- current" 
                href = "'.$url.$i.'" </a>'.$i.'</li>';
            } else {
                $tabla.= '<li><a class = "pagination-link" 
                href = "'.$url.$i.'" </a>'.$i.'</li>';
            }
            $ci++;
        }

        #Desde última pagina
        if ($pagina == $n_paginas){
            #Desabilitar el botón de Siguiente del paginador en última página
            $tabla.='
            </ul>
            <a class = "pagination-next is- disabled" disabled> Siguiente </a>
            ';
        } else{
            #Habilita el botón de anterior del paginador
            $tabla.='
                <li><span class = "pagination-ellipsis">&hellip;</span></li>
                <li><a class = "pagination-link" href = "'.$url.$n_paginas.'">'.$n_paginas.'</a></li>
            </ul>
            <a class = "pagination-next" href = "'.$url.($pagina+1).'"> Siguiente </a>
            ';
        }
        $tabla.='</nav>';
        return $tabla;
    }