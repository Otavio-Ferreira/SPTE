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

    private function calcularRiscoEvasao($nota, $frequencia, $categoria)
    {
        $notaNorm = $nota / 10;
        $freqNorm = $frequencia / 100;
        $categoriaStr = strtolower($categoria);
        if (strpos($categoriaStr, 'alta') !== false) {
            $c = [-0.68524, 0.71897, 0.95468, 0.5277, -0.40559];
        } elseif (strpos($categoriaStr, 'média') !== false || strpos($categoriaStr, 'media') !== false) {
            $c = [-0.0715066751, 0.6427844882, 1.003045137, 0.05721551176, -0.2383343929];
        } else {
            $c = [-0.4073363001, 0.8076287349, 1.078414918, -0.04895104895, -0.4886628523];
        }

        $chance = $c[0] + ($c[1] * $freqNorm) + ($c[2] * $notaNorm) + ($c[3] * pow($freqNorm, 2)) + ($c[4] * pow($notaNorm, 2));

        $chance = max(0, min(1, $chance));
        $indiceRisco = 1 - $chance;

        if ($indiceRisco >= 0.6) {
            return ['risco' => 'Alto', 'cor' => 'bg-danger'];
        } elseif ($indiceRisco >= 0.3) {
            return ['risco' => 'Médio', 'cor' => 'bg-warning text-dark'];
        } else {
            return ['risco' => 'Baixo', 'cor' => 'bg-success'];
        }
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
        if (isset($medias[0]['nota']) && isset($frequencias[0]['assiduidade'])) {
            $riscoCalculado = $this->calcularRiscoEvasao($medias[0]['nota'], $frequencias[0]['assiduidade'], $aluno["nome_categoria"]);
        } else {
            $riscoCalculado = ['risco' => 'Nenhum', 'cor' => 'bg-secondary'];
        }
        $this->view->risco = $riscoCalculado["risco"];
        $this->view->cor = $riscoCalculado["cor"];
        $this->render("historico", "layout");
    }
}
