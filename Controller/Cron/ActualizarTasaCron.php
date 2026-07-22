<?php
// Controller/Cron/ActualizarTasaCron.php
// Este archivo se ejecuta desde el cron

$root = dirname(__DIR__, 2);
require_once $root . '/Controller/ActualizarTasaController.php';

$controller = new ActualizarTasaController();
$controller->ejecutar();