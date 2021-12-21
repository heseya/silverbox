<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */

$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->post('{client}', 'UploadController@store');
    $router->patch('{client}/{fileName}', 'UpdateController@update');
    $router->delete('{client}/{fileName}', 'DeleteController@destroy');
    $router->get('{client}/{fileName}/info', 'InfoController@info');
});

$router->get('{client}/{fileName}', 'ViewController@show');
