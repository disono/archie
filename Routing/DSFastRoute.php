<?php

namespace Disono\Routing;

class DSFastRoute
{

    public $setType = 'web';
    public $routeList = [];

    private $_setRoutes = [];

    public function __construct()
    {

    }

    public function dispatch()
    {
        $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            $routes = require_once BASE_FOLDER . 'app/routes/routing.php';
            $config = config('routing');

            foreach ($routes as $route) {
                $prefix = '';
                if ($route['type'] === 'admin' || $route['type'] === 'api') {
                    $prefix = $config['prefix'][$route['type']];
                }

                $r->addRoute($route['method'], $prefix . $route['route'], $route['type'] . $route['class']);
            }
        });

        $this->_dispatcher($dispatcher);
    }

    public function setRoute($method, $class, $route, $name = null)
    {
        $this->_setRoutes[] = [
            'type' => $this->setType . '/',
            'method' => $method,
            'class' => $class,
            'route' => $route,
            'name' => $name
        ];
    }

    public function getRoutes()
    {
        return $this->_setRoutes;
    }

    private function _dispatcher($dispatcher)
    {
        // Fetch method and URI from somewhere
        $httpMethod = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];

        // Strip query string (?foo=bar) and decode URI
        if (false !== $pos = strpos($uri, '?')) {
            $uri = substr($uri, 0, $pos);
        }

        $routeInfo = $dispatcher->dispatch($httpMethod, rawurldecode($uri));

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                // 404 Not Found
                echo view('themes/master/errors/404');
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $allowedMethods = $routeInfo[1];
                // 405 Method Not Allowed
                echo view('themes/master/errors/405', ['methods' => $allowedMethods]);
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                list($class, $method) = explode("@", $handler, 2);
                $class = '\App\Controllers\\' . str_replace('/', '\\', $class);
                echo call_user_func_array(array(new $class, $method), $vars);
                break;
        }
    }

}