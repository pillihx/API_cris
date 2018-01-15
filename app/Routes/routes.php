<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Rutas

// Grupo usuario
$container['UsuarioController'] = function ($c) {
	$controlador = new \App\Controllers\UsuarioController($c);
	return $controlador;
};

$app->group('/usuario', function() use ($app) {
	$app->get('/vista/lista', 'UsuarioController:lista');
	$app->get('/vista/formulario', 'UsuarioController:formulario');

	$app->get('/todos', 'UsuarioController:todos');
	$app->get('/{id}', 'UsuarioController:obtener');
	$app->post('', 'UsuarioController:agregar');
	$app->put('/{id}', 'UsuarioController:actualizar');
	$app->delete('/{id}', 'UsuarioController:eliminar');
});
// Grupo proyecto
$container['ProyectoController'] = function ($c) {
	$controlador = new \App\Controllers\ProyectoController($c);
	return $controlador;
};

$app->group('/proyecto', function() use ($app) {
	$app->get('/vista/lista', 'ProyectoController:lista');
	$app->get('/vista/formulario', 'ProyectoController:formulario');

	$app->get('/todos', 'ProyectoController:todos');
	$app->get('/{id}', 'ProyectoController:obtener');
	$app->post('', 'ProyectoController:agregar');
	$app->put('/{id}', 'ProyectoController:actualizar');
	$app->delete('/{id}', 'ProyectoController:eliminar');
});

// Grupo tarea
$container['TareaController'] = function ($c) {
	$controlador = new \App\Controllers\TareaController($c);
	return $controlador;
};

$app->group('/tarea', function() use ($app) {
	$app->get('/vista/lista', 'TareaController:lista');
	$app->get('/vista/formulario', 'TareaController:formulario');

	$app->get('/todos', 'TareaController:todos');
	$app->get('/{id}', 'TareaController:obtener');
	$app->post('', 'TareaController:agregar');
	$app->put('/{id}', 'TareaController:actualizar');
	$app->delete('/{id}', 'TareaController:eliminar');
});

// Grupo notificacion
$container['NotificacionController'] = function ($c) {
	$controlador = new \App\Controllers\NotificacionController($c);
	return $controlador;
};

$app->group('/notificacion', function() use ($app) {
	$app->get('/vista/lista', 'NotificacionController:lista');
	$app->get('/vista/formulario', 'NotificacionController:formulario');

	$app->get('/todos', 'NotificacionController:todos');
	$app->get('/{id}', 'NotificacionController:obtener');
	$app->post('', 'NotificacionController:agregar');
	$app->put('/{id}', 'NotificacionController:actualizar');
	$app->delete('/{id}', 'NotificacionController:eliminar');
});

// Grupo buzon
$container['BuzonController'] = function ($c) {
	$controlador = new \App\Controllers\BuzonController($c);
	return $controlador;
};

$app->group('/buzon', function() use ($app) {
	$app->get('/vista/lista', 'BuzonController:lista');
	$app->get('/vista/formulario', 'BuzonController:formulario');

	$app->get('/todos', 'BuzonController:todos');
	$app->get('/{id}', 'BuzonController:obtener');
	$app->post('', 'BuzonController:agregar');
	$app->put('/{id}', 'BuzonController:actualizar');
	$app->delete('/{id}', 'BuzonController:eliminar');
});



/*$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});*/
