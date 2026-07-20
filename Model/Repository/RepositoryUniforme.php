<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Uniforme.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUniformeRepository.php';

class RepositoryUniforme implements IUniformeRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // ──────────── CREATE ────────────
    public function create(Uniforme $uniforme): bool
    {
        $sql = "INSERT INTO uniforme (franela_talla, franela_nro, short_talla, zapatos_talla) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $uniforme->getFranelaTalla(),
            $uniforme->getFranelaNumero(),
            $uniforme->getShortTalla(),
            $uniforme->getZapatosTalla()
        ]);
    }

    // ──────────── UPDATE ────────────
    public function update(Uniforme $uniforme): bool
    {
        $sql = "UPDATE uniforme SET
                    franela_talla = ?,
                    franela_nro = ?,
                    short_talla = ?,
                    zapatos_talla = ?
                WHERE Uniforme_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $uniforme->getFranelaTalla(),
            $uniforme->getFranelaNumero(),
            $uniforme->getShortTalla(),
            $uniforme->getZapatosTalla(),
            $uniforme->getId()
        ]);
    }

    // ──────────── DELETE ────────────
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM uniforme WHERE Uniforme_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ──────────── FIND BY ID ────────────
    public function findById(int $id): ?Uniforme
    {
        $sql = "SELECT * FROM uniforme WHERE Uniforme_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToUniforme($row) : null;
    }

    // ──────────── FIND ALL ────────────
    public function findAll(): array
    {
        $sql = "SELECT * FROM uniforme";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToUniformes($rows);
    }

    // ──────────── FIND BY FRANELA NÚMERO ────────────
    public function findByFranelaNumero(int $numero): array
    {
        $sql = "SELECT * FROM uniforme WHERE franela_nro = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$numero]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToUniformes($rows);
    }

    // ──────────── FIND BY FRANELA TALLA ────────────
    public function findByFranelaTalla(string $talla): array
    {
        $sql = "SELECT * FROM uniforme WHERE franela_talla = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$talla]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToUniformes($rows);
    }

    // ──────────── FIND BY SHORT TALLA ────────────
    public function findByShortTalla(string $talla): array
    {
        $sql = "SELECT * FROM uniforme WHERE short_talla = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$talla]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToUniformes($rows);
    }

    // ──────────── FIND BY ZAPATOS TALLA ────────────
    public function findByZapatosTalla(string $talla): array
    {
        $sql = "SELECT * FROM uniforme WHERE zapatos_talla = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$talla]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToUniformes($rows);
    }

    // ──────────── PRIVADO: mapeo de un row a objeto Uniforme ────────────
    private function mapRowToUniforme(array $row): Uniforme
    {
        return new Uniforme(
            (int) $row['Uniforme_id'],
            $row['franela_talla'],
            (int) $row['franela_nro'],
            $row['short_talla'],
            $row['zapatos_talla']
        );
    }

    // ──────────── PRIVADO: mapeo de múltiples rows ────────────
    private function mapRowsToUniformes(array $rows): array
    {
        $uniformes = [];
        foreach ($rows as $row) {
            $uniformes[] = $this->mapRowToUniforme($row);
        }
        return $uniformes;
    }
}