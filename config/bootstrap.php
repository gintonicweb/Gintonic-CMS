<?php

use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\EventManager;
use Cake\Routing\DispatcherFactory;
use Permissions\Listener\RoleListener;
use Websockets\Listener\WebsocketsListener;

// Crud stack
Plugin::load('BootstrapUI');
Plugin::load('Crud');
Plugin::load('CrudView');
Plugin::load('Search');
Configure::write('CrudView', []);

// Javascript
Plugin::load('Requirejs');

// File management
Plugin::load('Images', ['bootstrap' => true]);

// Themes
Plugin::load('Gintonic/Makeup');
Plugin::load('AdminTheme');
Plugin::load('TwbsTheme');

// Users Management
Plugin::load('Users', ['routes' => true, 'bootstrap' => true]);
Plugin::load('FOC/Authenticate');
Plugin::load('Permissions', ['routes' => true]);
EventManager::instance()->attach(new RoleListener());
