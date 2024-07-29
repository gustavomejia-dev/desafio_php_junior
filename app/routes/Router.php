<?php
namespace App\Routes;
class Router {
    private static $routes = [];

    public static function get($route, $action) {
        self::addRoute('GET', $route, $action);
    }

    private static function put($route, $action) {
        self::addRoute('PUT', $route, $action);
    }
    private static function patch($route, $action) {
        self::addRoute('patch', $route, $action);
    }
    private static function post($route, $action) {
        self::addRoute('POST', $route, $action);
    }
   
    private static function addRoute($method, $route, $action) {
        self::$routes[$method][$route] = $action;
    }

    public function dispatch($uri, $requestMethod) {
        $uri = parse_url($uri, PHP_URL_PATH);

        if (isset($this->routes[$requestMethod][$uri])) {
            $action = $this->routes[$requestMethod][$uri];
            if (is_callable($action)) {
                call_user_func($action);
            } elseif (is_string($action)) {
                $this->callController($action);
            }
        } else {
            $this->notFound();
        }
    }

    private function callController($action) {
        list($controller, $method) = explode('@', $action);
        $controller = "App\\Controllers\\$controller";
        if (class_exists($controller)) {
            $controller = new $controller();
            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound();
        }
    }

    private function notFound() {
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
    }
}
