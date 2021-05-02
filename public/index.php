<?php
include '../config.php';
use core\Router;
require __DIR__ . '../../vendor/autoload.php';
include '../services/eloquent.php';
include '../services/functions.php';


Router::add('/', ['controller' => 'Main', 'action' => 'index']);
Router::add('/telegram', ['controller' => 'Telegram', 'action' => 'index']);


Router::run();
