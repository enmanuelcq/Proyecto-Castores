<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/EntrenamientoRecurso.php';
require_once dirname(__DIR__, 2) . '/Config/Database.php';
require_once dirname(__DIR__, 2) . '/Model/Interfaces/IUEntrenamientoRecursoRepository.php';

class RepositoryEntrenamientoRecurso implements IUEntrenamientoRecursoRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(EntrenamientoRecurso $recurso): bool
    {
        $sql = "INSERT INTO entrenamiento_recurso (entrenamiento_id, tipo_recurso, url, archivo, descripcion)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $recurso->getEntrenamientoId(),
            $recurso->getTipoRecurso(),
            $recurso->getUrl(),
            $recurso->getArchivo(),
            $recurso->getDescripcion(),
        ]);
    }

    public function delete(int $recursoId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM entrenamiento_recurso WHERE recurso_id = ?");
        return $stmt->execute([$recursoId]);
    }

    public function deleteByEntrenamiento(int $entrenamientoId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM entrenamiento_recurso WHERE entrenamiento_id = ?");
        return $stmt->execute([$entrenamientoId]);
    }

    public function findByEntrenamiento(int $entrenamientoId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM entrenamiento_recurso WHERE entrenamiento_id = ? ORDER BY recurso_id ASC"
        );
        $stmt->execute([$entrenamientoId]);
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    private function mapRow(array $row): EntrenamientoRecurso
    {
        return new EntrenamientoRecurso(
            (int) $row['recurso_id'],
            (int) $row['entrenamiento_id'],
            $row['tipo_recurso'] ?? 'enlace',
            $row['url'] ?? null,
            $row['archivo'] ?? null,
            $row['descripcion'] ?? null
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
