<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Rutas

// Grupo registro
$container['RegistroController'] = function ($c) {
	$controlador = new \App\Controllers\RegistroController($c);
	return $controlador;
};

$app->group('/registro', function() use ($app) {
	//$app->get('/vista/lista', 'UsuarioController:lista');
	//$app->get('/vista/formulario', 'UsuarioController:formulario');

	//$app->get('/todos', 'UsuarioController:todos');
	//$app->get('/{id}', 'UsuarioController:obtener');
	$app->post('', 'RegistroController:agregar');
	//$app->put('/{id}', 'UsuarioController:actualizar');
	//$app->delete('/{id}', 'UsuarioController:eliminar');
});



$app->group('/test', function() use ($app) {
	$app->get('/', function () { 
	    echo "Testing!"; 
	});
});