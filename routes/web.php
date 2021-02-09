<?php

/** @var \Laravel\Lumen\Routing\Router $router */


$router->group(['prefix' => '/eats'], function () use ($router) {
    $router->group(['prefix' => '/stores'], function () use ($router) {
        $router->get('/', 'StoreController@all');

        $router->group(['prefix' => '{storeID}'], function () use ($router) {
            $router->get('/', 'StoreController@get');

            $router->get('/status', 'StoreController@getStatus');
            $router->post('/status', 'StoreController@setStatus');

            $router->get('/holiday-hours', 'StoreController@getHolidayHours');
            $router->post('/holiday-hours', 'StoreController@setHolidayHours');

            $router->get('/menus', 'MenuController@index');
            $router->put('/menus', 'MenuController@upload');

            $router->post('/menus/items/{itemID}', 'MenuItemController@update');

            $router->get('/created-orders', 'OrderController@created');
            $router->get('/canceled-orders', 'OrderController@canceled');
        });
    });

    $router->group(['prefix' => '/orders/{orderID}'], function () use ($router) {
        $router->get('/', 'OrderController@get');
        $router->post('/cancel', 'OrderController@cancel');
        $router->post('/accept_pos_order', 'OrderController@accept');
        $router->post('/deny_pos_order', 'OrderController@deny');
    });
});

$router->get('/', function () { echo 'Coucou c\'est nous';});
