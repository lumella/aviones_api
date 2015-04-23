<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//Versionado de la Api
//Las rutas quedaraán algo como www.dominio.local/api/v1.0/...rutas existentes
Route::group(array('prefix' => 'api/v1.0'), function(){

	// Ruta inicial
	Route::get('/', function() {
		return 'Bienvenido API RESTful de aviones.';
	});
	// Creamos rutas nuevas para los controllers

	// Ruta/fabricantes/...
	Route::resource('fabricantes', 'FabricanteController', ['except' => ['edit','create']]);

	// Ruta/aviones/... El resto de métodos los gestiona FabricanteAvion
	Route::resource('aviones', 'AvionController', ['only'=>['index', 'show']]);

	//Recurso anidado /fabricantes/xx/aviones/xx
	Route::resource('fabricantes.aviones', 'FabricanteAvionController', ['except'=>['edit', 'create', 'show']]);

});

Route::get('/', function() {
	return "<a href='http://www.dominio.local/api/v1.0'>Por favor acceda a la versión 1.0 de la api</a>";
	});

/*
Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
*/