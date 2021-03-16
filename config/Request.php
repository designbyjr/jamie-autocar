<?php
include_once 'IRequest.php';
//require_once '../middleware/Middleware.php';

class Request implements IRequest
{
    function __construct()
    {
        $this->bootstrapSelf();
        // Registers middleware
        new Middleware();
    }

    private function bootstrapSelf()
    {
        foreach($_SERVER as $key => $value)
        {
            $this->{$this->toCamelCase($key)} = $value;
        }
    }

    private function toCamelCase($string)
    {
        $result = strtolower($string);

        preg_match_all('/_[a-z]/', $result, $matches);

        foreach($matches[0] as $match)
        {
            $c = str_replace('_', '', strtoupper($match));
            $result = str_replace($match, $c, $result);
        }
    
        return $result;
    }

    public function input($arrkey = null)
    {
        if($this->requestMethod === "GET")
        {
            return;
        }


        if ($this->requestMethod == "POST")
        {

            $body = array();
            foreach($_POST as $key => $value)
            {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
            if(isset($arrkey))
            {
                return $body[$arrkey];
            }
            return $body;
        }
    }
}
