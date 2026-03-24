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
    // Verifica se a requisição tem o "crachá" do AJAX
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    
    // Se for AJAX, usa o layout limpo. Se for acesso normal, usa o layout completo.
    $layout_escolhido = $isAjax ? "layout_fetch" : "layout";

    $this->render("index", $layout_escolhido);
    }

    public function alunos() {
      // 1. Verifica se o ID da turma foi passado na URL (?id=...)
      $id_turma = isset($_GET['id']) ? $_GET['id'] : null;

      // Se não houver ID, redireciona de volta para a home
      if (!$id_turma) {
          header('Location: /home');
          exit;
      }

      // 2. Instancia o modelo de Alunos
      // Nota: Precisa de ter um modelo Aluno.php criado em App\Models
      $alunoModel = Container::getModel('Aluno');

      // 3. Busca os alunos daquela turma específica
      // Assumindo que criará um método getByTurmaId() no seu modelo Aluno
      $alunos = $alunoModel->getByTurmaId($id_turma);

      // (Opcional) Pode também buscar os dados da Turma para mostrar no título
      // $turmaModel = Container::getModel('Turma');
      // $this->view->turma_atual = $turmaModel->getById($id_turma);

      // 4. Passa a lista de alunos para a view (para o foreach no alunos.phtml)
      $this->view->alunos = $alunos;

      // 5. Renderiza o ficheiro 'alunos.phtml' utilizando o 'layout.phtml'
      $this->render("aluno", "layout_fetch");
    }

    public function historico() {
      // 1. Verifica se o ID do aluno foi passado na URL (?id=...)
      $id_aluno = isset($_GET['id']) ? $_GET['id'] : null;

      if (!$id_aluno) {
          header('Location: /home');
          exit;
      }

      
      $alunoModel = Container::getModel('Aluno');
      $mediaModel = Container::getModel('Media');
      $freqModel = Container::getModel('Frequencia');
      
      $aluno = $alunoModel->getById($id_aluno);
      $frequencias = $freqModel->getByAlunoId($id_aluno);
      $medias = $mediaModel->getByAlunoId($id_aluno);
      
      $this->view->aluno = $aluno;
      $this->view->frequencias = $frequencias;
      $this->view->medias = $medias;

      $this->render("historico", "layout_fetch");
    }

    public function usuarios() {
      $usuarios = Container::getModel('Usuario');

      $this->view->usuarios = $usuarios->getAll();

      $this->render("usuario", "layout_fetch");
    }
  }
?>