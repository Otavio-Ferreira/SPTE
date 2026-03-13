<?php
  namespace App\Controllers;

  use MF\Controller\Action;
  use MF\Model\Container;

  use App\Models\Produtos;
  use App\Models\Info;
  
  class HomeController extends Action{

    public function index(){
      $turmas = Container::getModel('Turma');
      $usuario = Container::getModel('Usuario');
      $usuarios = Container::getModel('Usuario');

      $usuario_logado = $usuario->getByEmail($_SESSION['usuario']);
    
      if($usuario_logado['tipo'] == 'Professor'){
        $turmas = $turmas->getByIdUser($usuario_logado['id']);
      }
      else{
        $turmas = $turmas->getAll();
        $usuarios = $usuarios->getAll();
      }

      $this->view->turmas = $turmas;
      $this->view->usuario = $usuario_logado;
      $this->view->usuarios = $usuarios;

      $this->render("index", "layout");
    }
  }
?>