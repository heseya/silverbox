<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', 'HomeController@info');
$router->get('{client}/{file}', 'ViewController@view');

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->post('{client}', 'UploadController@upload');
    $router->delete('{client}/{file}', 'DeleteController@delete');
});
