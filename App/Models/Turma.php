<?php
  namespace App\Models;
  use MF\Model\Model;
  
  class Turma extends Model{
    protected $db;

    public function __construct(\PDO $db){
      $this->db = $db;
    }

    public function getAll(){
      $query = "select * from Turma";
      return $this->db->query($query)->fetchAll();
    }

    public function getByIdUser($id_usuario){
      $query = "select * from Turma";
      return $this->db->query($query)->fetchAll();
    }

    public function store($ano, $serie, $turno, $id_usuario) {
      try {
          $this->db->beginTransaction();

          $queryTurma = "INSERT INTO Turma (ano, serie, turno) VALUES (:ano, :serie, :turno)";
          $stmtTurma = $this->db->prepare($queryTurma);
          $stmtTurma->bindValue(':ano', $ano);
          $stmtTurma->bindValue(':serie', $serie);
          $stmtTurma->bindValue(':turno', $turno);
          $stmtTurma->execute();

          $id_turma_gerado = $this->db->lastInsertId();

          $queryUsuarioTurma = "INSERT INTO Usuario_Turma (id_usuario, id_turma) VALUES (:id_usuario, :id_turma)";
          $stmtUsuarioTurma = $this->db->prepare($queryUsuarioTurma);
          $stmtUsuarioTurma->bindValue(':id_usuario', $id_usuario);
          $stmtUsuarioTurma->bindValue(':id_turma', $id_turma_gerado);
          $stmtUsuarioTurma->execute();

          $this->db->commit();

          return true;

        }
        catch (\PDOException $e) {
            $this->db->rollBack();
            
            return false; 
        }
    }
  }
?>