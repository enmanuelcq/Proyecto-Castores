<?php

require_once dirname(__DIR__, 2) . '/Model/Entities/DesempenioEntrenamiento.php';
require_once dirname(__DIR__, 2) . '/Config/Database.php';
require_once dirname(__DIR__, 2) . '/Model/Interfaces/IUDesempenioEntrenamientoRepository.php';

class RepositoryDesempenioEntrenamiento implements IUDesempenioEntrenamientoRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(DesempenioEntrenamiento $d): bool
    {
        $sql = "INSERT INTO desempenio_entrenamiento (
                    asistencia, desempenio, observacion, Atleta_id, entrenamiento_id
                ) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $d->getAsistencia(),
            $d->getDesempenio(),
            $d->getObservacion(),
            $d->getAtletaId(),
            $d->getEntrenamientoId(),
        ]);
    }

    public function update(DesempenioEntrenamiento $d): bool
    {
        $sql = "UPDATE desempenio_entrenamiento SET
                    asistencia = ?,
                    desempenio = ?,
                    observacion = ?
                WHERE desempenio_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $d->getAsistencia(),
            $d->getDesempenio(),
            $d->getObservacion(),
            $d->getDesempenioId(),
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM desempenio_entrenamiento WHERE desempenio_id = ?");
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?DesempenioEntrenamiento
    {
        $stmt = $this->db->prepare("SELECT * FROM desempenio_entrenamiento WHERE desempenio_id = ? LIMIT 1");
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRow($row) : null;
    }

    public function findByEntrenamientoId(int $entrenamientoId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM desempenio_entrenamiento WHERE entrenamiento_id = ?");
        $stmt->execute([$entrenamientoId]);
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function findByAtletaId(string $atletaId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM desempenio_entrenamiento WHERE Atleta_id = ?");
        $stmt->execute([$atletaId]);
        return $this->mapRows($stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    public function findOrCreate(int $entrenamientoId, string $atletaId): ?DesempenioEntrenamiento
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM desempenio_entrenamiento 
             WHERE entrenamiento_id = ? AND Atleta_id = ? LIMIT 1"
        );
        $stmt->execute([$entrenamientoId, $atletaId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRow($row) : null;
    }

    public function countAsistentesByEntrenamiento(int $entrenamientoId): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM desempenio_entrenamiento 
             WHERE entrenamiento_id = ? AND asistencia = 1"
        );
        $stmt->execute([$entrenamientoId]);
        return (int) $stmt->fetchColumn();
    }

    public function upsert(int $entrenamientoId, string $atletaId, int $asistio, ?string $observacion): bool
    {
        $existing = $this->findOrCreate($entrenamientoId, $atletaId);

        if ($existing) {
            $existing->setAsistencia($asistio);
            $existing->setObservacion($observacion);
            return $this->update($existing);
        }

        $d = new DesempenioEntrenamiento(
            0,
            $asistio,
            '',
            $observacion,
            $atletaId,
            $entrenamientoId
        );
        return $this->create($d);
    }

    private function mapRow(array $row): DesempenioEntrenamiento
    {
        return new DesempenioEntrenamiento(
            (int) $row['desempenio_id'],
            (int) $row['asistencia'],
            $row['desempenio'] ?? '',
            $row['observacion'] ?? null,
            $row['Atleta_id'] ?? '',
            (int) $row['entrenamiento_id']
        );
    }

    private function mapRows(array $rows): array
    {
        $result = [];
        foreach ($rows as $row) {
            $result[] = $this->mapRow($row);
        }
        return $result;
    }
}
