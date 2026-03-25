<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap
{

  protected function initRoutes()
  {
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

    // Rotas para turmas

    $routes['adicionarTurma'] = array(
      'route' => '/adicionar/turma',
      'controller' => 'TurmaController',
      'action' => 'store',
      'auth' => true
    );

    $routes['turmaAlunos'] = array(
      'route' => '/turma/alunos/{id}',
      'controller' => 'TurmaController',
      'action' => 'alunos',
      'auth' => true
    );

    // Rotas para alunos

    $routes['adicionarAluno'] = array(
      'route' => '/adicionar/aluno/{id}',
      'controller' => 'AlunoController',
      'action' => 'store',
      'auth' => true
    );

    $routes['aluno'] = array(
      'route' => '/aluno/{id}',
      'controller' => 'AlunoController',
      'action' => 'show',
      'auth' => true
    );

    // Rotas para usuários

    $routes['usuarios'] = array(
      'route' => '/usuarios',
      'controller' => 'UsuarioController',
      'action' => 'index',
      'auth' => true
    );

    $routes['sair'] = array(
      'route' => '/sair',
      'controller' => 'LoginController',
      'action' => 'logout',
      'auth' => true
    );

    $routes['perfil_editar'] = array(
      'route' => '/perfil/editar',
      'controller' => 'UsuarioController',
      'action' => 'editar',
      'auth' => true
    );

    $routes['perfil_salvar'] = array(
      'route' => '/perfil/salvar',
      'controller' => 'UsuarioController',
      'action' => 'salvarPerfil',
      'auth' => true
    );

    $routes['usuario_turmas'] = array(
      'route' => '/usuario/turmas',
      'controller' => 'UsuarioController',
      'action' => 'turmas',
      'auth' => true
    );

    $this->setRoutes($routes);
  }
}
