<?php

$root = dirname(__DIR__,2);
require_once $root .'/Model/Entities/Atleta.php';
require_once $root .'/Config/Database.php';
require_once $root .'/Model/Interfaces/IUAtletaRepository.php';

class RepositoryAtleta implements IAtletaRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // ──────────── CREATE ────────────
    public function create(Atleta $atleta): bool
    {
        $sql = "INSERT INTO atleta (
                    Atleta_id,
                    `1er_nombre`,
                    `2do_nombre`,
                    `1er_apellido`,
                    `2do_apellido`,
                    fecha_nacimiento,
                    sexo,
                    cedula,
                    pie_dominante,
                    direccion,
                    foto,
                    Atleta_status,
                    Uniforme_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $atleta->getAtletaId(),
            $atleta->getPrimerNombre(),
            $atleta->getSegundoNombre(),
            $atleta->getPrimerApellido(),
            $atleta->getSegundoApellido(),
            $atleta->getFechaNacimiento(),
            $atleta->getSexo(),
            $atleta->getCedula(),
            $atleta->getPieDominante(),
            $atleta->getDireccion(),
            $atleta->getFoto(),
            (int) $atleta->getAtletaStatus(),   // bool → 0/1
            $atleta->getUniformeId()
        ]);
    }

    // ──────────── UPDATE ────────────
    public function update(Atleta $atleta): bool
    {
        $sql = "UPDATE atleta SET
                    `1er_nombre` = ?,
                    `2do_nombre` = ?,
                    `1er_apellido` = ?,
                    `2do_apellido` = ?,
                    fecha_nacimiento = ?,
                    sexo = ?,
                    cedula = ?,
                    pie_dominante = ?,
                    direccion = ?,
                    foto = ?,
                    Atleta_status = ?,
                    Uniforme_id = ?
                WHERE Atleta_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $atleta->getPrimerNombre(),
            $atleta->getSegundoNombre(),
            $atleta->getPrimerApellido(),
            $atleta->getSegundoApellido(),
            $atleta->getFechaNacimiento(),
            $atleta->getSexo(),
            $atleta->getCedula(),
            $atleta->getPieDominante(),
            $atleta->getDireccion(),
            $atleta->getFoto(),
            (int) $atleta->getAtletaStatus(),
            $atleta->getUniformeId(),
            $atleta->getAtletaId()
        ]);
    }

    // ──────────── DELETE (físico) ────────────
    public function delete(string $id): bool
    {
        $sql = "DELETE FROM atleta WHERE Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ──────────── ACTIVAR / DESACTIVAR ────────────
    public function deactivate(string $id): bool
    {
        $sql = "UPDATE atleta SET Atleta_status = 0 WHERE Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function activate(string $id): bool
    {
        $sql = "UPDATE atleta SET Atleta_status = 1 WHERE Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ──────────── FIND BY ID ────────────
    public function findById(string $id): ?Atleta
    {
        $sql = "SELECT * FROM atleta WHERE Atleta_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToAtleta($row) : null;
    }

    // ──────────── FIND BY CÉDULA ────────────
    public function findByCedula(string $cedula): ?Atleta
    {
        $sql = "SELECT * FROM atleta WHERE cedula = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cedula]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToAtleta($row) : null;
    }

    // ──────────── FIND ALL ────────────
    public function findAll(): array
    {
        $sql = "SELECT * FROM atleta";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletas($rows);
    }

    public function findAllActive(): array
    {
        $sql = "SELECT * FROM atleta WHERE Atleta_status = 1";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletas($rows);
    }

    public function findAllInactive(): array
    {
        $sql = "SELECT * FROM atleta WHERE Atleta_status = 0";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletas($rows);
    }

    public function findAllWithFilters(?bool $status = true, string $busqueda = ''): array
    {
        $sql = "SELECT * FROM atleta WHERE 1=1";
        $params = [];

        if ($status !== null) {
            $sql .= " AND Atleta_status = ?";
            $params[] = (int)$status;
        }

        if (!empty($busqueda)) {
            $sql .= " AND (1er_nombre LIKE ? OR 2do_nombre LIKE ? OR 1er_apellido LIKE ? OR 2do_apellido LIKE ?)";
            $like = '%' . $busqueda . '%';
            $params = array_merge($params, [$like, $like, $like, $like]);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletas($rows);
    }

    // ──────────── FIND BY NOMBRE (coincidencia parcial en primer o segundo nombre) ────────────
    public function findByNombre(string $nombre): array
    {
        $sql = "SELECT * FROM atleta 
                WHERE `1er_nombre` LIKE ? OR `2do_nombre` LIKE ?";
        $stmt = $this->db->prepare($sql);
        $like = '%' . $nombre . '%';
        $stmt->execute([$like, $like]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletas($rows);
    }

    // ──────────── FIND BY APELLIDO (coincidencia parcial) ────────────
    public function findByApellido(string $apellido): array
    {
        $sql = "SELECT * FROM atleta 
                WHERE `1er_apellido` LIKE ? OR `2do_apellido` LIKE ?";
        $stmt = $this->db->prepare($sql);
        $like = '%' . $apellido . '%';
        $stmt->execute([$like, $like]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletas($rows);
    }

    // ──────────── FIND BY CATEGORÍA (usa tabla intermedia categoria_atleta) ────────────
    public function findByCategoria(int $categoriaId): array
    {
        $sql = "SELECT a.* 
                FROM atleta a
                JOIN categoria_atleta ca ON a.Atleta_id = ca.Atleta_id
                WHERE ca.categoria_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoriaId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletas($rows);
    }

    // ──────────── FIND BY MÚLTIPLES CATEGORÍAS ────────────
    public function findByCategorias(array $categoriaIds, int $primaryCategoriaId = 0): array
    {
        if (empty($categoriaIds)) return [];
        $placeholders = implode(',', array_fill(0, count($categoriaIds), '?'));
        $sql = "SELECT DISTINCT a.*, ca.categoria_id AS cat_asignada
                FROM atleta a
                JOIN categoria_atleta ca ON a.Atleta_id = ca.Atleta_id
                WHERE ca.categoria_id IN ($placeholders)
                ORDER BY CASE WHEN ca.categoria_id = ? THEN 0 ELSE 1 END,
                         a.`1er_apellido`, a.`1er_nombre`";
        $params = array_values($categoriaIds);
        $params[] = $primaryCategoriaId;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletas($rows);
    }

    // ──────────── OBTENER CATEGORÍA DE UN ATLETA ────────────
    public function getCategoriaAtleta(string $atletaId): ?string
    {
        $sql = "SELECT c.sub_categoria 
                FROM categoria_atleta ca
                JOIN categoria c ON ca.categoria_id = c.categoria_id
                WHERE ca.Atleta_id = ?
                ORDER BY ca.anio_id DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        return $stmt->fetchColumn() ?: null;
    }

    // ──────────── OBTENER MAPA ATLETA_ID => CATEGORÍA ────────────
    public function mapaCategorias(array $atletaIds): array
    {
        if (empty($atletaIds)) return [];
        $placeholders = implode(',', array_fill(0, count($atletaIds), '?'));
        $sql = "SELECT ca.Atleta_id, c.sub_categoria
                FROM categoria_atleta ca
                JOIN categoria c ON ca.categoria_id = c.categoria_id
                WHERE ca.Atleta_id IN ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($atletaIds);
        $mapa = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $mapa[$row['Atleta_id']] = $row['sub_categoria'];
        }
        return $mapa;
    }

    // ──────────── FIND BY REPRESENTANTE (usa tabla atleta_representante) ────────────
    public function findByRepresentante(string $cedulaRepresentante): array
    {
        $sql = "SELECT a.* 
                FROM atleta a
                JOIN atleta_representante ar ON a.Atleta_id = ar.Atleta_id
                WHERE ar.cedula_representante = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cedulaRepresentante]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletas($rows);
    }

    // ──────────── FIND BY STATUS ────────────
    public function findByStatus(bool $status): array
    {
        $sql = "SELECT * FROM atleta WHERE Atleta_status = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([(int) $status]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletas($rows);
    }

    // ──────────── PRIVADO: mapeo de un row a objeto Atleta ────────────
    private function mapRowToAtleta(array $row): Atleta
    {
        return new Atleta(
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
            $row['foto'],
            (bool) $row['Atleta_status'],
            (int) $row['Uniforme_id']
        );
    }

    // ──────────── PRIVADO: mapeo de múltiples rows ────────────
    private function mapRowsToAtletas(array $rows): array
    {
        $atletas = [];
        foreach ($rows as $row) {
            $atletas[] = $this->mapRowToAtleta($row);
        }
        return $atletas;
    }
}