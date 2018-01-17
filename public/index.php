<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargo el autoload de composer
require __DIR__ . '/../vendor/autoload.php';
// Bootstrap load
require __DIR__ . '/../app/bootstrap.php';

session_start();

$_SESSION['usuario_id'] = 1;

// Cargar todas las rutas
foreach(glob(__DIR__ . '/../app/Routes/*.php') as $ruta) {
	require_once($ruta);
}

// Ejecutar
$app->run();
