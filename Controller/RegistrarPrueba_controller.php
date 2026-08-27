<?php
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host . '/Castores3/';
$root = dirname(__DIR__);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $baseUrl . 'index.php?View=Pruebas');
    exit;
}

if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ' . $baseUrl . 'index.php?View=Pruebas&error=' . urlencode('Token CSRF inválido'));
    exit;
}

require_once $root . '/Config/main.php';
require_once $root . '/Config/Database.php';

$tipo_prueba = limpiar_cadena($_POST['tipo_prueba'] ?? '');
$fecha = limpiar_cadena($_POST['fecha'] ?? '');
$atleta_id = limpiar_cadena($_POST['atleta_id'] ?? '');
$categoria_id = intval($_POST['categoria_id_hidden'] ?? 0);
$usuario_cedula = $_SESSION['usuario']['usuario_cedula'] ?? '';

if (empty($tipo_prueba) || empty($fecha) || $categoria_id <= 0 || empty($atleta_id)) {
    $backUrl = $baseUrl . 'index.php?View=Pruebas&id=' . $atleta_id . '&error=' . urlencode('Todos los campos obligatorios deben ser completados');
    header('Location: ' . $backUrl);
    exit;
}

$db = Database::getConnection();

try {
    switch ($tipo_prueba) {
        case 'balon_aereo':
            $total_toques = intval($_POST['total_toques'] ?? 0);
            $toques_pecho = intval($_POST['toques_pecho'] ?? 0);
            $toques_rodilla = intval($_POST['toques_rodilla'] ?? 0);
            $toques_pie = intval($_POST['toques_pie'] ?? 0);
            
            if ($total_toques <= 0) throw new \Exception('El total de toques es requerido');
            if ($toques_pecho > $total_toques) throw new \Exception('Toques pecho no puede superar el total');
            if ($toques_rodilla > $total_toques) throw new \Exception('Toques rodilla no puede superar el total');
            if ($toques_pie > $total_toques) throw new \Exception('Toques pie no puede superar el total');
            
            $sql = "INSERT INTO prueba_balon_aereo (usuario_cedula, Atleta_id, categoria_id, total_toques, toques_pecho, toques_rodilla, toques_pie, fecha)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$usuario_cedula, $atleta_id, $categoria_id, $total_toques, $toques_pecho, $toques_rodilla, $toques_pie, $fecha]);
            break;

        case 'conduccion':
            $distancia_metros = floatval($_POST['cond_distancia_metros'] ?? 0);
            $distancia_obstaculos = floatval($_POST['cond_distancia_obstaculos'] ?? 0);
            $tiempo_derecha = floatval($_POST['cond_tiempo_derecha'] ?? 0);
            $tiempo_izquierda = floatval($_POST['cond_tiempo_izquierda'] ?? 0);
            $tiempo_ambas_piernas = floatval($_POST['cond_tiempo_ambas_piernas'] ?? 0);
            $descripcion = limpiar_cadena($_POST['cond_descripcion'] ?? '');
            
            if ($distancia_metros <= 0) throw new \Exception('La distancia total es requerida');
            if ($distancia_obstaculos > $distancia_metros && $distancia_metros > 0) throw new \Exception('Dist. obstáculos no puede superar la distancia total');
            if (empty($descripcion)) throw new \Exception('El tipo de ejercicio es requerido');
            
            $sql = "INSERT INTO prueba_conduccion (usuario_cedula, Atleta_id, categoria_id, distancia_metros, distancia_obstaculos, tiempo_derecha, tiempo_izquierda, tiempo_ambas_piernas, fecha, descripcion)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$usuario_cedula, $atleta_id, $categoria_id, $distancia_metros, $distancia_obstaculos, $tiempo_derecha, $tiempo_izquierda, $tiempo_ambas_piernas, $fecha, $descripcion]);
            break;

        case 'definicion':
            $cantidad_tiros = intval($_POST['cantidad_tiros'] ?? 0);
            $centro_bajo = intval($_POST['centro_bajo'] ?? 0);
            $centro_alto = intval($_POST['centro_alto'] ?? 0);
            $derecha_bajo = intval($_POST['derecha_bajo'] ?? 0);
            $derecha_alto = intval($_POST['derecha_alto'] ?? 0);
            $izquierda_bajo = intval($_POST['izquierda_bajo'] ?? 0);
            $izquierda_alto = intval($_POST['izquierda_alto'] ?? 0);
            $empeine = intval($_POST['empeine'] ?? 0);
            
            if ($cantidad_tiros <= 0) throw new \Exception('La cantidad de tiros es requerida');
            if ($centro_bajo > $cantidad_tiros) throw new \Exception('Centro bajo no puede superar los tiros por zona');
            if ($centro_alto > $cantidad_tiros) throw new \Exception('Centro alto no puede superar los tiros por zona');
            if ($derecha_bajo > $cantidad_tiros) throw new \Exception('Derecha bajo no puede superar los tiros por zona');
            if ($derecha_alto > $cantidad_tiros) throw new \Exception('Derecha alto no puede superar los tiros por zona');
            if ($izquierda_bajo > $cantidad_tiros) throw new \Exception('Izquierda bajo no puede superar los tiros por zona');
            if ($izquierda_alto > $cantidad_tiros) throw new \Exception('Izquierda alto no puede superar los tiros por zona');
            if ($empeine > $cantidad_tiros) throw new \Exception('Empeine no puede superar los tiros por zona');
            
            $sql = "INSERT INTO prueba_definicion (usuario_cedula, Atleta_id, categoria_id, distancia_metros, cantidad_tiros, centro_bajo, centro_alto, derecha_bajo, derecha_alto, izquierda_bajo, izquierda_alto, empeine, fecha)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$usuario_cedula, $atleta_id, $categoria_id, floatval($_POST['def_distancia_metros'] ?? 0), $cantidad_tiros, $centro_bajo, $centro_alto, $derecha_bajo, $derecha_alto, $izquierda_bajo, $izquierda_alto, $empeine, $fecha]);
            break;

        case 'fuerza':
            $maxId = $db->query("SELECT COALESCE(MAX(pruebaf_id), 0) + 1 FROM prueba_fuerza")->fetchColumn();
            $sql = "INSERT INTO prueba_fuerza (pruebaf_id, Atleta_id, categoria_id, usuario_cedula, pruebaf_flexiones_1min, pruebaf_abdominales_1min, pruebaf_sentadillas_1min, pruebaf_fecha)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([
                $maxId, $atleta_id, $categoria_id, $usuario_cedula,
                intval($_POST['pruebaf_flexiones_1min'] ?? 0),
                intval($_POST['pruebaf_abdominales_1min'] ?? 0),
                intval($_POST['pruebaf_sentadillas_1min'] ?? 0),
                $fecha
            ]);
            break;

        case 'pases':
            $total_pases = intval($_POST['total_pases'] ?? 0);
            $derecha = intval($_POST['pas_derecha'] ?? 0);
            $izquierda = intval($_POST['pas_izquierda'] ?? 0);
            $primera_intencion = intval($_POST['pas_primera_intencion'] ?? 0);
            
            if ($total_pases <= 0) throw new \Exception('El total de pases es requerido');
            if ($derecha > $total_pases) throw new \Exception('Pases derecha no puede superar el total');
            if ($izquierda > $total_pases) throw new \Exception('Pases izquierda no puede superar el total');
            if ($primera_intencion > $total_pases) throw new \Exception('Pases 1ra intención no puede superar el total');
            
            $sql = "INSERT INTO prueba_pases (usuario_cedula, Atleta_id, categoria_id, total_pases, derecha, izquierda, primera_intencion, fecha)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$usuario_cedula, $atleta_id, $categoria_id, $total_pases, $derecha, $izquierda, $primera_intencion, $fecha]);
            break;

        case 'recepcion':
            $cantidad_pases = intval($_POST['cantidad_pases'] ?? 0);
            $derecha = intval($_POST['rec_derecha'] ?? 0);
            $izquierda = intval($_POST['rec_izquierda'] ?? 0);
            $derecha_orientado = intval($_POST['rec_derecha_orientado'] ?? 0);
            $izquierda_orientado = intval($_POST['rec_izquierda_orientado'] ?? 0);
            $orientado_giro = intval($_POST['rec_orientado_giro'] ?? 0);
            
            if ($cantidad_pases <= 0) throw new \Exception('La cantidad de pases es requerida');
            if ($derecha > $cantidad_pases) throw new \Exception('Recepción derecha no puede superar el total de pases');
            if ($izquierda > $cantidad_pases) throw new \Exception('Recepción izquierda no puede superar el total de pases');
            if ($derecha_orientado > $cantidad_pases) throw new \Exception('Der. orientado no puede superar el total de pases');
            if ($izquierda_orientado > $cantidad_pases) throw new \Exception('Izq. orientado no puede superar el total de pases');
            if ($orientado_giro > $cantidad_pases) throw new \Exception('Orientado con giro no puede superar el total de pases');
            
            $sql = "INSERT INTO prueba_recepcion_balon (usuario_cedula, Atleta_id, categoria_id, distancia_metros, cantidad_pases, derecha, izquierda, derecha_orientado, izquierda_orientado, orientado_giro, fecha)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$usuario_cedula, $atleta_id, $categoria_id, floatval($_POST['rec_distancia_metros'] ?? 0), $cantidad_pases, $derecha, $izquierda, $derecha_orientado, $izquierda_orientado, $orientado_giro, $fecha]);
            break;

        case 'resistencia':
            $tiempo_total = floatval($_POST['tiempo_total'] ?? 0);
            $pausa = floatval($_POST['pausa'] ?? 0);
            
            if ($tiempo_total <= 0) throw new \Exception('El tiempo total es requerido');
            if ($pausa > $tiempo_total && $tiempo_total > 0) throw new \Exception('La pausa no puede superar el tiempo total');
            
            $sql = "INSERT INTO prueba_resistencia (usuario_cedula, Atleta_id, categoria_id, tiempo_total, pausa, fecha)
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$usuario_cedula, $atleta_id, $categoria_id, $tiempo_total, $pausa, $fecha]);
            break;

        case 'salto':
            $pruebaS_alt_inicial = floatval($_POST['pruebaS_alt_inicial'] ?? 0);
            $salto1 = floatval($_POST['pruebaS_1er_salto'] ?? 0);
            $salto2 = floatval($_POST['pruebaS_2do_salto'] ?? 0);
            $salto3 = floatval($_POST['pruebaS_3er_salto'] ?? 0);
            
            if ($pruebaS_alt_inicial <= 0) throw new \Exception('La altura inicial es requerida');
            
            $count = 0;
            $promedio = 0;
            if ($salto1 > 0) { $promedio += $salto1; $count++; }
            if ($salto2 > 0) { $promedio += $salto2; $count++; }
            if ($salto3 > 0) { $promedio += $salto3; $count++; }
            $promedio = $count > 0 ? round($promedio / $count, 2) : 0;
            
            $maxId = $db->query("SELECT COALESCE(MAX(pruebaS_id), 0) + 1 FROM prueba_salto")->fetchColumn();
            $sql = "INSERT INTO prueba_salto (pruebaS_id, Atleta_id, categoria_id, usuario_cedula, pruebaS_alt_inicial, pruebaS_1er_salto, pruebaS_2do_salto, pruebaS_3er_salto, pruebaS_Promedio, pruebaS_fecha)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$maxId, $atleta_id, $categoria_id, $usuario_cedula, $pruebaS_alt_inicial, $salto1, $salto2, $salto3, $promedio, $fecha]);
            break;

        case 'velocidad':
            $pruebav_distancia_mtr = floatval($_POST['pruebav_distancia_mtr'] ?? 0);
            $intento1 = floatval($_POST['pruebav_1er_intento_seg'] ?? 0);
            $intento2 = floatval($_POST['pruebav_2do_intento_seg'] ?? 0);
            $intento3 = floatval($_POST['pruebav_3er_intento_seg'] ?? 0);
            
            if ($pruebav_distancia_mtr <= 0) throw new \Exception('La distancia es requerida');
            
            $count = 0;
            $promedio = 0;
            if ($intento1 > 0) { $promedio += $intento1; $count++; }
            if ($intento2 > 0) { $promedio += $intento2; $count++; }
            if ($intento3 > 0) { $promedio += $intento3; $count++; }
            $promedio = $count > 0 ? round($promedio / $count, 2) : 0;
            
            $maxId = $db->query("SELECT COALESCE(MAX(pruebav_id), 0) + 1 FROM prueba_velocidad")->fetchColumn();
            $sql = "INSERT INTO prueba_velocidad (pruebav_id, Atleta_id, categoria_id, usuario_cedula, pruebav_distancia_mtr, pruebav_1er_intento_seg, pruebav_2do_intento_seg, pruebav_3er_intento_seg, pruebav_Promedio, pruebav_fecha)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$maxId, $atleta_id, $categoria_id, $usuario_cedula, $pruebav_distancia_mtr, $intento1, $intento2, $intento3, $promedio, $fecha]);
            break;

        default:
            throw new \Exception('Tipo de prueba no válido: ' . $tipo_prueba);
    }

    $backUrl = $baseUrl . 'index.php?View=Pruebas&id=' . $atleta_id . '&success=' . urlencode('Prueba registrada correctamente');
    header('Location: ' . $backUrl);
    exit;

} catch (\Exception $e) {
    error_log('Error al registrar prueba: ' . $e->getMessage());
    $backUrl = $baseUrl . 'index.php?View=Pruebas&id=' . $atleta_id . '&error=' . urlencode('Error al guardar: ' . $e->getMessage());
    header('Location: ' . $backUrl);
    exit;
}
