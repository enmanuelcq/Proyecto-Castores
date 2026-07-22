<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/PagoCuota.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUPagoCuotaRepository.php';

class RepositoryPagoCuota implements IPagoCuotaRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(PagoCuota $pagoCuota): bool
    {
        $sql = "INSERT INTO pago_cuota (pago_id, cuota_id, monto_abonado) 
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $pagoCuota->getPagoId(),
            $pagoCuota->getCuotaId(),
            $pagoCuota->getMontoAbonado()
        ]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $pagoCuota->setId($id);
        }
        return $result;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM pago_cuota WHERE pago_cuota_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findByPago(int $pagoId): array
    {
        $sql = "SELECT * FROM pago_cuota WHERE pago_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pagoId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToPagoCuotas($rows);
    }

    public function findByCuota(int $cuotaId): array
    {
        $sql = "SELECT * FROM pago_cuota WHERE cuota_id = ? ORDER BY fecha_asignacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cuotaId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToPagoCuotas($rows);
    }

    public function sumAbonadoByCuota(int $cuotaId): float
    {
        $sql = "SELECT COALESCE(SUM(monto_abonado), 0) as total FROM pago_cuota WHERE cuota_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cuotaId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $total = (float) ($row['total'] ?? 0);
        error_log("sumAbonadoByCuota($cuotaId) = $total");
        return $total;
    }

    private function mapRowToPagoCuota(array $row): PagoCuota
    {
        return new PagoCuota(
            (int) $row['pago_cuota_id'],
            (int) $row['pago_id'],
            (int) $row['cuota_id'],
            (float) $row['monto_abonado'],
            $row['fecha_asignacion'] ?? null
        );
    }

    private function mapRowsToPagoCuotas(array $rows): array
    {
        $pagoCuotas = [];
        foreach ($rows as $row) {
            $pagoCuotas[] = $this->mapRowToPagoCuota($row);
        }
        return $pagoCuotas;
    }
}