<?php
  namespace App\Controllers;

  use MF\Controller\Action;
  use MF\Model\Container;
  use App\Models\Produtos;
  use App\Models\Info;
  
  class TurmaController extends Action{

    public function store()
    {
    
      if (
        isset($_POST['ano']) && !empty($_POST['ano']) &&
        isset($_POST['serie']) && !empty($_POST['serie']) &&
        isset($_POST['turno']) && !empty($_POST['turno']) &&
        isset($_POST['id_usuario']) && !empty($_POST['id_usuario']) 
      ) {

        $ano = addslashes($_POST['ano']);
        $serie = addslashes($_POST['serie']);
        $turno = addslashes($_POST['turno']);
        $id_usuario = addslashes($_POST['id_usuario']);

        $turma = Container::getModel("Turma");

        if ($turma->store($ano, $serie, $turno, $id_usuario)) {

          $_SESSION['msg']['text'] = "Dados enviados incorretos!";
          $_SESSION['msg']['type'] = "success";
        } else {
          $_SESSION['msg']['text'] = "Erro ao enviar dados!";
          $_SESSION['msg']['type'] = "error";
        }

        header('Location: /home');
        exit;

      }
      else{
        $_SESSION['msg']['text'] = "É neceessário enviar todos os dados!";
        $_SESSION['msg']['type'] = "error";

        header('Location: /home');
        exit;
      }

      $this->render("index", "layout");
    }
  }
?>