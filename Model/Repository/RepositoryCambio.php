<?php
// Model/Repository/RepositoryCambio.php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Cambio.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUCambioRepository.php';

class RepositoryCambio implements ICambioRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Cambio $cambio): bool
    {
        $sql = "INSERT INTO cambios (tiempo, minuto, tipo_cambio, Atleta_id, Atleta_sale_id, partido_id) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $cambio->getTiempo(),
            $cambio->getMinuto(),
            $cambio->getTipoCambio(),
            $cambio->getAtletaEntraId(),
            $cambio->getAtletaSaleId(),
            $cambio->getPartidoId()
        ]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $cambio->setId($id);
        }
        return $result;
    }

    public function update(Cambio $cambio): bool
    {
        $sql = "UPDATE cambios SET 
                    tiempo = ?,
                    minuto = ?,
                    tipo_cambio = ?,
                    Atleta_id = ?,
                    Atleta_sale_id = ?,
                    partido_id = ?
                WHERE cambio_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $cambio->getTiempo(),
            $cambio->getMinuto(),
            $cambio->getTipoCambio(),
            $cambio->getAtletaEntraId(),
            $cambio->getAtletaSaleId(),
            $cambio->getPartidoId(),
            $cambio->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM cambios WHERE cambio_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Cambio
    {
        $sql = "SELECT * FROM cambios WHERE cambio_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToCambio($row) : null;
    }

    public function findByPartido(int $partidoId): array
    {
        $sql = "SELECT c.*, 
                    a1.1er_nombre AS entra_nombre, a1.1er_apellido AS entra_apellido,
                    a2.1er_nombre AS sale_nombre, a2.1er_apellido AS sale_apellido
                FROM cambios c
                LEFT JOIN atleta a1 ON c.Atleta_id = a1.Atleta_id
                LEFT JOIN atleta a2 ON c.Atleta_sale_id = a2.Atleta_id
                WHERE c.partido_id = ?
                ORDER BY c.minuto ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$partidoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteByPartido(int $partidoId): bool
    {
        $sql = "DELETE FROM cambios WHERE partido_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$partidoId]);
    }

    private function mapRowToCambio(array $row): Cambio
    {
        return new Cambio(
            (int) $row['cambio_id'],
            $row['tiempo'],
            $row['minuto'],
            $row['tipo_cambio'] ?? null,
            $row['Atleta_id'],
            $row['Atleta_sale_id'] ?? null,
            (int) $row['partido_id']
        );
    }
}