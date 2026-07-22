<?php

class PagoCuota
{
    private ?int $id;
    private int $pagoId;
    private int $cuotaId;
    private float $montoAbonado;
    private ?string $fechaAsignacion;

    public function __construct(
        ?int $id,
        int $pagoId,
        int $cuotaId,
        float $montoAbonado,
        ?string $fechaAsignacion = null
    ) {
        $this->id = $id;
        $this->pagoId = $pagoId;
        $this->cuotaId = $cuotaId;
        $this->montoAbonado = $montoAbonado;
        $this->fechaAsignacion = $fechaAsignacion;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getPagoId(): int { return $this->pagoId; }
    public function setPagoId(int $id): void { $this->pagoId = $id; }

    public function getCuotaId(): int { return $this->cuotaId; }
    public function setCuotaId(int $id): void { $this->cuotaId = $id; }

    public function getMontoAbonado(): float { return $this->montoAbonado; }
    public function setMontoAbonado(float $monto): void { $this->montoAbonado = $monto; }

    public function getFechaAsignacion(): ?string { return $this->fechaAsignacion; }
    public function setFechaAsignacion(?string $fecha): void { $this->fechaAsignacion = $fecha; }
}