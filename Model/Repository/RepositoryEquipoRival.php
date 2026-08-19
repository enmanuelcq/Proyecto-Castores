<?php
// Model/Repository/RepositoryEquipoRival.php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/EquipoRival.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUEquipoRivalRepository.php';

class RepositoryEquipoRival implements IEquipoRivalRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(EquipoRival $equipo): bool
    {
        $sql = "INSERT INTO equipo_rival (nombre_equipo) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$equipo->getNombre()]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $equipo->setId($id);
        }
        return $result;
    }

    public function update(EquipoRival $equipo): bool
    {
        $sql = "UPDATE equipo_rival SET nombre_equipo = ? WHERE equipo_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$equipo->getNombre(), $equipo->getId()]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM equipo_rival WHERE equipo_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?EquipoRival
    {
        $sql = "SELECT * FROM equipo_rival WHERE equipo_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToEquipo($row) : null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM equipo_rival ORDER BY nombre_equipo ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToEquipos($rows);
    }

    public function findByNombre(string $nombre): ?EquipoRival
    {
        $sql = "SELECT * FROM equipo_rival WHERE nombre_equipo = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToEquipo($row) : null;
    }

    private function mapRowToEquipo(array $row): EquipoRival
    {
        return new EquipoRival((int) $row['equipo_id'], $row['nombre_equipo']);
    }

    private function mapRowsToEquipos(array $rows): array
    {
        $equipos = [];
        foreach ($rows as $row) {
            $equipos[] = $this->mapRowToEquipo($row);
        }
        return $equipos;
    }
}