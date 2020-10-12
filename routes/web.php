<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->get('/', function () use ($router) {
    return "selamat datang di API Copas Code";
});

$router->group(['prefix' => 'auth'], function () use ($router) {
    $router->post('/login', 'AuthController@login'); //dont need token
    $router->post('/logout', 'AuthController@logout'); //need token
    $router->post('/refresh', 'AuthController@refresh'); //need token
    $router->post('/me', 'AuthController@me'); //need token
});

$router->group(['prefix' => 'users'], function () use ($router) {
    $router->get('/getAll', 'UserController@index'); //need token
    $router->get('/getById/{id}', 'UserController@show'); //need token
    $router->post('/reset/{id}', 'UserController@reset'); //need token
    $router->post('/store', 'UserController@store'); // need token
    $router->post('/update/{id}', 'UserController@update'); //need token
    $router->post('/active/{id}', 'UserController@active'); //need token
    $router->delete('/delete/{id}', 'UserController@destroy'); //need token
});

$router->group(['prefix' => 'menus'], function () use ($router) {
    $router->get('/getAll', 'MenuController@index'); //need token
    $router->get('/getMenu', 'MenuController@getMenu'); //need token
    $router->get('/getById/{id}', 'MenuController@show'); //need token
    $router->post('/store', 'MenuController@store'); // need token
    $router->post('/update/{id}', 'MenuController@update'); //need token
    $router->post('/active/{id}', 'MenuController@active'); //need token
    $router->delete('/delete/{id}', 'MenuController@destroy'); //need token
});
$router->group(['prefix' => 'accesses'], function () use ($router) {
    $router->get('/getAll', 'AccessController@index'); //need token
    $router->get('/getMenu', 'AccessController@getMenu'); //need token
    $router->get('/check', 'AccessController@check'); //need token
    $router->post('/active', 'AccessController@active'); //need token
});
$router->group(['prefix' => 'submenus'], function () use ($router) {
    $router->get('/getAll', 'SubmenuController@index'); //need token
    $router->get('/getByMenuId/{id}', 'SubmenuController@getByMenuId'); //need token
    $router->get('/getById/{id}', 'SubmenuController@show'); //need token
    $router->post('/store', 'SubmenuController@store'); // need token
    $router->post('/update/{id}', 'SubmenuController@update'); //need token
    $router->put('/active/{id}', 'SubmenuController@active'); //need token
    $router->delete('/delete/{id}', 'SubmenuController@destroy'); //need token
});

$router->group(['prefix' => 'roles'], function () use ($router) {
    $router->get('/getAll', 'RoleController@index'); //need token
    $router->get('/getById/{id}', 'RoleController@show'); //need token
    $router->post('/store', 'RoleController@store'); // need token
    $router->post('/update/{id}', 'RoleController@update'); //need token
    $router->delete('/delete/{id}', 'RoleController@destroy'); //need token
});
