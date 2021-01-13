<?php
include '../config.php';
use core\Router;
spl_autoload_register(function ($class){
    $path = str_replace('\\', '/',$_SERVER['DOCUMENT_ROOT'].DIRECTORY_SEPARATOR.$class.'.php');
    if (file_exists($path)){
        require $path;
    }
});


Router::add('/', ['controller' => 'Main', 'action' => 'index']);
Router::add('/getData', ['controller' => 'Main', 'action' => 'getData']);

Router::run();
