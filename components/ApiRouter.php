<?php

require_once ROOT . '/components/HelperFunc.php';

class ApiRouter
{
    private $routes;

    public function __construct()
    {
        $routersPath = ROOT . '/config/routes.php';
        $this->routes = include($routersPath);
    }

    public function run()
    {
        $uri = $this->getURI();
        foreach ($this->routes as $uriPattern => $path) {
            if (preg_match("~$uriPattern~", $uri))
            {
                if ($path == end($this->routes)) {
                    $internalroute = $path;
                } else {
                    $internalroute = preg_replace("~$uriPattern~", $path, $uri);
                }
                $segment = explode('/', $internalroute);

                $controllerName = ucfirst(array_shift($segment)) . 'Controller';

                $actionName = ucfirst(array_shift($segment)) . 'Action';
                $parameters = $segment; //остатки, параметры

                $controlFile = ROOT . '/controllers/' . $controllerName . '.php';

                if (file_exists($controlFile)) {
                    include_once $controlFile;
                }

                $controllerObject = new $controllerName;
                $result = $controllerObject->$actionName($parameters);

                if ($result != null) {
                    break;
                }
            }

        }
    }

    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
}