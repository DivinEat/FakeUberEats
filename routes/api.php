<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => '/eats'], function () use ($router) {
    $router->get('/', 'StoreController@all');

    $router->group(['prefix' => '/stores'], function () use ($router) {
        $router->group(['prefix' => '{store_id}'], function () use ($router) {
            $router->get('/status', 'StoreStatusController@get');
            $router->post('/status', 'StoreStatusController@set');

            $router->get('/pos_data', 'StorePosDataController@get');
            $router->post('/pos_data', 'StorePosDataController@upate');

            $router->get('/holiday-hours', 'StoreHolidayHoursController@get');
            $router->post('/holiday-hours', 'StoreHolidayHoursController@set');

            $router->get('/menus', 'MenuController@index');
            $router->put('/menus', 'MenuController@upload');

            $router->post('/menus/items/{item_id}', 'MenuItemController@update');

            $router->get('/created-orders', 'OrderController@created');
            $router->get('/canceled-orders', 'OrderController@canceled');
        });
    });

    $router->group(['prefix' => '/orders/{order_id}'], function () use ($router) {
        $router->get('/', 'OrderController@get');

        $router->post('/accept_pos_order', 'OrderController@accept');

        $router->post('/deny_pos_order', 'OrderController@deny');

        $router->post('/cancel', 'OrderController@cancel');

        $router->patch('/cart', 'OrderController@cancel');
    });
});
