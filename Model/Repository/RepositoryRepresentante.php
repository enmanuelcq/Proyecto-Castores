<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Representante.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IURepresentanteRepository.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php'; // para obtener atletas

class RepositoryRepresentante implements IRepresentanteRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // ──────────── CREATE ────────────
    public function create(Representante $representante): bool
    {
        $sql = "INSERT INTO representante (
                    cedula_representante,
                    nombre_representante,
                    apellido_representante,
                    correo_representante,
                    ocupacion_representante,
                    telefono_representante
                ) VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $representante->getCedula(),
            $representante->getNombre(),
            $representante->getApellido(),
            $representante->getCorreo(),
            $representante->getOcupacion(),
            $representante->getTelefono()
        ]);
    }

    // ──────────── UPDATE ────────────
    public function update(Representante $representante): bool
    {
        $sql = "UPDATE representante SET
                    nombre_representante = ?,
                    apellido_representante = ?,
                    correo_representante = ?,
                    ocupacion_representante = ?,
                    telefono_representante = ?
                WHERE cedula_representante = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $representante->getNombre(),
            $representante->getApellido(),
            $representante->getCorreo(),
            $representante->getOcupacion(),
            $representante->getTelefono(),
            $representante->getCedula()
        ]);
    }

    // ──────────── DELETE ────────────
    public function delete(string $cedula): bool
    {
        $sql = "DELETE FROM representante WHERE cedula_representante = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cedula]);
    }

    // ──────────── FIND BY CÉDULA ────────────
    public function findByCedula(string $cedula): ?Representante
    {
        // Normalizar la cédula buscada: mayúsculas y sin guiones
        $cedula_norm = strtoupper(str_replace('-', '', $cedula));
        
        // Buscar comparando solo dígitos (ignora prefijos y guiones)
        $stmt = $this->db->prepare(
            "SELECT * FROM representante 
             WHERE UPPER(REPLACE(cedula_representante, '-', '')) = ? 
             LIMIT 1"
        );
        $stmt->execute([$cedula_norm]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToRepresentante($row) : null;
    }

    // ──────────── FIND BY CORREO ────────────
    public function findByCorreo(string $correo): ?Representante
    {
        $sql = "SELECT * FROM representante WHERE correo_representante = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$correo]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToRepresentante($row) : null;
    }

    // ──────────── FIND BY TELÉFONO ────────────
    public function findByTelefono(string $telefono): ?Representante
    {
        $sql = "SELECT * FROM representante WHERE telefono_representante = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$telefono]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $row ? $this->mapRowToRepresentante($row) : null;
    }

    // ──────────── FIND ALL ────────────
    public function findAll(): array
    {
        $sql = "SELECT * FROM representante";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToRepresentantes($rows);
    }

    // ──────────── FIND ALL ACTIVE ────────────
    // Nota: la tabla representante no tiene campo de status en el diseño original.
    // Si agregas un campo 'activo' en la BD, puedes usar esta función.
    // Por ahora, lo dejamos como findAll() pero podrías adaptarlo.
    public function findAllActive(): array
    {
        // Si no existe campo status en la tabla, retornamos todos.
        // O podrías filtrar por aquellos que tengan al menos un atleta asociado.
        return $this->findAll();
    }

    // ──────────── FIND BY NOMBRE (coincidencia parcial) ────────────
    public function findByNombre(string $nombre): array
    {
        $sql = "SELECT * FROM representante WHERE nombre_representante LIKE ?";
        $stmt = $this->db->prepare($sql);
        $like = '%' . $nombre . '%';
        $stmt->execute([$like]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToRepresentantes($rows);
    }

    // ──────────── FIND BY APELLIDO (coincidencia parcial) ────────────
    public function findByApellido(string $apellido): array
    {
        $sql = "SELECT * FROM representante WHERE apellido_representante LIKE ?";
        $stmt = $this->db->prepare($sql);
        $like = '%' . $apellido . '%';
        $stmt->execute([$like]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToRepresentantes($rows);
    }

    // ──────────── FIND BY NOMBRE COMPLETO ────────────
    public function findByNombreCompleto(string $nombre, string $apellido): array
    {
        $sql = "SELECT * FROM representante 
                WHERE nombre_representante = ? AND apellido_representante = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nombre, $apellido]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToRepresentantes($rows);
    }

    // ──────────── OBTENER ATLETAS ASOCIADOS A UN REPRESENTANTE ────────────
    public function getAtletasByRepresentante(string $cedulaRepresentante): array
    {
        // Usamos el repositorio de Atleta
        $atletaRepo = new RepositoryAtleta();
        return $atletaRepo->findByRepresentante($cedulaRepresentante);
    }

    // ──────────── PRIVADO: mapeo de un row a objeto Representante ────────────
    private function mapRowToRepresentante(array $row): Representante
    {
        // Si no existe campo 'status' en la tabla, pasamos true por defecto.
        // Si decides agregarlo, ajusta aquí.
        return new Representante(
            $row['cedula_representante'],
            $row['nombre_representante'],
            $row['apellido_representante'],
            $row['correo_representante'],
            $row['ocupacion_representante'],
            $row['telefono_representante'],
            true // status por defecto (si no está en tabla)
        );
    }

    // ──────────── PRIVADO: mapeo de múltiples rows ────────────
    private function mapRowsToRepresentantes(array $rows): array
    {
        $representantes = [];
        foreach ($rows as $row) {
            $representantes[] = $this->mapRowToRepresentante($row);
        }
        return $representantes;
    }
}