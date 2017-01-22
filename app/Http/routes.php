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

$app->group(['middleware' => 'jwt-auth', 'namespace' => 'App\Http\Controllers'],
    function () use ($app) {

    //Users
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
    $app->put('users/{user}/tasks/{task}', [
        'as' => 'users.addTask', 'uses' => 'UsersController@addTask'
    ]);
    $app->delete('users/{user}/tasks/{task}', [
        'as' => 'users.removeTask', 'uses' => 'UsersController@removeTask'
    ]);


    //Priorities
    $app->get('priorities', [
        'as' => 'priorities.index', 'uses' => 'PrioritiesController@index'
    ]);
    $app->get('priorities/{id}', [
        'as' => 'priorities.show', 'uses' => 'PrioritiesController@show'
    ]);
    $app->post('priorities', [
        'as' => 'priorities.store', 'uses' => 'PrioritiesController@store'
    ]);
    $app->put('priorities/{id}', [
        'as' => 'priorities.update', 'uses' => 'PrioritiesController@update'
    ]);
    $app->delete('priorities/{id}', [
        'as' => 'priorities.destroy', 'uses' => 'PrioritiesController@destroy'
    ]);

    //Tasks
    $app->get('tasks', [
        'as' => 'tasks.index', 'uses' => 'TasksController@index'
    ]);
    $app->get('tasks/{id}', [
        'as' => 'tasks.show', 'uses' => 'TasksController@show'
    ]);
    $app->post('tasks', [
        'as' => 'task.store', 'uses' => 'TasksController@store'
    ]);
    $app->put('tasks/{id}', [
        'as' => 'task.update', 'uses' => 'TasksController@update'
    ]);
    $app->delete('tasks/{id}', [
        'as' => 'task.destroy', 'uses' => 'TasksController@destroy'
    ]);
});

