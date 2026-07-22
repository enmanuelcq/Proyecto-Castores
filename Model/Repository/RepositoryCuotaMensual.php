<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/CuotaMensual.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUCuotaMensualRepository.php';

class RepositoryCuotaMensual implements ICuotaMensualRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(CuotaMensual $cuota): bool
    {
        $sql = "INSERT INTO cuota_mensual (Atleta_id, mes, año, monto_establecido, estatus_cuota) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $cuota->getAtletaId(),
            $cuota->getMes(),
            $cuota->getAnio(),
            $cuota->getMontoEstablecido(),
            $cuota->getEstatusCuota()
        ]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $cuota->setId($id);
        }
        return $result;
    }

    public function update(CuotaMensual $cuota): bool
    {
        $sql = "UPDATE cuota_mensual SET 
                    Atleta_id = ?,
                    mes = ?,
                    año = ?,
                    monto_establecido = ?,
                    estatus_cuota = ?
                WHERE cuota_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $cuota->getAtletaId(),
            $cuota->getMes(),
            $cuota->getAnio(),
            $cuota->getMontoEstablecido(),
            $cuota->getEstatusCuota(),
            $cuota->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM cuota_mensual WHERE cuota_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?CuotaMensual
    {
        $sql = "SELECT * FROM cuota_mensual WHERE cuota_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToCuota($row) : null;
    }

    public function findByAtleta(string $atletaId): array
    {
        $sql = "SELECT * FROM cuota_mensual WHERE Atleta_id = ? ORDER BY año DESC, mes DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToCuotas($rows);
    }

    public function findByAtletaAndYear(string $atletaId, int $anio): array
    {
        $sql = "SELECT * FROM cuota_mensual WHERE Atleta_id = ? AND año = ? ORDER BY mes DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId, $anio]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToCuotas($rows);
    }

    public function updateEstado(int $cuotaId, string $estado): bool
    {
        $sql = "UPDATE cuota_mensual SET estatus_cuota = ? WHERE cuota_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$estado, $cuotaId]);
    }

    private function mapRowToCuota(array $row): CuotaMensual
    {
        return new CuotaMensual(
            (int) $row['cuota_id'],
            $row['Atleta_id'],
            (int) $row['mes'],
            (int) $row['año'],
            (float) $row['monto_establecido'],
            $row['estatus_cuota'],
            $row['fecha_creacion'] ?? null,
            $row['fecha_actualizacion'] ?? null
        );
    }

    public function countByStatus(string $estado): int
    {
        $sql = "SELECT COUNT(*) FROM cuota_mensual WHERE estatus_cuota = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$estado]);
        return (int) $stmt->fetchColumn();
    }

    private function mapRowsToCuotas(array $rows): array
    {
        $cuotas = [];
        foreach ($rows as $row) {
            $cuotas[] = $this->mapRowToCuota($row);
        }
        return $cuotas;
    }
}