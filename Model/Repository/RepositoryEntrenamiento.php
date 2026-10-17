<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/Entrenamiento.php';
require_once dirname(__DIR__, 2) . '/Config/Database.php';
require_once dirname(__DIR__, 2) . '/Model/Interfaces/IUEntrenamientoRepository.php';

class RepositoryEntrenamiento implements IUEntrenamientoRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Entrenamiento $e): bool
    {
        $sql = "INSERT INTO entrenamiento (
                    fecha_entrenamiento, hora_inicio, hora_fin,
                    objetivo_entrenamiento, tiempo_estimado, tipo_entrenamiento,
                    categoria, lugar, estado, total_atletas, descripcion,
                    usuario_cedula
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $obj = $e->getObjetivoEntrenamiento() ?: ($e->getDescripcion() ?: '');
        $result = $stmt->execute([
            $e->getFechaEntrenamiento(),
            $e->getHoraInicio(),
            $e->getHoraFin(),
            $obj,
            $e->getTiempoEstimado(),
            $e->getTipoEntrenamiento(),
            $e->getCategoria(),
            $e->getLugar(),
            $e->getEstado(),
            $e->getTotalAtletas(),
            $e->getDescripcion(),
            $e->getUsuarioCedula(),
        ]);
        if ($result) {
            $e->setEntrenamientoId((int) $this->db->lastInsertId());
        }
        return $result;
    }

    public function update(Entrenamiento $e): bool
    {
        $sql = "UPDATE entrenamiento SET
                    fecha_entrenamiento = ?,
                    hora_inicio = ?,
                    hora_fin = ?,
                    objetivo_entrenamiento = ?,
                    tiempo_estimado = ?,
                    tipo_entrenamiento = ?,
                    categoria = ?,
                    lugar = ?,
                    estado = ?,
                    total_atletas = ?,
                    descripcion = ?,
                    usuario_cedula = ?
                WHERE entrenamiento_id = ?";

        $stmt = $this->db->prepare($sql);
        $obj = $e->getObjetivoEntrenamiento() ?: ($e->getDescripcion() ?: '');
        return $stmt->execute([
            $e->getFechaEntrenamiento(),
            $e->getHoraInicio(),
            $e->getHoraFin(),
            $obj,
            $e->getTiempoEstimado(),
            $e->getTipoEntrenamiento(),
            $e->getCategoria(),
            $e->getLugar(),
            $e->getEstado(),
            $e->getTotalAtletas(),
            $e->getDescripcion(),
            $e->getUsuarioCedula(),
            $e->getEntrenamientoId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM entrenamiento WHERE entrenamiento_id = ?");
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Entrenamiento
    {
        $stmt = $this->db->prepare("SELECT * FROM entrenamiento WHERE entrenamiento_id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRow($row) : null;
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM entrenamiento ORDER BY fecha_entrenamiento DESC");
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function findByCategoria(string $categoria): array
    {
        $stmt = $this->db->prepare("SELECT * FROM entrenamiento WHERE categoria = ? ORDER BY fecha_entrenamiento DESC");
        $stmt->execute([$categoria]);
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function findByEstado(string $estado): array
    {
        $stmt = $this->db->prepare("SELECT * FROM entrenamiento WHERE estado = ? ORDER BY fecha_entrenamiento DESC");
        $stmt->execute([$estado]);
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function findByUsuarioCedula(string $cedula): array
    {
        $stmt = $this->db->prepare("SELECT * FROM entrenamiento WHERE usuario_cedula = ? ORDER BY fecha_entrenamiento DESC");
        $stmt->execute([$cedula]);
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function findByFecha(string $fecha): array
    {
        $stmt = $this->db->prepare("SELECT * FROM entrenamiento WHERE DATE(fecha_entrenamiento) = ? ORDER BY fecha_entrenamiento DESC");
        $stmt->execute([$fecha]);
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function findByRangoFechas(string $desde, string $hasta): array
    {
        $stmt = $this->db->prepare("SELECT * FROM entrenamiento WHERE DATE(fecha_entrenamiento) BETWEEN ? AND ? ORDER BY fecha_entrenamiento DESC");
        $stmt->execute([$desde, $hasta]);
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function findProximos(int $limite = 5): array
    {
        $hoy = date('Y-m-d');
        $stmt = $this->db->prepare(
            "SELECT * FROM entrenamiento 
             WHERE DATE(fecha_entrenamiento) >= ? AND estado != 'Finalizado' 
             ORDER BY fecha_entrenamiento ASC 
             LIMIT ?"
        );
        $stmt->execute([$hoy, $limite]);
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function countByEstado(string $estado): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM entrenamiento WHERE estado = ?");
        $stmt->execute([$estado]);
        return (int) $stmt->fetchColumn();
    }

    public function countByCategoria(string $categoria): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM entrenamiento WHERE categoria = ?");
        $stmt->execute([$categoria]);
        return (int) $stmt->fetchColumn();
    }

    public function countByUsuarioCedula(string $cedula): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM entrenamiento WHERE usuario_cedula = ?");
        $stmt->execute([$cedula]);
        return (int) $stmt->fetchColumn();
    }

    public function estadisticasEntrenador(string $cedula): array
    {
        $stmt = $this->db->prepare(
            "SELECT 
                COUNT(*) AS total,
                SUM(CASE WHEN estado = 'Finalizado' THEN 1 ELSE 0 END) AS finalizados,
                SUM(CASE WHEN estado = 'En curso' THEN 1 ELSE 0 END) AS en_curso,
                SUM(CASE WHEN estado = 'Programado' THEN 1 ELSE 0 END) AS programados,
                SUM(total_atletas) AS total_atletas
             FROM entrenamiento WHERE usuario_cedula = ?"
        );
        $stmt->execute([$cedula]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        $total = (int)($row['total'] ?? 0);
        $finalizados = (int)($row['finalizados'] ?? 0);
        $en_curso = (int)($row['en_curso'] ?? 0);
        $programados = (int)($row['programados'] ?? 0);
        $total_atletas = (int)($row['total_atletas'] ?? 0);

        return [
            'total' => $total,
            'finalizados' => $finalizados,
            'en_curso' => $en_curso,
            'programados' => $programados,
            'total_asistentes' => 0,
            'total_atletas' => $total_atletas,
            'promedio_asistencia' => 0,
        ];
    }

    public function findAllFiltered(?string $categoria = null, ?string $estado = null): array
    {
        $sql = "SELECT * FROM entrenamiento WHERE 1=1";
        $params = [];

        if ($categoria) {
            $sql .= " AND categoria = ?";
            $params[] = $categoria;
        }
        if ($estado) {
            $sql .= " AND estado = ?";
            $params[] = $estado;
        }

        $sql .= " ORDER BY fecha_entrenamiento DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    private function mapRow(array $row): Entrenamiento
    {
        return new Entrenamiento(
            (int) $row['entrenamiento_id'],
            $row['fecha_entrenamiento'],
            $row['hora_inicio'] ?? '08:00',
            $row['hora_fin'] ?? '10:00',
            $row['objetivo_entrenamiento'] ?? '',
            $row['tiempo_estimado'] ?? '02:00:00',
            $row['tipo_entrenamiento'] ?? '',
            $row['categoria'] ?? '',
            $row['lugar'] ?? '',
            $row['estado'] ?? 'Programado',
            (int) ($row['total_atletas'] ?? 0),
            $row['descripcion'] ?? null,
            $row['usuario_cedula'] ?? ''
        );
    }

    private function mapRows(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            $result[] = $this->mapRow($row);
        }
        return $result;
    }
}
