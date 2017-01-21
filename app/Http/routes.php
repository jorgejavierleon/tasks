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

$app->post('auth/login', [
    'as' => 'login', 'uses' => 'AuthController@login'
]);

$app->group(
    ['middleware' => 'jwt-auth', 'namespace' => 'App\Http\Controllers'],
    function () use ($app) {

    $app->get('users', [
        'as' => 'users.index', 'uses' => 'UsersController@index'
    ]);

    $app->get('users/{id}', [
        'as' => 'users.show', 'uses' => 'UsersController@show'
    ]);

    $app->post('users', [
        'as' => 'users.store', 'uses' => 'UsersController@store'
    ]);

    $app->put('users/{id}', [
        'as' => 'users.update', 'uses' => 'UsersController@update'
    ]);

    $app->delete('users/{id}', [
        'as' => 'users.destroy', 'uses' => 'UsersController@destroy'
    ]);
});

