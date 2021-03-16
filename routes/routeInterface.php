<?php

interface routeInterface
{
    public function __construct($router);

    public function controller($request,$controllerClass,$function);

}
