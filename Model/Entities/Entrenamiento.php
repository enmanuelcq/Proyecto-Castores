<?php

class Entrenamiento
{
    private int $entrenamiento_id;
    private string $fecha_entrenamiento;
    private string $hora_inicio;
    private string $hora_fin;
    private string $objetivo_entrenamiento;
    private string $tiempo_estimado;
    private string $tipo_entrenamiento;
    private string $categoria;
    private string $lugar;
    private string $estado;
    private int $total_atletas;
    private ?string $descripcion;
    private string $usuario_cedula;

    public function __construct(
        int $entrenamiento_id = 0,
        string $fecha_entrenamiento = '',
        string $hora_inicio = '08:00',
        string $hora_fin = '10:00',
        string $objetivo_entrenamiento = '',
        string $tiempo_estimado = '02:00:00',
        string $tipo_entrenamiento = '',
        string $categoria = '',
        string $lugar = '',
        string $estado = 'Programado',
        int $total_atletas = 0,
        ?string $descripcion = null,
        string $usuario_cedula = ''
    ) {
        $this->entrenamiento_id = $entrenamiento_id;
        $this->fecha_entrenamiento = $fecha_entrenamiento;
        $this->hora_inicio = $hora_inicio;
        $this->hora_fin = $hora_fin;
        $this->objetivo_entrenamiento = $objetivo_entrenamiento;
        $this->tiempo_estimado = $tiempo_estimado;
        $this->tipo_entrenamiento = $tipo_entrenamiento;
        $this->categoria = $categoria;
        $this->lugar = $lugar;
        $this->estado = $estado;
        $this->total_atletas = $total_atletas;
        $this->descripcion = $descripcion;
        $this->usuario_cedula = $usuario_cedula;
    }

    public function getEntrenamientoId(): int { return $this->entrenamiento_id; }
    public function setEntrenamientoId(int $id): void { $this->entrenamiento_id = $id; }

    public function getFechaEntrenamiento(): string { return $this->fecha_entrenamiento; }
    public function setFechaEntrenamiento(string $fecha): void { $this->fecha_entrenamiento = $fecha; }

    public function getHoraInicio(): string { return $this->hora_inicio; }
    public function setHoraInicio(string $hora): void { $this->hora_inicio = $hora; }

    public function getHoraFin(): string { return $this->hora_fin; }
    public function setHoraFin(string $hora): void { $this->hora_fin = $hora; }

    public function getObjetivoEntrenamiento(): string { return $this->objetivo_entrenamiento; }
    public function setObjetivoEntrenamiento(string $obj): void { $this->objetivo_entrenamiento = $obj; }

    public function getTiempoEstimado(): string { return $this->tiempo_estimado; }
    public function setTiempoEstimado(string $t): void { $this->tiempo_estimado = $t; }

    public function getTipoEntrenamiento(): string { return $this->tipo_entrenamiento; }
    public function setTipoEntrenamiento(string $tipo): void { $this->tipo_entrenamiento = $tipo; }

    public function getCategoria(): string { return $this->categoria; }
    public function setCategoria(string $cat): void { $this->categoria = $cat; }

    public function getLugar(): string { return $this->lugar; }
    public function setLugar(string $lugar): void { $this->lugar = $lugar; }

    public function getEstado(): string { return $this->estado; }
    public function setEstado(string $estado): void { $this->estado = $estado; }

    public function getTotalAtletas(): int { return $this->total_atletas; }
    public function setTotalAtletas(int $t): void { $this->total_atletas = $t; }

    public function getDescripcion(): ?string { return $this->descripcion; }
    public function setDescripcion(?string $d): void { $this->descripcion = $d; }

    public function getUsuarioCedula(): string { return $this->usuario_cedula; }
    public function setUsuarioCedula(string $c): void { $this->usuario_cedula = $c; }

    public function toArray(): array
    {
        return [
            'id' => $this->entrenamiento_id,
            'fecha' => date('Y-m-d', strtotime($this->fecha_entrenamiento)),
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'categoria' => $this->categoria,
            'tipo' => $this->tipo_entrenamiento,
            'descripcion' => $this->descripcion ?? $this->objetivo_entrenamiento,
            'lugar' => $this->lugar,
            'entrenador_cedula' => $this->usuario_cedula,
            'entrenador_nombre' => '',
            'estado' => $this->estado,
            'asistentes' => 0,
            'total_atletas' => $this->total_atletas,
        ];
    }
}
