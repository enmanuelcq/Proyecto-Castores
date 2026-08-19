<?php
// Model/Repository/RepositoryCampo.php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Campo.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUCampoRepository.php';

class RepositoryCampo implements ICampoRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Campo $campo): bool
    {
        $sql = "INSERT INTO campo (nombre_campo, direccion_campo, mapa_campo) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $campo->getNombre(),
            $campo->getDireccion(),
            $campo->getMapa() // puede ser null
        ]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $campo->setId($id);
        }
        return $result;
    }

    public function update(Campo $campo): bool
    {
        $sql = "UPDATE campo SET nombre_campo = ?, direccion_campo = ?, mapa_campo = ? WHERE campo_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $campo->getNombre(),
            $campo->getDireccion(),
            $campo->getMapa(),
            $campo->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM campo WHERE campo_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Campo
    {
        $sql = "SELECT * FROM campo WHERE campo_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToCampo($row) : null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM campo ORDER BY nombre_campo ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToCampos($rows);
    }

    public function findByNombre(string $nombre): ?Campo
    {
        $sql = "SELECT * FROM campo WHERE nombre_campo = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToCampo($row) : null;
    }

    private function mapRowToCampo(array $row): Campo
    {
        return new Campo(
            (int) $row['campo_id'],
            $row['nombre_campo'],
            $row['direccion_campo'],
            $row['mapa_campo'] ?? null
        );
    }

    private function mapRowsToCampos(array $rows): array
    {
        $campos = [];
        foreach ($rows as $row) {
            $campos[] = $this->mapRowToCampo($row);
        }
        return $campos;
    }
}