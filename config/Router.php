<?php

class Router
{
    private $request;
    private $supportedHttpMethods = array(
        "GET",
        "POST"
    );

    function __construct(IRequest $request)
    {
        $this->request = $request;
    }

    function __call($name, $args)
    {
        list($route, $method) = $args;

        if(!in_array(strtoupper($name), $this->supportedHttpMethods))
        {
            $this->invalidMethodHandler();
        }

        $this->{strtolower($name)}[$this->formatRoute($route)] = $method;
    }

    /**
     * Removes trailing forward slashes from the right of the route.
     * @param route (string)
     */
    private function formatRoute($route)
    {
        $result = rtrim($route, '/');
        if ($result === '')
        {
            return '/';
        }
        return $result;
    }

    private function invalidMethodHandler()
    {
        header("{$this->request->serverProtocol} 405 Method Not Allowed");
    }

    private function defaultRequestHandler()
    {
        header("{$this->request->serverProtocol} 404 Not Found");
    }

    /**
     * Resolves a route
     */
    function resolve()
    {
        $methodDictionary = $this->{strtolower($this->request->requestMethod)};
        $formatedRoute = $this->formatRoute($this->request->requestUri);
        if(!isset($methodDictionary[$formatedRoute]))
        {
            echo "The Url you want to access is only available to logged in users";
            return;
        }
        $method = $methodDictionary[$formatedRoute];
        unset($methodDictionary);

        if(is_null($method))
        {
            $this->defaultRequestHandler();
            return;
        }
        if (is_array($method) && ($html = implode($method)) != strip_tags($html)) {
            echo $html;
        }
        else if(isset($methodDictionary['/']))
        {
           echo implode($methodDictionary['/']);
        }
        else {
            echo call_user_func_array($method, array($this->request));
        }
    }






}
