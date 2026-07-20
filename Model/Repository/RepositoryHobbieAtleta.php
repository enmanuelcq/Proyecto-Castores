<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/HobbieAtleta.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUHobbieAtletaRepository.php';

class RepositoryHobbieAtleta implements IHobbieAtletaRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(HobbieAtleta $relacion): bool
    {
        $sql = "INSERT INTO hobbie_atleta (horario_hobbie, Atleta_id, hobbie_id) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $relacion->getHorario(),
            $relacion->getAtletaId(),
            $relacion->getHobbieId()
        ]);
    }

    public function update(HobbieAtleta $relacion): bool
    {
        $sql = "UPDATE hobbie_atleta SET horario_hobbie = ?, Atleta_id = ?, hobbie_id = ? WHERE hobbie_atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $relacion->getHorario(),
            $relacion->getAtletaId(),
            $relacion->getHobbieId(),
            $relacion->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM hobbie_atleta WHERE hobbie_atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function deleteByAtletaAndHobbie(string $atletaId, int $hobbieId): bool
    {
        $sql = "DELETE FROM hobbie_atleta WHERE Atleta_id = ? AND hobbie_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$atletaId, $hobbieId]);
    }

    public function findById(int $id): ?HobbieAtleta
    {
        $sql = "SELECT * FROM hobbie_atleta WHERE hobbie_atleta_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToHobbieAtleta($row) : null;
    }

    public function findByAtleta(string $atletaId): array
    {
        $sql = "SELECT * FROM hobbie_atleta WHERE Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToHobbieAtleta($rows);
    }

    public function findByHobbie(int $hobbieId): array
    {
        $sql = "SELECT * FROM hobbie_atleta WHERE hobbie_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$hobbieId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToHobbieAtleta($rows);
    }

    public function exists(string $atletaId, int $hobbieId): bool
    {
        $sql = "SELECT COUNT(*) FROM hobbie_atleta WHERE Atleta_id = ? AND hobbie_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId, $hobbieId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    private function mapRowToHobbieAtleta(array $row): HobbieAtleta
    {
        return new HobbieAtleta(
            (int) $row['hobbie_atleta_id'],
            $row['horario_hobbie'],
            $row['Atleta_id'],
            (int) $row['hobbie_id']
        );
    }

    private function mapRowsToHobbieAtleta(array $rows): array
    {
        $relaciones = [];
        foreach ($rows as $row) {
            $relaciones[] = $this->mapRowToHobbieAtleta($row);
        }
        return $relaciones;
    }
}