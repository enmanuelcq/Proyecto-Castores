<?php
// Model/Repository/RepositoryConfiguracion.php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Configuracion.php';
require_once $root . '/Config/Database.php';

class RepositoryConfiguracion
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function getValor(string $clave): ?string
    {
        $sql = "SELECT valor FROM configuracion WHERE clave = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$clave]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $row['valor'] : null;
    }

    public function setValor(string $clave, string $valor): bool
    {
        $sql = "INSERT INTO configuracion (clave, valor) VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE valor = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$clave, $valor, $valor]);
    }

    public function getMontoMensualUSD(): float
    {
        return (float)($this->getValor('monto_mensual_usd') ?? 50.00);
    }

    public function getTasaBCV(): float
    {
        return (float)($this->getValor('tasa_dolar_bcv') ?? 0);
    }

    public function setTasaBCV(float $tasa): bool
    {
        $this->setValor('tasa_dolar_bcv', (string)$tasa);
        $this->setValor('ultima_actualizacion_tasa', date('Y-m-d H:i:s'));
        return true;
    }

    public function getMontoMensualBs(): float
    {
        return $this->getMontoMensualUSD() * $this->getTasaBCV();
    }
}