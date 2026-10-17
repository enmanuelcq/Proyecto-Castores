<?php

class CategoriaEntrenamiento
{
    private ?int $convocatoriaId;
    private int $categoriaId;
    private int $entrenamientoId;
    private ?string $subCategoria;

    public function __construct(
        ?int $convocatoriaId = null,
        int $categoriaId = 0,
        int $entrenamientoId = 0,
        ?string $subCategoria = null
    ) {
        $this->convocatoriaId = $convocatoriaId;
        $this->categoriaId = $categoriaId;
        $this->entrenamientoId = $entrenamientoId;
        $this->subCategoria = $subCategoria;
    }

    public function getConvocatoriaId(): ?int { return $this->convocatoriaId; }
    public function setConvocatoriaId(?int $id): void { $this->convocatoriaId = $id; }

    public function getCategoriaId(): int { return $this->categoriaId; }
    public function setCategoriaId(int $id): void { $this->categoriaId = $id; }

    public function getEntrenamientoId(): int { return $this->entrenamientoId; }
    public function setEntrenamientoId(int $id): void { $this->entrenamientoId = $id; }

    public function getSubCategoria(): ?string { return $this->subCategoria; }
    public function setSubCategoria(?string $sub): void { $this->subCategoria = $sub; }
}
