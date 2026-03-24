<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

use App\Models\Produtos;
use App\Models\Info;

class UsuarioController extends Action
{

  public function index()
  {
    $usuarios = Container::getModel('Usuario');

    $this->view->usuarios = $usuarios->getAll();

    $this->render("index", "layout");
  }
}
