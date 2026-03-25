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

  public function editar()
  {
      $id_usuario = $_SESSION['id'] ?? 1;

      $usuarioModel = Container::getModel('Usuario');
      $this->view->usuario_edit = $usuarioModel->getById($id_usuario);

      $this->render("editar", "layout");
  }

  public function salvarPerfil()
  {
      $id_usuario = $_SESSION['id'] ?? 1;

      $nome = $_POST['nome'] ?? '';
      $email = $_POST['email'] ?? '';
      $senha = $_POST['senha'] ?? null;

      $usuarioModel = Container::getModel('Usuario');
      $usuarioModel->atualizarPerfil($id_usuario, $nome, $email, $senha);

      header('Location: /usuarios');
  }

  public function turmas()
  {
      $id_usuario = $_GET['id'] ?? null;

      if (!$id_usuario) {
          header('Location: /usuarios');
          exit;
      }

      $usuarioModel = Container::getModel('Usuario');
      $this->view->usuario = $usuarioModel->getById($id_usuario);

      $turmaModel = Container::getModel('Turma');
      $this->view->turmas = $turmaModel->getTurmasPorUsuario($id_usuario);
      $this->render("turmas", "layout");
  }
}
