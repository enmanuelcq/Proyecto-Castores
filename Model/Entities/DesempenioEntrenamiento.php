<?php

class DesempenioEntrenamiento
{
    private int $desempenio_id;
    private int $asistencia;
    private string $desempenio;
    private ?string $observacion;
    private string $atleta_id;
    private int $entrenamiento_id;

    public function __construct(
        int $desempenio_id = 0,
        int $asistencia = 0,
        string $desempenio = '',
        ?string $observacion = null,
        string $atleta_id = '',
        int $entrenamiento_id = 0
    ) {
        $this->desempenio_id = $desempenio_id;
        $this->asistencia = $asistencia;
        $this->desempenio = $desempenio;
        $this->observacion = $observacion;
        $this->atleta_id = $atleta_id;
        $this->entrenamiento_id = $entrenamiento_id;
    }

    public function getDesempenioId(): int { return $this->desempenio_id; }
    public function setDesempenioId(int $id): void { $this->desempenio_id = $id; }

    public function getAsistencia(): int { return $this->asistencia; }
    public function setAsistencia(int $a): void { $this->asistencia = $a; }

    public function getDesempenio(): string { return $this->desempenio; }
    public function setDesempenio(string $d): void { $this->desempenio = $d; }

    public function getObservacion(): ?string { return $this->observacion; }
    public function setObservacion(?string $o): void { $this->observacion = $o; }

    public function getAtletaId(): string { return $this->atleta_id; }
    public function setAtletaId(string $id): void { $this->atleta_id = $id; }

    public function getEntrenamientoId(): int { return $this->entrenamiento_id; }
    public function setEntrenamientoId(int $id): void { $this->entrenamiento_id = $id; }

    public function toArray(): array
    {
        return [
            'id' => $this->desempenio_id,
            'entrenamiento_id' => $this->entrenamiento_id,
            'atleta_id' => $this->atleta_id,
            'asistio' => $this->asistencia,
            'observacion' => $this->observacion ?? '',
        ];
    }
}
