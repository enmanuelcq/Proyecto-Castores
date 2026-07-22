<?php

class Pago
{
    private ?int $id;
    private float $totalPago;
    private string $conceptoPago;
    private string $cedulaRepresentante;
    private ?string $fechaPago;
    private string $estatusPago;

    public function __construct(
        ?int $id,
        float $totalPago,
        string $conceptoPago,
        string $cedulaRepresentante,
        ?string $fechaPago = null,
        string $estatusPago = 'Completado'
    ) {
        $this->id = $id;
        $this->totalPago = $totalPago;
        $this->conceptoPago = $conceptoPago;
        $this->cedulaRepresentante = $cedulaRepresentante;
        $this->fechaPago = $fechaPago;
        $this->estatusPago = $estatusPago;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getTotalPago(): float { return $this->totalPago; }
    public function setTotalPago(float $total): void { $this->totalPago = $total; }

    public function getConceptoPago(): string { return $this->conceptoPago; }
    public function setConceptoPago(string $concepto): void { $this->conceptoPago = $concepto; }

    public function getCedulaRepresentante(): string { return $this->cedulaRepresentante; }
    public function setCedulaRepresentante(string $cedula): void { $this->cedulaRepresentante = $cedula; }

    public function getFechaPago(): ?string { return $this->fechaPago; }
    public function setFechaPago(?string $fecha): void { $this->fechaPago = $fecha; }

    public function getEstatusPago(): string { return $this->estatusPago; }
    public function setEstatusPago(string $estatus): void { $this->estatusPago = $estatus; }
}