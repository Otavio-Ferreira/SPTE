<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;
use App\Models\Produtos;
use App\Models\Info;

class AlunoController extends Action
{

    public function store($id)
    {

        if (
            isset($_POST['nome']) && !empty($_POST['nome']) &&
            isset($_POST['matricula']) && !empty($_POST['matricula']) &&
            isset($_POST['rua']) && !empty($_POST['rua']) &&
            isset($_POST['bairro']) && !empty($_POST['bairro']) &&
            isset($_POST['cidade']) && !empty($_POST['cidade']) &&
            isset($_POST['estado']) && !empty($_POST['estado']) &&
            isset($_POST['id_categoria']) && !empty($_POST['id_categoria'])
        ) {

            $id_turma = addslashes($id);
            $nome = addslashes($_POST['nome']);
            $matricula = addslashes($_POST['matricula']);
            $rua = addslashes($_POST['rua']);
            $bairro = addslashes($_POST['bairro']);
            $cidade = addslashes($_POST['cidade']);
            $estado = addslashes($_POST['estado']);
            $id_categoria = addslashes($_POST['id_categoria']);

            $aluno = Container::getModel("Aluno");

            if ($aluno->store($id_turma, $nome, $matricula, $rua, $bairro, $cidade, $estado, $id_categoria)) {

                $_SESSION['msg']['text'] = "Dados enviados corretamente!";
                $_SESSION['msg']['type'] = "success";
            } else {
                $_SESSION['msg']['text'] = "Erro ao enviar dados!";
                $_SESSION['msg']['type'] = "danger";
            }

            header('Location: /turma/alunos/' . $id);
            exit;
        } else {
            $_SESSION['msg']['text'] = "É neceessário enviar todos os dados!";
            $_SESSION['msg']['type'] = "danger";

            header('Location: /turma/alunos/' . $id);
            exit;
        }

        header('Location: /turma/alunos/' . $id);
    }

    public function show($id)
    {

        $alunoModel = Container::getModel('Aluno');
        $mediaModel = Container::getModel('Media');
        $freqModel = Container::getModel('Frequencia');

        $aluno = $alunoModel->getById($id);
        $frequencias = $freqModel->getByAlunoId($id);
        $medias = $mediaModel->getByAlunoId($id);

        $this->view->aluno = $aluno;
        $this->view->frequencias = $frequencias;
        $this->view->medias = $medias;

        $this->render("historico", "layout");
    }
}
