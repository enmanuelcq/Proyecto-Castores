<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/CategoriaAtleta.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUCategoriaAtletaRepository.php';

class RepositoryCategoriaAtleta implements ICategoriaAtletaRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // ──────────── CREATE ────────────
    public function create(CategoriaAtleta $relacion): bool
    {
        $sql = "INSERT INTO categoria_atleta (año, Atleta_id, categoria_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $relacion->getAnio(),
            $relacion->getAtletaId(),
            $relacion->getCategoriaId()
        ]);
    }

    // ──────────── UPDATE ────────────
    public function update(CategoriaAtleta $relacion): bool
    {
        $sql = "UPDATE categoria_atleta SET año = ?, Atleta_id = ?, categoria_id = ? WHERE año_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $relacion->getAnio(),
            $relacion->getAtletaId(),
            $relacion->getCategoriaId(),
            $relacion->getId()
        ]);
    }

    // ──────────── DELETE ────────────
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM categoria_atleta WHERE año_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function deleteByAtletaAndCategoria(string $atletaId, int $categoriaId): bool
    {
        $sql = "DELETE FROM categoria_atleta WHERE Atleta_id = ? AND categoria_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$atletaId, $categoriaId]);
    }

    // ──────────── FIND BY ID ────────────
    public function findById(int $id): ?CategoriaAtleta
    {
        $sql = "SELECT * FROM categoria_atleta WHERE año_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToCategoriaAtleta($row) : null;
    }

    // ──────────── FIND BY ATLETA ────────────
    public function findByAtleta(string $atletaId): array
    {
        $sql = "SELECT * FROM categoria_atleta WHERE Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToCategoriaAtleta($rows);
    }

    // ──────────── FIND BY CATEGORIA ────────────
    public function findByCategoria(int $categoriaId): array
    {
        $sql = "SELECT * FROM categoria_atleta WHERE categoria_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoriaId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToCategoriaAtleta($rows);
    }

    // ──────────── EXISTS ────────────
    public function exists(string $atletaId, int $categoriaId): bool
    {
        $sql = "SELECT COUNT(*) FROM categoria_atleta WHERE Atleta_id = ? AND categoria_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId, $categoriaId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    // ──────────── PRIVADO: mapeo ────────────
    private function mapRowToCategoriaAtleta(array $row): CategoriaAtleta
    {
        return new CategoriaAtleta(
            (int) $row['año_id'],
            $row['año'],
            $row['Atleta_id'],
            (int) $row['categoria_id']
        );
    }

    private function mapRowsToCategoriaAtleta(array $rows): array
    {
        $relaciones = [];
        foreach ($rows as $row) {
            $relaciones[] = $this->mapRowToCategoriaAtleta($row);
        }
        return $relaciones;
    }
}