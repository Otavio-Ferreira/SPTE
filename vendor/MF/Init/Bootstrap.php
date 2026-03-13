<?php
namespace MF\Init;

abstract class Bootstrap {
    private $routes;

    abstract protected function initRoutes();

    public function __construct() {
        $this->initRoutes();
        $this->run($this->getUrl());
    }
    
    public function getRoutes() {
        return $this->routes;
    }

    public function setRoutes(array $routes) {
        $this->routes = $routes;
    }

    protected function run($url) {
        $rotaEncontrada = false;

        foreach ($this->getRoutes() as $key => $route) {
            if ($url == $route['route']) {
                $rotaEncontrada = true;

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
                $controller->$action();
                
                break;
            }
        }

        if (!$rotaEncontrada) {
            echo 'Erro 404: Página não encontrada';
            exit;
        }
    }

    protected function getUrl() {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
}
?>