<?php

use Cake\Routing\Router;

Router::scope('/', ['plugin' => 'GintonicCMS'], function ($routes) {

    $routes->connect('/messages', ['controller' => 'Messages']);
    $routes->connect('/messages/:action/*', ['controller' => 'Messages']);

    $routes->connect('/threads', ['controller' => 'Threads', 'action' => 'view']);
    $routes->connect('/threads/:action/*', ['controller' => 'Threads']);

    $routes->connect('/posts', ['controller' => 'Posts']);
    $routes->connect('/posts/:action/*', ['controller' => 'Posts']);

    $routes->prefix('admin', function ($routes) {

        $routes->connect('/users', ['controller' => 'Users']);
        $routes->connect('/users/:action/*', ['controller' => 'Users']);

        $routes->connect('/posts', ['controller' => 'Posts']);
        $routes->connect('/posts/:action/*', ['controller' => 'Posts']);

    });

});
