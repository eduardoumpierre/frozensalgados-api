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

Dusterio\LumenPassport\LumenPassport::routes($router);

$router->get('/', function () use ($router) {
    return $router->app->version();
});

/**
 * Api
 */
$router->group(['prefix' => 'api/v1', 'middleware' => ['auth']], function () use ($router) {
    /**
     * Customers
     */
    $router->group(['prefix' => 'customers'], function () use ($router) {
        $router->get('/', 'CustomerController@getAll');
        $router->get('/{id}', 'CustomerController@getOne');
        $router->post('/', 'CustomerController@create');
    });

    /**
     * Orders
     */
    $router->group(['prefix' => 'orders'], function () use ($router) {
        $router->get('/', 'OrderController@getAll');
        $router->get('/{id}', 'OrderController@getOne');
        $router->post('/', 'OrderController@create');
    });

    /**
     * Products
     */
    $router->group(['prefix' => 'products'], function () use ($router) {
        $router->get('/', 'ProductController@getAll');
        $router->post('/', 'ProductController@create');
    });

    /**
     * Lists
     */
    $router->group(['prefix' => 'lists'], function () use ($router) {
        $router->post('/', 'ListController@create');
    });

    /**
     * Auth
     */
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/me', 'AuthController@me');
    });
});
