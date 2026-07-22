<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Operacion.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUOperacionRepository.php';

class RepositoryOperacion implements IOperacionRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Operacion $operacion): bool
    {
        $sql = "INSERT INTO operacion (monto, pago_id, tipo_pago_id, referencia, banco) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $operacion->getMonto(),
            $operacion->getPagoId(),
            $operacion->getTipoPagoId(),
            $operacion->getReferencia(),
            $operacion->getBanco()
        ]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            if ($id > 0) {
                $operacion->setId($id);
            }
        }
        return $result;
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM operacion WHERE operacion_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findByPago(int $pagoId): array
    {
        $sql = "SELECT * FROM operacion WHERE pago_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pagoId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToOperaciones($rows);
    }

    public function existsReferencia(int $tipoPagoId, string $banco, string $referencia): bool
    {
        $sql = "SELECT COUNT(*) FROM operacion 
                WHERE tipo_pago_id = ? AND banco = ? AND referencia = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tipoPagoId, $banco, $referencia]);
        return (int) $stmt->fetchColumn() > 0;
    }

    private function mapRowToOperacion(array $row): Operacion
    {
        return new Operacion(
            (int) $row['operacion_id'],
            (float) $row['monto'],
            (int) $row['pago_id'],
            (int) $row['tipo_pago_id'],
            $row['referencia'] ?? null,
            $row['fecha_operacion'] ?? null
        );
    }

    private function mapRowsToOperaciones(array $rows): array
    {
        $operaciones = [];
        foreach ($rows as $row) {
            $operaciones[] = $this->mapRowToOperacion($row);
        }
        return $operaciones;
    }
}