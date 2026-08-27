<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Config/Database.php';

class RepositoryPrueba
{
    private \PDO $db;

    private static array $TABLES = [
        'balon_aereo' => [
            'table' => 'prueba_balon_aereo',
            'pk' => 'id_balon_aereo',
            'date_col' => 'fecha',
            'value_col' => 'total_toques',
        ],
        'conduccion' => [
            'table' => 'prueba_conduccion',
            'pk' => 'id_conduccion',
            'date_col' => 'fecha',
            'value_col' => 'tiempo_ambas_piernas',
        ],
        'definicion' => [
            'table' => 'prueba_definicion',
            'pk' => 'id_definicion',
            'date_col' => 'fecha',
            'value_col' => 'cantidad_tiros',
        ],
        'fuerza' => [
            'table' => 'prueba_fuerza',
            'pk' => 'pruebaf_id',
            'date_col' => 'pruebaf_fecha',
            'value_col' => 'pruebaf_flexiones_1min',
        ],
        'pases' => [
            'table' => 'prueba_pases',
            'pk' => 'id_pases',
            'date_col' => 'fecha',
            'value_col' => 'total_pases',
        ],
        'recepcion' => [
            'table' => 'prueba_recepcion_balon',
            'pk' => 'id_recepcion',
            'date_col' => 'fecha',
            'value_col' => 'cantidad_pases',
        ],
        'resistencia' => [
            'table' => 'prueba_resistencia',
            'pk' => 'id_resistencia',
            'date_col' => 'fecha',
            'value_col' => 'tiempo_total',
        ],
        'salto' => [
            'table' => 'prueba_salto',
            'pk' => 'pruebaS_id',
            'date_col' => 'pruebaS_fecha',
            'value_col' => 'pruebaS_Promedio',
        ],
        'velocidad' => [
            'table' => 'prueba_velocidad',
            'pk' => 'pruebav_id',
            'date_col' => 'pruebav_fecha',
            'value_col' => 'pruebav_Promedio',
        ],
    ];

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findAllByAtleta(string $atletaId): array
    {
        $resultado = [];
        foreach (self::$TABLES as $tipo => $cfg) {
            $sql = "SELECT * FROM `{$cfg['table']}` WHERE Atleta_id = ? ORDER BY `{$cfg['date_col']}` ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$atletaId]);
            $resultado[$tipo] = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }
        return $resultado;
    }

