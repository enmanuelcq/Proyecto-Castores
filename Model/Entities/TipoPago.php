<?php

class TipoPago
{
    private ?int $id;
    private string $tipoPago;

    public function __construct(?int $id, string $tipoPago)
    {
        $this->id = $id;
        $this->tipoPago = $tipoPago;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getTipoPago(): string { return $this->tipoPago; }
    public function setTipoPago(string $tipo): void { $this->tipoPago = $tipo; }
}