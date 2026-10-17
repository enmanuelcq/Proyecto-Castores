<?php

class EntrenamientoRecurso
{
    private ?int $recursoId;
    private int $entrenamientoId;
    private string $tipoRecurso;
    private ?string $url;
    private ?string $archivo;
    private ?string $descripcion;

    public function __construct(
        ?int $recursoId = null,
        int $entrenamientoId = 0,
        string $tipoRecurso = 'enlace',
        ?string $url = null,
        ?string $archivo = null,
        ?string $descripcion = null
    ) {
        $this->recursoId = $recursoId;
        $this->entrenamientoId = $entrenamientoId;
        $this->tipoRecurso = $tipoRecurso;
        $this->url = $url;
        $this->archivo = $archivo;
        $this->descripcion = $descripcion;
    }

    public function getRecursoId(): ?int { return $this->recursoId; }
    public function setRecursoId(?int $id): void { $this->recursoId = $id; }

    public function getEntrenamientoId(): int { return $this->entrenamientoId; }
    public function setEntrenamientoId(int $id): void { $this->entrenamientoId = $id; }

    public function getTipoRecurso(): string { return $this->tipoRecurso; }
    public function setTipoRecurso(string $tipo): void { $this->tipoRecurso = $tipo; }

    public function getUrl(): ?string { return $this->url; }
    public function setUrl(?string $url): void { $this->url = $url; }

    public function getArchivo(): ?string { return $this->archivo; }
    public function setArchivo(?string $archivo): void { $this->archivo = $archivo; }

    public function getDescripcion(): ?string { return $this->descripcion; }
    public function setDescripcion(?string $desc): void { $this->descripcion = $desc; }

    public function toArray(): array
    {
        return [
            'recurso_id' => $this->recursoId,
            'entrenamiento_id' => $this->entrenamientoId,
            'tipo_recurso' => $this->tipoRecurso,
            'url' => $this->url,
            'archivo' => $this->archivo,
            'descripcion' => $this->descripcion,
        ];
    }
}
