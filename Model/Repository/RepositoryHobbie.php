<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Hobbie.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUHobbieRepository.php';

class RepositoryHobbie implements IHobbieRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Hobbie $hobbie): bool
    {
        $sql = "INSERT INTO hobbies (nombre_hobbie) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$hobbie->getNombre()]);
    }

    public function update(Hobbie $hobbie): bool
    {
        $sql = "UPDATE hobbies SET nombre_hobbie = ? WHERE hobbie_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $hobbie->getNombre(),
            $hobbie->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM hobbies WHERE hobbie_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Hobbie
    {
        $sql = "SELECT * FROM hobbies WHERE hobbie_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToHobbie($row) : null;
    }

    public function findByNombre(string $nombre): ?Hobbie
    {
        $sql = "SELECT * FROM hobbies WHERE nombre_hobbie = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToHobbie($row) : null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM hobbies ORDER BY nombre_hobbie ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToHobbies($rows);
    }

    private function mapRowToHobbie(array $row): Hobbie
    {
        return new Hobbie(
            (int) $row['hobbie_id'],
            $row['nombre_hobbie']
        );
    }

    private function mapRowsToHobbies(array $rows): array
    {
        $hobbies = [];
        foreach ($rows as $row) {
            $hobbies[] = $this->mapRowToHobbie($row);
        }
        return $hobbies;
    }
}