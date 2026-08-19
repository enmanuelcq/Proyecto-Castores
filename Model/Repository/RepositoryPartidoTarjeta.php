<?php
// Model/Repository/RepositoryPartidoTarjeta.php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/PartidoTarjeta.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUPartidoTarjetaRepository.php';

class RepositoryPartidoTarjeta implements IPartidoTarjetaRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(PartidoTarjeta $pt): bool
    {
        $sql = "INSERT INTO partido_tarjetas (Atleta_id, partido_id, tarjeta_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $pt->getAtletaId(),
            $pt->getPartidoId(),
            $pt->getTarjetaId()
        ]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $pt->setId($id);
        }
        return $result;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM partido_tarjetas WHERE p_t_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findByPartido(int $partidoId): array
    {
        $sql = "SELECT pt.*, t.tarjeta, a.1er_nombre, a.1er_apellido 
                FROM partido_tarjetas pt
                LEFT JOIN tarjetas t ON pt.tarjeta_id = t.tarjeta_id
                LEFT JOIN atleta a ON pt.Atleta_id = a.Atleta_id
                WHERE pt.partido_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$partidoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findByAtleta(string $atletaId): array
    {
        $sql = "SELECT * FROM partido_tarjetas WHERE Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteByPartido(int $partidoId): bool
    {
        $sql = "DELETE FROM partido_tarjetas WHERE partido_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$partidoId]);
    }
}