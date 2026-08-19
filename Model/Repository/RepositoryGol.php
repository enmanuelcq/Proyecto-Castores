<?php
// Model/Repository/RepositoryGol.php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Gol.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUGolRepository.php';

class RepositoryGol implements IGolRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Gol $gol): bool
    {
        $sql = "INSERT INTO goles (cant_goles, tiempo, descripcion, Atleta_id, partido_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $gol->getCantGoles(),
            $gol->getTiempo(),
            $gol->getDescripcion(),
            $gol->getAtletaId(),
            $gol->getPartidoId()
        ]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $gol->setId($id);
        }
        return $result;
    }

    public function update(Gol $gol): bool
    {
        $sql = "UPDATE goles SET cant_goles = ?, tiempo = ?, descripcion = ?, Atleta_id = ?, partido_id = ? 
                WHERE gol_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $gol->getCantGoles(),
            $gol->getTiempo(),
            $gol->getDescripcion(),
            $gol->getAtletaId(),
            $gol->getPartidoId(),
            $gol->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM goles WHERE gol_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Gol
    {
        $sql = "SELECT * FROM goles WHERE gol_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToGol($row) : null;
    }

    public function findByPartido(int $partidoId): array
    {
        $sql = "SELECT g.*, a.1er_nombre, a.1er_apellido 
                FROM goles g
                LEFT JOIN atleta a ON g.Atleta_id = a.Atleta_id
                WHERE g.partido_id = ?
                ORDER BY g.tiempo ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$partidoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findByAtleta(string $atletaId): array
    {
        $sql = "SELECT * FROM goles WHERE Atleta_id = ? ORDER BY partido_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteByPartido(int $partidoId): bool
    {
        $sql = "DELETE FROM goles WHERE partido_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$partidoId]);
    }

    private function mapRowToGol(array $row): Gol
    {
        return new Gol(
            (int) $row['gol_id'],
            (int) $row['cant_goles'],
            $row['tiempo'],
            $row['descripcion'],
            $row['Atleta_id'],
            (int) $row['partido_id']
        );
    }
}