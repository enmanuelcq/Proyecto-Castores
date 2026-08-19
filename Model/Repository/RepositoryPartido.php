<?php
// Model/Repository/RepositoryPartido.php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Partido.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUPartidoRepository.php';

class RepositoryPartido implements IPartidoRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Partido $partido): bool
    {
        $sql = "INSERT INTO partido (fecha_partido, duracion, estado, tipo_partido, nombre_competencia, goles_favor, goles_contra, categoria_id, equipo_id, campo_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $partido->getFechaPartido(),
            $partido->getDuracion(),
            $partido->getEstado(),
            $partido->getTipoPartido(),
            $partido->getNombreCompetencia(),
            $partido->getGolesFavor(),
            $partido->getGolesContra(),
            $partido->getCategoriaId(),
            $partido->getEquipoId(),
            $partido->getCampoId()
        ]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $partido->setId($id);
        }
        return $result;
    }

    public function update(Partido $partido): bool
    {
        $sql = "UPDATE partido SET 
                    fecha_partido = ?,
                    duracion = ?,
                    estado = ?,
                    tipo_partido = ?,
                    nombre_competencia = ?,
                    goles_favor = ?,
                    goles_contra = ?,
                    categoria_id = ?,
                    equipo_id = ?,
                    campo_id = ?
                WHERE partido_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $partido->getFechaPartido(),
            $partido->getDuracion(),
            $partido->getEstado(),
            $partido->getTipoPartido(),
            $partido->getNombreCompetencia(),
            $partido->getGolesFavor(),
            $partido->getGolesContra(),
            $partido->getCategoriaId(),
            $partido->getEquipoId(),
            $partido->getCampoId(),
            $partido->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM partido WHERE partido_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Partido
    {
        $sql = "SELECT * FROM partido WHERE partido_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToPartido($row) : null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM partido ORDER BY fecha_partido DESC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToPartidos($rows);
    }

    public function findAllWithDetails(?int $limit = null, ?int $offset = null): array
{
    $sql = "SELECT 
                p.*,
                c.sub_categoria AS categoria_nombre,
                er.nombre_equipo AS equipo_rival,
                ca.nombre_campo AS campo_nombre,
                ca.mapa_campo AS mapa_campo
            FROM partido p
            LEFT JOIN categoria c ON p.categoria_id = c.categoria_id
            LEFT JOIN equipo_rival er ON p.equipo_id = er.equipo_id
            LEFT JOIN campo ca ON p.campo_id = ca.campo_id
            ORDER BY p.fecha_partido DESC";

    if ($limit !== null) {
        $sql .= " LIMIT :limit";
        if ($offset !== null) {
            $sql .= " OFFSET :offset";
        }
    }

    $stmt = $this->db->prepare($sql);

    if ($limit !== null) {
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        if ($offset !== null) {
            $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        }
    }

    $stmt->execute();
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

    public function findByCategoria(int $categoriaId): array
    {
        $sql = "SELECT * FROM partido WHERE categoria_id = ? ORDER BY fecha_partido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoriaId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findByEstado(string $estado): array
    {
        $sql = "SELECT * FROM partido WHERE estado = ? ORDER BY fecha_partido DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$estado]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function countAll(): int
    {
        $sql = "SELECT COUNT(*) FROM partido";
        $stmt = $this->db->query($sql);
        return (int) $stmt->fetchColumn();
    }

    public function getGoleadoresByPartido(int $partidoId): array
    {
        $sql = "SELECT g.*, a.1er_nombre, a.1er_apellido 
                FROM goles g
                LEFT JOIN atleta a ON g.Atleta_id = a.Atleta_id
                WHERE g.partido_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$partidoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTarjetasByPartido(int $partidoId): array
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

    public function getCambiosByPartido(int $partidoId): array
    {
        $sql = "SELECT c.*, 
                    a1.1er_nombre AS entra_nombre, a1.1er_apellido AS entra_apellido,
                    a2.1er_nombre AS sale_nombre, a2.1er_apellido AS sale_apellido
                FROM cambios c
                LEFT JOIN atleta a1 ON c.Atleta_id = a1.Atleta_id
                LEFT JOIN atleta a2 ON c.Atleta_sale_id = a2.Atleta_id
                WHERE c.partido_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$partidoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getIncidenciasByPartido(int $partidoId): array
    {
        $sql = "SELECT i.*, a.1er_nombre, a.1er_apellido 
                FROM incidencia i
                LEFT JOIN atleta a ON i.Atleta_id = a.Atleta_id
                WHERE i.partido_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$partidoId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function mapRowToPartido(array $row): Partido
    {
        return new Partido(
            (int) $row['partido_id'],
            $row['fecha_partido'],
            $row['duracion'],
            $row['estado'] ?? 'Pendiente',
            (int) $row['goles_favor'],
            (int) $row['goles_contra'],
            (int) $row['categoria_id'],
            (int) $row['equipo_id'],
            (int) $row['campo_id']
        );
    }

    private function mapRowsToPartidos(array $rows): array
    {
        $partidos = [];
        foreach ($rows as $row) {
            $partidos[] = $this->mapRowToPartido($row);
        }
        return $partidos;
    }
}