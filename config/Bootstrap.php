<?php
require_once __DIR__.'/Autoloader.php';
$autoloader = new Autoloader();
$router = $autoloader->loader();
$web =  new Web($router);

return $web;





