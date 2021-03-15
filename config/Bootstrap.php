<?php
require_once __DIR__.'/Autoloader.php';
include 'Request.php';
include_once 'Router.php';

$autoloader = new Autoloader();
$router = new Router(new Request);
$autoloader->loader($router);
$web =  new Web($router);

return $web;





