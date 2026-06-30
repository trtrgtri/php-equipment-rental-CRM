<?php

class Router
{
    private array $routes = ['GET' => [], 'POST' => []];

    public function get(string $path, array $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, array $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    public function dispatch(string $method, string $path, array $container): void
    {
        $pathExists = isset($this->routes['GET'][$path]) || isset($this->routes['POST'][$path]);

        if (!isset($this->routes[$method][$path])) {
            if ($pathExists) {
                http_response_code(405);
                render('errors/405', ['title' => '405 Method Not Allowed']);
                exit;
            }

            http_response_code(404);
            render('errors/404', ['title' => '404 Not Found']);
            exit;
        }

        [$class, $action] = $this->routes[$method][$path];
        $container[$class]->$action();
    }
}
