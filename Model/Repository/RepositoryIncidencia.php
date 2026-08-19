<?php
// Model/Repository/RepositoryIncidencia.php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Incidencia.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUIncidenciaRepository.php';

class RepositoryIncidencia implements IIncidenciaRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Incidencia $incidencia): bool
    {
        $sql = "INSERT INTO incidencia (descripcion, Atleta_id, partido_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $incidencia->getDescripcion(),
            $incidencia->getAtletaId(),
            $incidencia->getPartidoId()
        ]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $incidencia->setId($id);
        }
        return $result;
    }

    public function update(Incidencia $incidencia): bool
    {
        $sql = "UPDATE incidencia SET descripcion = ?, Atleta_id = ?, partido_id = ? WHERE incidencia_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $incidencia->getDescripcion(),
            $incidencia->getAtletaId(),
            $incidencia->getPartidoId(),
            $incidencia->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM incidencia WHERE incidencia_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Incidencia
    {
        $sql = "SELECT * FROM incidencia WHERE incidencia_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToIncidencia($row) : null;
    }

    public function findByPartido(int $partidoId): array
    {
        $sql = "SELECT i.*, a.1er_nombre, a.1er_apellido 
                FROM incidencia i
                LEFT JOIN atleta a ON i.Atleta_id = a.Atleta_id
                WHERE i.partido_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$partidoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteByPartido(int $partidoId): bool
    {
        $sql = "DELETE FROM incidencia WHERE partido_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$partidoId]);
    }

    private function mapRowToIncidencia(array $row): Incidencia
    {
        return new Incidencia(
            (int) $row['incidencia_id'],
            $row['descripcion'],
            $row['Atleta_id'],
            (int) $row['partido_id']
        );
    }
}