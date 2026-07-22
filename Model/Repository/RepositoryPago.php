<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Pago.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUPagoRepository.php';

class RepositoryPago implements IPagoRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Pago $pago): bool
    {
        $sql = "INSERT INTO pagos (total_pago, concepto_pago, cedula_representante, fecha_pago, estatus_pago) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $pago->getTotalPago(),
            $pago->getConceptoPago(),
            $pago->getCedulaRepresentante(),
            $pago->getFechaPago() ?? date('Y-m-d H:i:s'),
            $pago->getEstatusPago()
        ]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $pago->setId($id);
        }
        return $result;
    }

    public function update(Pago $pago): bool
    {
        $sql = "UPDATE pagos SET 
                    total_pago = ?,
                    concepto_pago = ?,
                    cedula_representante = ?,
                    fecha_pago = ?,
                    estatus_pago = ?
                WHERE pago_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $pago->getTotalPago(),
            $pago->getConceptoPago(),
            $pago->getCedulaRepresentante(),
            $pago->getFechaPago(),
            $pago->getEstatusPago(),
            $pago->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM pagos WHERE pago_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Pago
    {
        $sql = "SELECT * FROM pagos WHERE pago_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToPago($row) : null;
    }

    public function findByRepresentante(string $cedulaRepresentante): array
    {
        $sql = "SELECT * FROM pagos WHERE cedula_representante = ? ORDER BY fecha_pago DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cedulaRepresentante]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToPagos($rows);
    }

    public function findByAtleta(string $atletaId): array
    {
        // Primero obtenemos las cuotas del atleta, luego los pagos asociados
        $sql = "SELECT DISTINCT p.* 
                FROM pagos p
                JOIN pago_cuota pc ON p.pago_id = pc.pago_id
                JOIN cuota_mensual c ON pc.cuota_id = c.cuota_id
                WHERE c.Atleta_id = ?
                ORDER BY p.fecha_pago DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToPagos($rows);
    }

    private function mapRowToPago(array $row): Pago
    {
        return new Pago(
            (int) $row['pago_id'],
            (float) $row['total_pago'],
            $row['concepto_pago'],
            $row['cedula_representante'],
            $row['fecha_pago'],
            $row['estatus_pago']
        );
    }

    private function mapRowsToPagos(array $rows): array
    {
        $pagos = [];
        foreach ($rows as $row) {
            $pagos[] = $this->mapRowToPago($row);
        }
        return $pagos;
    }
}