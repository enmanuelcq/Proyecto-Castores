<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Colegio.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUColegioRepository.php';

class RepositoryColegio implements IColegioRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Colegio $colegio): bool
    {
        $sql = "INSERT INTO colegio (nombre_colegio, direccion_colegio) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$colegio->getNombre(), $colegio->getDireccion()]);
    }

    public function update(Colegio $colegio): bool
    {
        $sql = "UPDATE colegio SET nombre_colegio = ?, direccion_colegio = ? WHERE colegio_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $colegio->getNombre(),
            $colegio->getDireccion(),
            $colegio->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM colegio WHERE colegio_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Colegio
    {
        $sql = "SELECT * FROM colegio WHERE colegio_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToColegio($row) : null;
    }

    public function findByNombre(string $nombre): ?Colegio
    {
        $sql = "SELECT * FROM colegio WHERE nombre_colegio = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToColegio($row) : null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM colegio ORDER BY nombre_colegio ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToColegios($rows);
    }

    private function mapRowToColegio(array $row): Colegio
    {
        return new Colegio(
            (int) $row['colegio_id'],
            $row['nombre_colegio'],
            $row['direccion_colegio']
        );
    }

    private function mapRowsToColegios(array $rows): array
    {
        $colegios = [];
        foreach ($rows as $row) {
            $colegios[] = $this->mapRowToColegio($row);
        }
        return $colegios;
    }
}