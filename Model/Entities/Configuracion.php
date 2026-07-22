<?php
class Configuracion
{
    private ?int $id;
    private string $clave;
    private string $valor;
    private ?string $descripcion;
    private ?string $fechaActualizacion;

    public function __construct(
        ?int $id,
        string $clave,
        string $valor,
        ?string $descripcion = null,
        ?string $fechaActualizacion = null
    ) {
        $this->id = $id;
        $this->clave = $clave;
        $this->valor = $valor;
        $this->descripcion = $descripcion;
        $this->fechaActualizacion = $fechaActualizacion;
    }

    public function getId(): ?int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getClave(): string { return $this->clave; }
    public function setClave(string $clave): void { $this->clave = $clave; }

    public function getValor(): string { return $this->valor; }
    public function setValor(string $valor): void { $this->valor = $valor; }

    public function getDescripcion(): ?string { return $this->descripcion; }
    public function setDescripcion(?string $descripcion): void { $this->descripcion = $descripcion; }

    public function getFechaActualizacion(): ?string { return $this->fechaActualizacion; }
    public function setFechaActualizacion(?string $fecha): void { $this->fechaActualizacion = $fecha; }
}