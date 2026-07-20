<?php

$root = dirname(__DIR__, 2);
require_once $root . '/Model/Entities/Salud.php';
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Interfaces/IUSaludRepository.php';

class RepositorySalud implements ISaludRepository
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(Salud $salud): bool
    {
        $sql = "INSERT INTO salud (
                    tipo_sangre,
                    alergias,
                    padecimiento,
                    medicamento_no_tomar,
                    lesiones_previas,
                    poliza_seguro,
                    vigencia,
                    empresa,
                    cobertura,
                    nro_poliza,
                    nombre_medico,
                    telefono_medico,
                    Atleta_id
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $salud->getTipoSangre(),
            $salud->getAlergias(),
            $salud->getPadecimiento(),
            $salud->getMedicamentoNoTomar(),
            $salud->getLesionesPrevias(),
            $salud->getPolizaSeguro(),
            $salud->getVigencia(),
            $salud->getEmpresa(),
            $salud->getCobertura(),
            $salud->getNroPoliza(),
            $salud->getNombreMedico(),
            $salud->getTelefonoMedico(),
            $salud->getAtletaId()
        ]);
    }

    public function update(Salud $salud): bool
    {
        $sql = "UPDATE salud SET
                    tipo_sangre = ?,
                    alergias = ?,
                    padecimiento = ?,
                    medicamento_no_tomar = ?,
                    lesiones_previas = ?,
                    poliza_seguro = ?,
                    vigencia = ?,
                    empresa = ?,
                    cobertura = ?,
                    nro_poliza = ?,
                    nombre_medico = ?,
                    telefono_medico = ?,
                    Atleta_id = ?
                WHERE salud_id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $salud->getTipoSangre(),
            $salud->getAlergias(),
            $salud->getPadecimiento(),
            $salud->getMedicamentoNoTomar(),
            $salud->getLesionesPrevias(),
            $salud->getPolizaSeguro(),
            $salud->getVigencia(),
            $salud->getEmpresa(),
            $salud->getCobertura(),
            $salud->getNroPoliza(),
            $salud->getNombreMedico(),
            $salud->getTelefonoMedico(),
            $salud->getAtletaId(),
            $salud->getId()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM salud WHERE salud_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function findById(int $id): ?Salud
    {
        $sql = "SELECT * FROM salud WHERE salud_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToSalud($row) : null;
    }

    public function findByAtletaId(string $atletaId): ?Salud
    {
        $sql = "SELECT * FROM salud WHERE Atleta_id = ? LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$atletaId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $row ? $this->mapRowToSalud($row) : null;
    }

    private function mapRowToSalud(array $row): Salud
    {
        return new Salud(
            (int) $row['salud_id'],
            $row['tipo_sangre'],
            $row['alergias'],
            $row['padecimiento'],
            $row['medicamento_no_tomar'],
            $row['lesiones_previas'],
            $row['poliza_seguro'],
            $row['vigencia'],
            $row['empresa'],
            $row['cobertura'],
            $row['nro_poliza'],
            $row['nombre_medico'],
            $row['telefono_medico'],
            $row['Atleta_id']
        );
    }
}