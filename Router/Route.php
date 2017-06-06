<?php

namespace BuildIt\Router;

/**
 * Class Route
 * @package BuildIt\Router
 */
class Route
{
    /**
     * @var string $path
     * Path which Route will respond to.
     */
    private $path = null;

    /**
     * @var string | callable $action
     * Action which Route will execute.
     */
    private $action = null;

    /**
     * @var array $matches
     * Array where Regexp matching params will be stored.
     */
    private $matches = [];

    /**
     * @var array $params
     * Array where wanted params will be stored.
     */
    private $params = [];

    /**
     * @var bool $admin
     * Property which will tell if this Route should be called by an ADMIN.
     */
    private $admin = false;

    /**
     * Route constructor.
     * @param $path
     * @param $action
     * @param $admin
     */
    public function __construct($path, $action, $admin)
    {
        $this->path = trim($path, '/');
        $this->action = $action;
        $this->admin = $admin;
    }

    /**
     * Tells if Route matches the given $url
     * @param $url
     * @return bool
     */
    public function match($url) {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:[\w]+#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }
        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    /**
     * Tells if Route specified param matches given $match
     * @param $match
     * @return string
     */
    private function paramMatch($match) {
        $match[0] = ltrim($match[0], ':');
        if (isset($this->params[$match[0]])) {
            return '(' . $this->params[$match[0]] . ')';
        } else {
            return '([^/]+)';
        }
    }

    /**
     * Adds a new Regexp param to Route
     * @param $param
     * @param $regex
     * @return $this
     */
    public function param($param, $regex) {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this;
    }

    /**
     * Executes Route action
     * @param $controllersPath
     */
    public function call($controllersPath = '') {
        if (is_string($this->action) && $controllersPath !== '') {
            $action = explode('#', $this->action);
            $controller = $action[0];
            $controller = $controllersPath . ucfirst($controller) . 'Controller';
            $controller = new $controller();
            $action = $action[1];
            call_user_func_array([$controller, $action], $this->matches);
        } else {
            $func = $this->action;
            $func();
        }
    }

    /**
     * Tells if Route and ADMIN protected Route
     * @return bool
     */
    public function isAdmin()
    {
        return $this->admin;
    }
}