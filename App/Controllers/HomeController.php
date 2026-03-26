<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

use App\Models\Produtos;
use App\Models\Info;

class HomeController extends Action
{

  public function index()
  {
    $turmas = Container::getModel('Turma');
    $usuario = Container::getModel('Usuario');
    $usuarios = Container::getModel('Usuario');

    $usuario_logado = $usuario->getByEmail($_SESSION['usuario']);

    $termoBusca = $_GET['busca'] ?? '';

    if ($usuario_logado['tipo'] == 'Professor') {
        if (!empty($termoBusca)) {
            $turmas = $turmas->searchByIdUser($usuario_logado['id'], $termoBusca);
        } else {
            $turmas = $turmas->getByIdUser($usuario_logado['id']);
        }
    } else {
        if (!empty($termoBusca)) {
            $turmas = $turmas->searchAll($termoBusca);
        } else {
            $turmas = $turmas->getAll();
        }
        
        $usuarios = $usuarios->getAll();
    }

    $this->view->turmas = $turmas;
    $this->view->usuario = $usuario_logado;
    $this->view->usuarios = $usuarios;

    $this->render("index", "layout");
  }
}
