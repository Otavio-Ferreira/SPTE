<?php

namespace App\Controllers;

use App\Models\Usuario;
use MF\Controller\Action;
use MF\Model\Container;

class LoginController extends Action
{

  public function login()
  {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (
      isset($_POST['email']) && !empty($_POST['email']) &&
      isset($_POST['password']) && !empty($_POST['password'])
    ) {
      $email = addslashes($_POST['email']);
      $password = addslashes($_POST['password']);

      $usuario = Container::getModel("Usuario");

      if ($usuario->verifyUser($email, $password)) {
        
        $_SESSION['usuario_logado'] = true;
        $_SESSION['usuario'] = $email;
        
        header('Location: /home');
        exit;
        
      } else {
        $_SESSION['errorLogin'] = "Email ou senha incorretas!";
        header('Location: /');
        exit;
      }
    }

    $this->render("index", "login");
  }

  public function logout()
  {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    session_unset();
    session_destroy();

    header('Location: /');
    exit;
  }
}