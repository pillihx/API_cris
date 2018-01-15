<?php 

// Inicializar las configuraciones y aplicación
$settings = require __DIR__ . '/../app/settings.php';
 
$app = new \Slim\App($settings);

// Cargo controlador de base de datos
require __DIR__ . '/../app/database.php';

// Configurar dependencias
require __DIR__ . '/../app/dependencies.php';

// Iniciar middleware
require __DIR__ . '/../app/middleware.php';