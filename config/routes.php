<?php

use Cake\Routing\Router;

Router::plugin('GintonicCMS', function ($routes) {
    $routes->fallbacks('InflectedRoute');
});

Router::scope('/', ['plugin' => 'GintonicCMS'], function ($routes) {
    $routes->connect('/signin', ['controller' => 'Users', 'action' => 'signin']);
    $routes->connect('/signout', ['controller' => 'Users', 'action' => 'signout']);
    $routes->connect('/signup', ['controller' => 'Users', 'action' => 'signup']);
});
