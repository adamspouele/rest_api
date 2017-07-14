<?php

namespace App\Router;

class Router{
    private $url; 
    private $routes = []; 
    private $routesNames = [];

    public function __construct($url){
        $this->url = $url;
    }

    public function get($path, $callback, $name = null){
        return $this->add($path, $callback, $name, 'GET');
    }

    public function post($path, $callback, $name = null){
        return $this->add($path, $callback, $name, 'POST');
    }

    private function add($path, $callback, $name, $method){
        $route = new Route($path, $callback);
        $this->routes[$method][] = $route;
        if(is_string($callback) && $name === null){
            $name = $callback;
        }

        if($name){
            $this->routesNames[$name] = $route;
        }

        return $route; 
    }

    public function run(){
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){
            throw new RouterException('REQUEST_METHOD does not exist');
        }

        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route){
            if($route->match($this->url)){
                return $route->call();
            }
        }
        throw new RouterException('No matching routes');
    }

    public function url($name, $params = []){
        if(!isset($this->routesNames[$name])){
            throw new RouterException('No route matches this name');
        }

        return $this->routesNames[$name]->getUrl($params);
    }
}