<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/CategoriaEntrenamiento.php';
require_once dirname(__DIR__, 2) . '/Config/Database.php';
require_once dirname(__DIR__, 2) . '/Model/Interfaces/IUCategoriaEntrenamientoRepository.php';

class RepositoryCategoriaEntrenamiento implements IUCategoriaEntrenamientoRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(CategoriaEntrenamiento $ce): bool
    {
        $sql = "INSERT INTO categoria_entrenamiento (categoria_id, entrenamiento_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $ce->getCategoriaId(),
            $ce->getEntrenamientoId(),
        ]);
    }

    public function delete(int $convocatoriaId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categoria_entrenamiento WHERE convocatoria_id = ?");
        return $stmt->execute([$convocatoriaId]);
    }

    public function deleteByEntrenamiento(int $entrenamientoId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM categoria_entrenamiento WHERE entrenamiento_id = ?");
        return $stmt->execute([$entrenamientoId]);
    }

    public function findByEntrenamiento(int $entrenamientoId): array
    {
        $sql = "SELECT ce.convocatoria_id, ce.categoria_id, ce.entrenamiento_id, c.sub_categoria
                FROM categoria_entrenamiento ce
                LEFT JOIN categoria c ON ce.categoria_id = c.categoria_id
                WHERE ce.entrenamiento_id = ?
                ORDER BY ce.categoria_id ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$entrenamientoId]);
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function findCategoriaIdsByEntrenamiento(int $entrenamientoId): array
    {
        $stmt = $this->db->prepare("SELECT categoria_id FROM categoria_entrenamiento WHERE entrenamiento_id = ?");
        $stmt->execute([$entrenamientoId]);
        return array_map('intval', $stmt->fetchAll(\PDO::FETCH_COLUMN));
    }

    private function mapRow(array $row): CategoriaEntrenamiento
    {
        return new CategoriaEntrenamiento(
            (int) $row['convocatoria_id'],
            (int) $row['categoria_id'],
            (int) $row['entrenamiento_id'],
            $row['sub_categoria'] ?? null
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
