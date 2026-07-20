<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Grado.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUGradoRepository.php';

class RepositoryGrado implements IGradoRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Grado $grado): bool
    {
        $sql = "INSERT INTO grado (grado, Atleta_id, colegio_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $grado->getGrado(),
            $grado->getAtletaId(),
            $grado->getColegioId()
        ]);
    }

    public function update(Grado $grado): bool
    {
        $sql = "UPDATE grado SET grado = ?, Atleta_id = ?, colegio_id = ? WHERE grado_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $grado->getGrado(),
            $grado->getAtletaId(),
            $grado->getColegioId(),
            $grado->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM grado WHERE grado_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function deleteByAtleta(string $atletaId): bool
    {
        $sql = "DELETE FROM grado WHERE Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$atletaId]);
    }

    public function findById(int $id): ?Grado
    {
        $sql = "SELECT * FROM grado WHERE grado_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToGrado($row) : null;
    }

    public function findByAtleta(string $atletaId): array
    {
        $sql = "SELECT * FROM grado WHERE Atleta_id = ? ORDER BY grado_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToGrados($rows);
    }

    public function findByColegio(int $colegioId): array
    {
        $sql = "SELECT * FROM grado WHERE colegio_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$colegioId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToGrados($rows);
    }

    public function findCurrentByAtleta(string $atletaId): ?Grado
    {
        // Asumimos que el último insertado es el actual (puedes usar fecha de registro si existiera)
        $sql = "SELECT * FROM grado WHERE Atleta_id = ? ORDER BY grado_id DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToGrado($row) : null;
    }

    private function mapRowToGrado(array $row): Grado
    {
        return new Grado(
            (int) $row['grado_id'],
            $row['grado'],
            $row['Atleta_id'],
            (int) $row['colegio_id']
        );
    }

    private function mapRowsToGrados(array $rows): array
    {
        $grados = [];
        foreach ($rows as $row) {
            $grados[] = $this->mapRowToGrado($row);
        }
        return $grados;
    }
}