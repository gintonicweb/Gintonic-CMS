<?php

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\EventManager;
use Cake\Routing\DispatcherFactory;
use Permissions\Listener\RoleListener;

// Crud stack
Plugin::load('BootstrapUI');
Plugin::load('Crud');
Plugin::load('CrudView');
Plugin::load('Search');
Configure::write('CrudView', []);

// Javascript
Plugin::load('Requirejs');
Plugin::load('Websockets', ['bootstrap' => true]);
Configure::write('Websockets.userModel', 'Users.Users');
Configure::write('Websockets.scope', false);

// File management
Plugin::load('Images', ['bootstrap' => true]);

// Themes
Plugin::load('AdminTheme');
Plugin::load('TwbsTheme');

// Users Management
Plugin::load('Users', ['routes' => true, 'bootstrap' => 'true']);
Plugin::load('FOC/Authenticate');
Plugin::load('Permissions', ['routes' => true]);
EventManager::instance()->attach(new RoleListener());


// Application base
Plugin::load('Posts', ['bootstrap' => 'true']);
Plugin::load('Messages', ['routes' => true, 'bootstrap' => 'true']);



