<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/AtletaRepresentante.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUAtletaRepresentanteRepository.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';
require_once $root . '/Model/Repository/RepositoryRepresentante.php';

class RepositoryAtletaRepresentante implements IAtletaRepresentanteRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // ──────────── CREATE ────────────
    public function create(AtletaRepresentante $relacion): bool
    {
        $sql = "INSERT INTO atleta_representante (
                    parentesco,
                    fecha_inscripcion,
                    Atleta_id,
                    cedula_representante
                ) VALUES (?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $relacion->getParentesco(),
            $relacion->getFechaInscripcion(),
            $relacion->getAtletaId(),
            $relacion->getCedulaRepresentante()
        ]);
    }

    // ──────────── UPDATE ────────────
    public function update(AtletaRepresentante $relacion): bool
    {
        $sql = "UPDATE atleta_representante SET
                    parentesco = ?,
                    fecha_inscripcion = ?,
                    Atleta_id = ?,
                    cedula_representante = ?
                WHERE AR_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $relacion->getParentesco(),
            $relacion->getFechaInscripcion(),
            $relacion->getAtletaId(),
            $relacion->getCedulaRepresentante(),
            $relacion->getId()
        ]);
    }

    // ──────────── DELETE por ID ────────────
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM atleta_representante WHERE AR_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    // ──────────── DELETE por Atleta y Representante ────────────
    public function deleteByAtletaAndRepresentante(string $atletaId, string $cedulaRepresentante): bool
    {
        $sql = "DELETE FROM atleta_representante 
                WHERE Atleta_id = ? AND cedula_representante = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$atletaId, $cedulaRepresentante]);
    }

    // ──────────── FIND BY ID ────────────
    public function findById(int $id): ?AtletaRepresentante
    {
        $sql = "SELECT * FROM atleta_representante WHERE AR_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToAtletaRepresentante($row) : null;
    }

    // ──────────── FIND BY ATLETA ────────────
    public function findByAtleta(string $atletaId): array
    {
        $sql = "SELECT * FROM atleta_representante WHERE Atleta_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletaRepresentantes($rows);
    }

    // ──────────── FIND BY REPRESENTANTE ────────────
    public function findByRepresentante(string $cedulaRepresentante): array
    {
        $sql = "SELECT * FROM atleta_representante WHERE cedula_representante = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cedulaRepresentante]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToAtletaRepresentantes($rows);
    }

    // ──────────── OBTENER ATLETAS DE UN REPRESENTANTE ────────────
    public function getAtletasByRepresentante(string $cedulaRepresentante): array
    {
        // Reutilizamos el repositorio de Atleta
        $atletaRepo = new RepositoryAtleta();
        return $atletaRepo->findByRepresentante($cedulaRepresentante);
    }

    // ──────────── OBTENER REPRESENTANTES DE UN ATLETA ────────────
    public function getRepresentantesByAtleta(string $atletaId): array
    {
        // Obtenemos las relaciones
        $relaciones = $this->findByAtleta($atletaId);
        $representantes = [];
        $repoRepresentante = new RepositoryRepresentante();

        foreach ($relaciones as $relacion) {
            $cedula = $relacion->getCedulaRepresentante();
            $representante = $repoRepresentante->findByCedula($cedula);
            if ($representante) {
                $representantes[] = $representante;
            }
        }
        return $representantes;
    }

    // ──────────── VERIFICAR EXISTENCIA ────────────
    public function exists(string $atletaId, string $cedulaRepresentante): bool
    {
        $sql = "SELECT COUNT(*) FROM atleta_representante 
                WHERE Atleta_id = ? AND cedula_representante = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId, $cedulaRepresentante]);
        return (int) $stmt->fetchColumn() > 0;
    }

    // ──────────── PRIVADO: mapeo de un row a objeto AtletaRepresentante ────────────
    private function mapRowToAtletaRepresentante(array $row): AtletaRepresentante
    {
        return new AtletaRepresentante(
            (int) $row['AR_id'],
            $row['parentesco'],
            $row['fecha_inscripcion'],
            $row['Atleta_id'],
            $row['cedula_representante']
        );
    }

    // ──────────── PRIVADO: mapeo de múltiples rows ────────────
    private function mapRowsToAtletaRepresentantes(array $rows): array
    {
        $relaciones = [];
        foreach ($rows as $row) {
            $relaciones[] = $this->mapRowToAtletaRepresentante($row);
        }
        return $relaciones;
    }
}