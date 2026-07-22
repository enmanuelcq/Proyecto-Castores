<?php
// Controller/CargarCuotasManual.php

$root = dirname(__DIR__);
require_once $root . '/Config/Database.php';
require_once $root . '/Model/Entities/CuotaMensual.php';
require_once $root . '/Model/Repository/RepositoryAtleta.php';
require_once $root . '/Model/Repository/RepositoryCuotaMensual.php';
require_once $root . '/Model/Repository/RepositoryConfiguracion.php';

$repoAtleta = new RepositoryAtleta();
$repoCuota = new RepositoryCuotaMensual();
$repoConfig = new RepositoryConfiguracion();

$montoMensual = $repoConfig->getMontoMensualBs(); // Calcula monto en Bs según la tasa actual
$anioActual = date('Y');
$mesActual = (int)date('m');

// Si tasa es 0, usar valor fijo
if ($montoMensual <= 0) {
    $montoMensual = 50.00;
}

$atletas = $repoAtleta->findAll();
$contador = 0;

echo "<h2>Cargando cuotas para todos los atletas</h2>";
echo "<p>Monto mensual: Bs " . number_format($montoMensual, 2) . "</p>";

foreach ($atletas as $atleta) {
    // Solo crear cuotas desde el mes actual hasta diciembre
    for ($mes = $mesActual; $mes <= 12; $mes++) {
        // Verificar si ya existe cuota
        $existentes = $repoCuota->findByAtleta($atleta->getAtletaId());
        $existe = false;
        foreach ($existentes as $c) {
            if ($c->getMes() == $mes && $c->getAnio() == $anioActual) {
                $existe = true;
                break;
            }
        }
        if (!$existe) {
            $cuota = new CuotaMensual(
                null,
                $atleta->getAtletaId(),
                $mes,
                $anioActual,
                $montoMensual,
                'Pendiente'
            );
            if ($repoCuota->create($cuota)) {
                $contador++;
                echo "✅ Cuota creada para {$atleta->getPrimerNombre()} {$atleta->getPrimerApellido()} - Mes $mes<br>";
            }
        }
    }
}
echo "<br><strong>✅ Total de cuotas creadas: $contador</strong>";