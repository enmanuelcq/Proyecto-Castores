<?php
// Model/Repository/RepositoryTarjeta.php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Tarjeta.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUTarjetaRepository.php';

class RepositoryTarjeta implements ITarjetaRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Tarjeta $tarjeta): bool
    {
        $sql = "INSERT INTO tarjetas (tarjeta) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$tarjeta->getTarjeta()]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $tarjeta->setId($id);
        }
        return $result;
    }

    public function update(Tarjeta $tarjeta): bool
    {
        $sql = "UPDATE tarjetas SET tarjeta = ? WHERE tarjeta_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$tarjeta->getTarjeta(), $tarjeta->getId()]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM tarjetas WHERE tarjeta_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Tarjeta
    {
        $sql = "SELECT * FROM tarjetas WHERE tarjeta_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToTarjeta($row) : null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM tarjetas ORDER BY tarjeta_id ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToTarjetas($rows);
    }

    public function findByTipo(string $tipo): ?Tarjeta
    {
        $sql = "SELECT * FROM tarjetas WHERE tarjeta = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tipo]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToTarjeta($row) : null;
    }

    private function mapRowToTarjeta(array $row): Tarjeta
    {
        return new Tarjeta((int) $row['tarjeta_id'], $row['tarjeta']);
    }

    private function mapRowsToTarjetas(array $rows): array
    {
        $tarjetas = [];
        foreach ($rows as $row) {
            $tarjetas[] = $this->mapRowToTarjeta($row);
        }
        return $tarjetas;
    }
}