    public function findByAtletaAndType(string $atletaId, string $type): array
    {
        if (!isset(self::$TABLES[$type])) return [];
        $cfg = self::$TABLES[$type];
        $sql = "SELECT * FROM `{$cfg['table']}` WHERE Atleta_id = ? ORDER BY `{$cfg['date_col']}` ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countByAtleta(string $atletaId): int
    {
        $total = 0;
        foreach (self::$TABLES as $cfg) {
            $sql = "SELECT COUNT(*) FROM `{$cfg['table']}` WHERE Atleta_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$atletaId]);
            $total += (int) $stmt->fetchColumn();
        }
        return $total;
    }

    public function countByAtletaAndType(string $atletaId, string $type): int
    {
        if (!isset(self::$TABLES[$type])) return 0;
        $cfg = self::$TABLES[$type];
        $sql = "SELECT COUNT(*) FROM `{$cfg['table']}` WHERE Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        return (int) $stmt->fetchColumn();
    }

    public function getPromediosByCategoria(int $categoriaId): array
    {
        $promedios = [];
        foreach (self::$TABLES as $tipo => $cfg) {
            $sql = "SELECT AVG(`{$cfg['value_col']}`) AS promedio
                    FROM `{$cfg['table']}`
                    WHERE categoria_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$categoriaId]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);
            $promedios[$tipo] = $row && $row['promedio'] !== null ? round((float) $row['promedio'], 2) : 0;
        }
        return $promedios;
    }

    public function getLatestByAtletaAndType(string $atletaId, string $type): ?array
    {
        if (!isset(self::$TABLES[$type])) return null;
        $cfg = self::$TABLES[$type];
        $sql = "SELECT * FROM `{$cfg['table']}` WHERE Atleta_id = ? ORDER BY `{$cfg['date_col']}` DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function getValueColumn(string $type): string
    {
        return self::$TABLES[$type]['value_col'] ?? '';
    }

    public function getDateColumn(string $type): string
    {
        return self::$TABLES[$type]['date_col'] ?? 'fecha';
    }

    public static function getTableConfig(): array
    {
        return self::$TABLES;
    }

    private static array $ASPECTS = [
        'balon_aereo' => [
            'total_toques' => 'Total Toques',
            'toques_pecho' => 'Pecho',
            'toques_rodilla' => 'Rodilla',
            'toques_pie' => 'Pie',
        ],
        'conduccion' => [
            'tiempo_derecha' => 'Derecha',
            'tiempo_izquierda' => 'Izquierda',
            'tiempo_ambas_piernas' => 'Ambas',
        ],
        'definicion' => [
            'centro_bajo' => 'Centro Bajo',
            'centro_alto' => 'Centro Alto',
            'derecha_bajo' => 'Der. Bajo',
            'derecha_alto' => 'Der. Alto',
            'izquierda_bajo' => 'Izq. Bajo',
            'izquierda_alto' => 'Izq. Alto',
            'empeine' => 'Empeine',
        ],
        'fuerza' => [
            'pruebaf_flexiones_1min' => 'Flexiones',
            'pruebaf_abdominales_1min' => 'Abdominales',
            'pruebaf_sentadillas_1min' => 'Sentadillas',
        ],
        'pases' => [
            'total_pases' => 'Total',
            'derecha' => 'Derecha',
            'izquierda' => 'Izquierda',
            'primera_intencion' => '1ra Intención',
        ],
        'recepcion' => [
            'derecha' => 'Derecha',
            'izquierda' => 'Izquierda',
            'derecha_orientado' => 'Der. Orientado',
            'izquierda_orientado' => 'Izq. Orientado',
            'orientado_giro' => 'Giro',
        ],
        'resistencia' => [
            'tiempo_total' => 'Tiempo Total',
            'pausa' => 'Pausa',
        ],
        'salto' => [
            'pruebaS_1er_salto' => '1er Salto',
            'pruebaS_2do_salto' => '2do Salto',
            'pruebaS_3er_salto' => '3er Salto',
            'pruebaS_Promedio' => 'Promedio',
        ],
        'velocidad' => [
            'pruebav_1er_intento_seg' => '1er Intento',
            'pruebav_2do_intento_seg' => '2do Intento',
            'pruebav_3er_intento_seg' => '3er Intento',
            'pruebav_Promedio' => 'Promedio',
        ],
    ];

    public static function getAspects(string $type): array
    {
        return self::$ASPECTS[$type] ?? [];
    }

    public function getAspectAveragesByAtleta(string $atletaId, string $type): array
    {
        if (!isset(self::$TABLES[$type])) return [];
        $cfg = self::$TABLES[$type];
        $aspects = self::$ASPECTS[$type] ?? [];
        if (empty($aspects)) return [];

        $cols = array_keys($aspects);
        $selects = [];
        foreach ($cols as $col) {
            $selects[] = "AVG(`{$col}`) AS `avg_{$col}`";
        }
        $sql = "SELECT " . implode(', ', $selects) . " FROM `{$cfg['table']}` WHERE Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $result = [];
        foreach ($aspects as $col => $label) {
            $result[$label] = $row && $row["avg_{$col}"] !== null ? round((float) $row["avg_{$col}"], 2) : 0;
        }
        return $result;
    }

    public function getAspectAveragesByCategoria(int $categoriaId, string $type): array
    {
        if (!isset(self::$TABLES[$type])) return [];
        $cfg = self::$TABLES[$type];
        $aspects = self::$ASPECTS[$type] ?? [];
        if (empty($aspects)) return [];

        $cols = array_keys($aspects);
        $selects = [];
        foreach ($cols as $col) {
            $selects[] = "AVG(`{$col}`) AS `avg_{$col}`";
        }
        $sql = "SELECT " . implode(', ', $selects) . " FROM `{$cfg['table']}` WHERE categoria_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoriaId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $result = [];
        foreach ($aspects as $col => $label) {
            $result[$label] = $row && $row["avg_{$col}"] !== null ? round((float) $row["avg_{$col}"], 2) : 0;
        }
        return $result;
    }

    public function getLatestAspects(string $atletaId, string $type): array
    {
        if (!isset(self::$TABLES[$type])) return [];
        $cfg = self::$TABLES[$type];
        $aspects = self::$ASPECTS[$type] ?? [];
        if (empty($aspects)) return [];

        $sql = "SELECT * FROM `{$cfg['table']}` WHERE Atleta_id = ? ORDER BY `{$cfg['date_col']}` DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $result = [];
        foreach ($aspects as $col => $label) {
            $result[$label] = $row && isset($row[$col]) ? round((float) $row[$col], 2) : 0;
        }
        return $result;
    }
}
