<?php

namespace MF\Init;

abstract class Bootstrap
{
    private $routes;

    abstract protected function initRoutes();

    public function __construct()
    {
        $this->initRoutes();
        $this->run($this->getUrl());
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function setRoutes(array $routes)
    {
        $this->routes = $routes;
    }

    protected function run($url)
    {
        $rotaEncontrada = false;

        $urlPath = parse_url($url, PHP_URL_PATH);

        $urlPath = rtrim($urlPath, '/') ?: '/';

        foreach ($this->getRoutes() as $key => $route) {
            $routePath = rtrim($route['route'], '/') ?: '/';

            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $routePath);

            $pattern = '@^' . $pattern . '$@';

            if (preg_match($pattern, $urlPath, $matches)) {
                $rotaEncontrada = true;

                array_shift($matches);

                $parametros = $matches;

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                if (isset($route['auth']) && $route['auth'] === true) {
                    if (!isset($_SESSION['usuario_logado']) || $_SESSION['usuario_logado'] !== true) {
                        header('Location: /');
                        exit;
                    }
                }

                $class = "App\\Controllers\\" . ucfirst($route['controller']);
                $controller = new $class;
                $action = $route['action'];

                $controller->$action(...$parametros);

                break;
            }
        }

        if (!$rotaEncontrada) {
            echo 'Erro 404: Página não encontrada';
            exit;
        }
    }

    protected function getUrl()
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}
