<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/TipoPago.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUTipoPagoRepository.php';

class RepositoryTipoPago implements ITipoPagoRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(TipoPago $tipoPago): bool
    {
        $sql = "INSERT INTO tipo_pago (tipo_pago) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$tipoPago->getTipoPago()]);
        if ($result) {
            $id = (int) $this->db->lastInsertId();
            $tipoPago->setId($id);
        }
        return $result;
    }

    public function update(TipoPago $tipoPago): bool
    {
        $sql = "UPDATE tipo_pago SET tipo_pago = ? WHERE tipo_pago_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $tipoPago->getTipoPago(),
            $tipoPago->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM tipo_pago WHERE tipo_pago_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?TipoPago
    {
        $sql = "SELECT * FROM tipo_pago WHERE tipo_pago_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToTipoPago($row) : null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM tipo_pago ORDER BY tipo_pago ASC";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $this->mapRowsToTipoPagos($rows);
    }

    public function findByTipo(string $tipo): ?TipoPago
    {
        $sql = "SELECT * FROM tipo_pago WHERE tipo_pago = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tipo]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToTipoPago($row) : null;
    }

    private function mapRowToTipoPago(array $row): TipoPago
    {
        return new TipoPago(
            (int) $row['tipo_pago_id'],
            $row['tipo_pago']
        );
    }

    private function mapRowsToTipoPagos(array $rows): array
    {
        $tipos = [];
        foreach ($rows as $row) {
            $tipos[] = $this->mapRowToTipoPago($row);
        }
        return $tipos;
    }
}