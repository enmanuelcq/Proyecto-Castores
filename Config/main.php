<?php
#Verificar Datos#
function verificar_datos($filtro, $cadena){
    if(preg_match($filtro, $cadena)){
        return true;
    } else {
        return false;
    }
}

#Verificar fechas
function verificar_fecha($filtro, $fecha) {
    if (empty($filtro) || !is_string($filtro) || empty($fecha)) {
        return false;
    }
    if (preg_match($filtro, $fecha)) {
        return validar_fecha_real($fecha, $filtro);
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
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        $cadena = htmlspecialchars($cadena, ENT_QUOTES, 'UTF-8');
        $cadena = strip_tags($cadena);
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

// ============================================================
//  FUNCIÓN AUXILIAR
// ============================================================
if (!function_exists('time_elapsed')) {
    function time_elapsed($datetime) {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        
        if ($diff < 60) return 'Hace ' . $diff . ' segundos';
        if ($diff < 3600) return 'Hace ' . floor($diff / 60) . ' minutos';
        if ($diff < 86400) return 'Hace ' . floor($diff / 3600) . ' horas';
        if ($diff < 2592000) return 'Hace ' . floor($diff / 86400) . ' días';
        if ($diff < 31536000) return 'Hace ' . floor($diff / 2592000) . ' meses';
        return 'Hace ' . floor($diff / 31536000) . ' años';
    }
}

function obtenerEstadoPartido($partido) {
    $estado = $partido['estado'] ?? 'Pendiente';
    
    // Si ya está finalizado, mostrar Finalizado
    if ($estado === 'Finalizado') {
        return 'Finalizado';
    }
    
    // Si la fecha es pasada y no está finalizado, mostrar "Por Cargar"
    $fechaPartido = new DateTime($partido['fecha_partido']);
    $hoy = new DateTime();
    if ($fechaPartido < $hoy && $estado !== 'Finalizado') {
        return 'Por Cargar';
    }
    
    // Si es futuro o está en curso
    return $estado;
}