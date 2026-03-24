<?php
  namespace App;
  use MF\Init\Bootstrap;

  class Route extends Bootstrap{

    protected function initRoutes(){
      $routes['login'] = array(
        'route' => '/',
        'controller' => 'LoginController',
        'action' => 'login',
        'auth' => false
      );

      $routes['home'] = array(
        'route' => '/home',
        'controller' => 'HomeController',
        'action' => 'index',
        'auth' => true
      );

      $routes['adicionarTurma'] = array(
        'route' => '/adicionar/turma',
        'controller' => 'TurmaController',
        'action' => 'store',
        'auth' => true
      );

      $routes['usuarios'] = array(
        'route' => '/usuarios',
        'controller' => 'UserController',
        'action' => '/usuarios',
        'auth' => true
      );

      $routes['sair'] = array(
        'route' => '/sair',
        'controller' => 'LoginController',
        'action' => 'logout',
        'auth' => true
      );

      $routes['turma_alunos'] = array(
    'route' => '/turma/alunos',
    'controller' => 'HomeController',
    'action' => 'alunos'
);

      $routes['aluno_historico'] = array(
        'route' => '/aluno/historico',
        'controller' => 'HomeController',
        'action' => 'historico'
      );

      $this->setRoutes($routes);

    }
  }

?>