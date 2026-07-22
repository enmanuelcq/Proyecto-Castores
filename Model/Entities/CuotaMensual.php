<?php

class CuotaMensual
{
    private ?int $id;
    private string $atletaId;
    private int $mes;
    private int $anio;
    private float $montoEstablecido;
    private string $estatusCuota;
    private ?string $fechaCreacion;
    private ?string $fechaActualizacion;

    public function __construct(
        ?int $id,
        string $atletaId,
        int $mes,
        int $anio,
        float $montoEstablecido,
        string $estatusCuota = 'Pendiente',
        ?string $fechaCreacion = null,
        ?string $fechaActualizacion = null
    ) {
        $this->id = $id;
        $this->atletaId = $atletaId;
        $this->mes = $mes;
        $this->anio = $anio;
        $this->montoEstablecido = $montoEstablecido;
        $this->estatusCuota = $estatusCuota;
        $this->fechaCreacion = $fechaCreacion;
        $this->fechaActualizacion = $fechaActualizacion;
    }

    // Getters y Setters
    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getAtletaId(): string { return $this->atletaId; }
    public function setAtletaId(string $id): void { $this->atletaId = $id; }

    public function getMes(): int { return $this->mes; }
    public function setMes(int $mes): void { $this->mes = $mes; }

    public function getAnio(): int { return $this->anio; }
    public function setAnio(int $anio): void { $this->anio = $anio; }

    public function getMontoEstablecido(): float { return $this->montoEstablecido; }
    public function setMontoEstablecido(float $monto): void { $this->montoEstablecido = $monto; }

    public function getEstatusCuota(): string { return $this->estatusCuota; }
    public function setEstatusCuota(string $estatus): void { $this->estatusCuota = $estatus; }

    public function getFechaCreacion(): ?string { return $this->fechaCreacion; }
    public function setFechaCreacion(?string $fecha): void { $this->fechaCreacion = $fecha; }

    public function getFechaActualizacion(): ?string { return $this->fechaActualizacion; }
    public function setFechaActualizacion(?string $fecha): void { $this->fechaActualizacion = $fecha; }
}