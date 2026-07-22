<?php

class Operacion
{
    private ?int $id;
    private float $monto;
    private int $pagoId;
    private int $tipoPagoId;
    private ?string $referencia;
    private ?string $banco; // ← NUEVO
    private ?string $fechaOperacion;

    public function __construct(
        ?int $id,
        float $monto,
        int $pagoId,
        int $tipoPagoId,
        ?string $referencia = null,
        ?string $banco = null, // ← NUEVO
        ?string $fechaOperacion = null
    ) {
        $this->id = $id;
        $this->monto = $monto;
        $this->pagoId = $pagoId;
        $this->tipoPagoId = $tipoPagoId;
        $this->referencia = $referencia;
        $this->banco = $banco; // ← NUEVO
        $this->fechaOperacion = $fechaOperacion;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getMonto(): float { return $this->monto; }
    public function setMonto(float $monto): void { $this->monto = $monto; }

    public function getPagoId(): int { return $this->pagoId; }
    public function setPagoId(int $id): void { $this->pagoId = $id; }

    public function getTipoPagoId(): int { return $this->tipoPagoId; }
    public function setTipoPagoId(int $id): void { $this->tipoPagoId = $id; }

    public function getReferencia(): ?string { return $this->referencia; }
    public function setReferencia(?string $ref): void { $this->referencia = $ref; }

    public function getFechaOperacion(): ?string { return $this->fechaOperacion; }
    public function setFechaOperacion(?string $fecha): void { $this->fechaOperacion = $fecha; }

    public function getBanco(): ?string { return $this->banco; }
    public function setBanco(?string $banco): void { $this->banco = $banco; }
}