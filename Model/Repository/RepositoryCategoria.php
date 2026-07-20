<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Categoria.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUCategoriaRepository.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php'; // para obtener atletas

class RepositoryCategoria implements ICategoriaRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // ──────────── CREATE ────────────
    public function create(Categoria $categoria): bool
    {
        $sql = "INSERT INTO categoria (sub_categoria) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$categoria->getSubCategoria()]);
    }

    // ──────────── UPDATE ────────────
    public function update(Categoria $categoria): bool
    {
        $sql = "UPDATE categoria SET sub_categoria = ? WHERE categoria_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $categoria->getSubCategoria(),
            $categoria->getId()
        ]);
    }

    // ──────────── DELETE ────────────
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM categoria WHERE categoria_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ──────────── FIND BY ID ────────────
    public function findById(int $id): ?Categoria
    {
        $sql = "SELECT * FROM categoria WHERE categoria_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToCategoria($row) : null;
    }

    // ──────────── FIND BY SUB-CATEGORIA ────────────
    public function findBySubCategoria(string $subCategoria): ?Categoria
    {
        $sql = "SELECT * FROM categoria WHERE sub_categoria = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$subCategoria]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToCategoria($row) : null;
    }

    // ──────────── FIND ALL ────────────
    public function findAll(): array
    {
        $sql = "SELECT * FROM categoria ORDER BY sub_categoria ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToCategorias($rows);
    }

    // ──────────── OBTENER ATLETAS DE UNA CATEGORÍA ────────────
    public function getAtletasByCategoria(int $categoriaId): array
    {
        // Reutilizamos el repositorio de Atleta
        $atletaRepo = new RepositoryAtleta();
        return $atletaRepo->findByCategoria($categoriaId);
    }

    // ──────────── PRIVADO: mapeo de un row a objeto Categoria ────────────
    private function mapRowToCategoria(array $row): Categoria
    {
        return new Categoria(
            (int) $row['categoria_id'],
            $row['sub_categoria']
        );
    }

    // ──────────── PRIVADO: mapeo de múltiples rows ────────────
    private function mapRowsToCategorias(array $rows): array
    {
        $categorias = [];
        foreach ($rows as $row) {
            $categorias[] = $this->mapRowToCategoria($row);
        }
        return $categorias;
    }
}