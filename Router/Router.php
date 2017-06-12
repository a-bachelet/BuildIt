<?php

namespace BuildIt\Router;

/**
 * Class Router
 * @package BuildIt\Router
 */
abstract class Router implements RouterInterface
{
    /**
     * @var string $url
     * Url which has been called by user.
     */
    private $url = null;

    /**
     * @var string $controllersPath
     * Folder Path where all non-admin Controllers are stored.
     */
    private $controllersPath = null;

    /**
     * @var string $adminControllersPath
     * Folder Path where all admin Controllers are stored.
     */
    private $adminControllersPath = null;

    /**
     * @var Route[] $routes
     * Array which contains all the Application Routes.
     */
    private $routes = [];

    /**
     * Router constructor.
     * @param $url
     * @param $controllersPath
     * @param $adminControllersPath
     */
    protected function __construct($url, $controllersPath, $adminControllersPath)
    {
        $this->url = $url;
        $this->controllersPath = $controllersPath;
        $this->adminControllersPath = $adminControllersPath;
    }

    /**
     * Adds a new Route in the $this->routes[$method] array
     * @param $path
     * @param $action
     * @param $method
     * @param $admin
     * @return Route
     */
    private  function add($path, $action, $method, $admin) {
        $route = new Route($path, $action, $admin);
        $this->routes[$method][] = $route;
        return $route;
    }

    /**
     * Adds a new Route in the $this->routes['GET'] array
     * @param $path
     * @param $action
     * @param $admin
     * @return Route
     */
    public function get($path, $action, $admin) {
        return $this->add($path, $action, 'GET', $admin);
    }

    /**
     * Adds a new Route in the $this->routes['POST'] array
     * @param $path
     * @param $action
     * @param $admin
     * @return Route
     */
    public function post($path, $action, $admin) {
        return $this->add($path, $action, 'POST', $admin);
    }

    /**
     * Runs Router checking job
     * @throws \Exception
     */
    public function dispatch()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new \Exception('UNAUTHORIZED REQUEST METHOD');
        } else {
            $match = false;
            /** @var Route $route **/
            foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
                if ($route->match($this->url)) {
                    $match = true;
                    if ($route->isAdmin()) {
                        if ($this->isUserAdminCallable()) {
                            $route->call($this->adminControllersPath);
                        } else {
                            $this->notFoundCallable();
                        }
                    } else {
                        $route->call($this->controllersPath);
                    }
                }
            }
            if ($match === false) {
                $this->notFoundCallable();
            }
        }
    }

    /**
     * Function which will be called if a Route has a NOT FOUND state
     */
    public function notFoundCallable() {}

    /**
     * Function which will be called to know if the user is ADMIN or not
     */
    public function isUserAdminCallable() {}
}