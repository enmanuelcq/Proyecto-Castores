<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Entities/Atleta.php';

class RepositoryConvocado
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function findByPartido(int $partidoId): array
    {
        $sql = "SELECT a.* 
                FROM atleta a
                JOIN convocado c ON a.Atleta_id = c.Atleta_id
                WHERE c.partido_id = ?
                ORDER BY a.`1er_apellido`, a.`1er_nombre`";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$partidoId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRows($rows);
    }

    public function findIdsByPartido(int $partidoId): array
    {
        $sql = "SELECT Atleta_id FROM convocado WHERE partido_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$partidoId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function countByPartido(int $partidoId): int
    {
        $sql = "SELECT COUNT(*) FROM convocado WHERE partido_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$partidoId]);
        return (int) $stmt->fetchColumn();
    }

    public function convocar(int $partidoId, string $atletaId): bool
    {
        $sql = "INSERT IGNORE INTO convocado (partido_id, Atleta_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$partidoId, $atletaId]);
    }

    public function desconvocar(int $partidoId, string $atletaId): bool
    {
        $sql = "DELETE FROM convocado WHERE partido_id = ? AND Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$partidoId, $atletaId]);
    }

    public function deleteByPartido(int $partidoId): bool
    {
        $sql = "DELETE FROM convocado WHERE partido_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$partidoId]);
    }

    public function esConvocado(int $partidoId, string $atletaId): bool
    {
        $sql = "SELECT COUNT(*) FROM convocado WHERE partido_id = ? AND Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$partidoId, $atletaId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    public function findByAtleta(string $atletaId): array
    {
        $sql = "SELECT p.*, er.nombre_equipo AS equipo_rival, c.minutos_jugados,
                       cat.sub_categoria AS categoria_nombre
                FROM partido p
                JOIN convocado c ON p.partido_id = c.partido_id
                LEFT JOIN equipo_rival er ON p.equipo_id = er.equipo_id
                LEFT JOIN categoria cat ON p.categoria_id = cat.categoria_id
                WHERE c.Atleta_id = ?
                ORDER BY p.fecha_partido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countByAtleta(string $atletaId): int
    {
        $sql = "SELECT COUNT(*) FROM convocado WHERE Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        return (int) $stmt->fetchColumn();
    }

    private function mapRows(array $rows): array
    {
        $atletas = [];
        foreach ($rows as $row) {
            $atletas[] = new Atleta(
                $row['Atleta_id'],
                $row['1er_nombre'],
                $row['2do_nombre'],
                $row['1er_apellido'],
                $row['2do_apellido'],
                $row['fecha_nacimiento'],
                $row['sexo'],
                $row['cedula'],
                $row['pie_dominante'],
                $row['direccion'],
                $row['foto'] ?? '',
                $row['documento_cedula'] ?? '',
                $row['documento_partida'] ?? '',
                (bool) $row['Atleta_status'],
                (int) $row['Uniforme_id']
            );
        }
        return $atletas;
    }
}
