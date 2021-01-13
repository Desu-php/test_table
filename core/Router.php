<?php

namespace core;

class Router
{
    protected static $routes = [];
    protected static $route = [];

    public static function add($pattern, $route = [])
    {
        $pattern = self::generateRegExp(trim($pattern, '/'));
        self::$routes[$pattern] = $route;
    }

    public static function getRoutes()
    {
        return self::$routes;
    }

    public static function getCurrentRoute()
    {
        if (self::$route !== null) {
            return self::$route;
        }
        return null;
    }

    private static function match()
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        $match = strripos($url, '?');

        if ($match !== false) {
            $url = substr($url, 0, $match);
        }

        foreach (self::$routes as $pattern => $route) {
            if (preg_match($pattern, $url, $matches)) {
                $params = [];
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $params[] = $value;
                    }

                }
                $route['params'] = $params;
                self::$route = $route;
                return true;
            }
        }
        return false;
    }

    public static function run()
    {
        if (self::match()) {
            $class = 'app\controllers\\' . self::$route['controller'] . 'Controller';
            $method = self::$route['action'];
            if (class_exists($class)) {
                if (method_exists($class, $method)) {
                    $controller = new $class;
                    $controller->$method(...self::$route['params']);
                } else {
                    throw new \Exception('method ' . self::$route['action'] . ' in class ' . $class . ' does not exists');
                }
            } else {
                throw new \Exception('class ' . $class . ' does not exists');
            }
        } else {
            echo "Error 404 page not found";
        }

    }

    protected static function generateRegExp($pattern)
    {
        $patternAsRegex = preg_replace_callback('#:([\w]+)\+?#', function ($m) {
            return '(?P<' . $m[1] . '>[^/]+)';
        }, $pattern);

        $regex = '#^' . $patternAsRegex . '$#';
        return $regex;
    }

}
